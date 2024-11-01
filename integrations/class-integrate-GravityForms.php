<?php
class SR_Partner_Integrate_GravityForms extends SR_Partner_Core {
    
    /**
     * Register hooks for Gravity Forms
     * of the plugin.
     *
     * @since       1.0.0
     * @access      public
     */
    public function register_hooks()
    {
        add_action('gform_after_submission', array($this, 'catch_form_data'), 10, 2);        
    }

    /**
     * Catch the form data from Gravity Forms
     * @since       1.0.0
     * @access      public
     * @param       array   $entry 
     * @param       array   $form 
     * @return      boolean
     */
    public function catch_form_data($entry, $form)
    {
        try {
            $data = $this->convert_data($entry, $form);
            return $this->_helper->catch_lead($data);
        } catch (Exception $ex) {
            $this->_helper->log($ex, 'Error');
        }
        return true;
    }


    /**
     * converts the form data so we can proccess it later
     * http://www.gravityhelp.com/documentation/page/Gform_after_submission
     * @param $entry Entry Object The entry that was just created.
     * http://www.gravityhelp.com/documentation/page/Entry_Object
     * @param $form Form Object The current form
     * http://www.gravityhelp.com/documentation/page/Form_Object
     * @return array
     */
    public function convert_data($entry, $form)
    {

        $postedData = array();
        $uploadFiles = array();

        // Iterate through the field definitions and get their values
        if (!is_array($form['fields'])) {
            return true;
        }

        foreach ($form['fields'] as $field) {

            // Gravity Forms 1.8.5 $field was an array
            // Gravity Forms 1.9.1.2 $field is an object
            if (is_object($field)) {
                $field = (array)$field;
            }

            $fieldId = $field['id'];
            $fieldName = (isset($field['adminLabel']) && $field['adminLabel']) ?
                    $field['adminLabel'] : // Use override label if exists
                    $field['label'];
            
            if (isset($entry[$fieldId])) {
                switch ($field['type']) {
                    case 'list' :
                        $list = unserialize($entry[$fieldId]);
                        if ($list) {
                            // $list may be a list of strings or
                            // or in the case of Gravity Form List with columns,
                            /*
                             Array
                                (
                                    [0] => Array
                                        (
                                            [Column 1] => hi
                                            [Column 2] => there
                                            [Column 3] => howdy
                                        )
                                )
                             */
                            if (! empty($list) && is_array($list[0])) {
                                $colMatrix = array();
                                foreach ($list as $colArray) {
                                    $colList = array();
                                    foreach ($colArray as $colKey => $colValue) {
                                        $colList[] = $colKey . '=' . $colValue;
                                    }
                                    $colMatrix[] = implode('|', $colList);
                                }
                                $postedData[$fieldName] = implode("\n", $colMatrix);
                            } else {
                                $postedData[$fieldName] = implode('|', $list);
                            }
                        } else {
                            if (!isset($postedData[$fieldName]) || $postedData[$fieldName] === '') { // handle duplicate empty hidden fields
                                // List - value is serialized array
                                try {
                                    $valueArray = unserialize($entry[$fieldId]);
                                } catch (Exception $ex) {
                                    $this->_helper->log($ex, 'Error');
                                    $valueArray = '';
                                }
                                
                                if (is_array($valueArray)) {
                                    //$postedData[$fieldName] = '';
                                    // Array of (Array of column-name => value)
                                    $tmpArray = array();
                                    foreach ($valueArray as $listArray) {
                                        $tmpArray[] = implode(',', array_values($listArray));
                                    }
                                    $postedData[$fieldName] = implode('|', $tmpArray);
                                } else {
                                    $postedData[$fieldName] = $entry[$fieldId];
                                }
                            }
                        }
                        break;

                    default:
                        if (!isset($postedData[$fieldName]) || $postedData[$fieldName] === '') { // handle duplicate empty hidden fields
                            $postedData[$fieldName] = $entry[$fieldId];
                        }
                        break;
                }
            } else {
                if (!empty($field['inputs']) && is_array($field['inputs'])) {
                    if ($field['type'] == 'checkbox') {
                        // This is a multi-input field
                        if (!isset($postedData[$fieldName]) || $postedData[$fieldName] === '') { // handle duplicate empty hidden fields
                            $values = array();
                            foreach ($field['inputs'] as $input) {
                                $inputId = strval($input['id']); // Need string value of number like '1.3'
                                if (!empty($entry[$inputId])) {
                                    $values[] = $entry[$inputId];
                                }
                            }
                            $postedData[$fieldName] = implode(',', $values);
                        }
                    } else {
                        foreach ($field['inputs'] as $input) {
                            $inputId = strval($input['id']); // Need string value of number like '1.3'
                            $label = $input['label']; // Assumption: all inputs have diff labels
                            $effectiveFieldName = $fieldName;
                            if (!empty($label)) {
                                $effectiveFieldName = $fieldName . ' ' . $label;
                            }
                            if (!isset($postedData[$effectiveFieldName]) || $postedData[$effectiveFieldName] === '') {  // handle duplicate empty hidden fields
                                if (isset($entry[$inputId])) {
                                    $postedData[$effectiveFieldName] = $entry[$inputId];
                                } else if (isset($entry[$fieldId])) {
                                    $postedData[$effectiveFieldName] = $entry[$fieldId];
                                }
                            }
                        }
                    }
                }
            }
        }

        return  array(
                    'title'         => $form['title'],
                    'posted_data'   => $postedData
                );
    }
}