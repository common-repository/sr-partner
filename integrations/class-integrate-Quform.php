<?php
class SR_Partner_Integrate_Quform extends SR_Partner_Core {
    
    /**
     * Register hooks for Quform
     * of the plugin.
     *
     * @since       1.0.0
     * @access      public
     */
    public function register_hooks()
    {
        // http://support.themecatcher.net/quform-wordpress/guides/hooks/iphorm_post_process
        add_action('iphorm_post_process', array(&$this, 'catch_form_data'), 10, 1);
    }

    /**
     * Catch the form data from Quform
     * @since       1.0.0
     * @access      public
     * @param       array   $form 
     * @return      boolean
     */
    public function catch_form_data($form)
    {
        try {
            $data = $this->convert_data($form);
            return $this->_helper->catch_lead($data);
        } catch (Exception $ex) {
            $this->_helper->log($ex, 'Error');
        }
        return true;
    }


    /**
     * @since       1.0.0
     * @access      public
     * @param       $form iPhorm
     * @return      array
     */
    public function convert_data($form)
    {
        // http://support.themecatcher.net/quform-wordpress/guides/basic/getting-form-values
        $allValues = $form->getValues();

        if (is_array($allValues)) {

            $postedData = array();

            foreach ($allValues as $fieldId => $value) {
                // $fieldId is something like "iphorm_2_1"
                // get the human-readable field label
                $fieldName = $fieldId; //iPhorm_Element
                $element = $form->getElement($fieldId);
                if (is_object($element)) {
                    $fieldName = $element->getLabel();
                }

                if (is_array($value)) {
                    if (array_key_exists('day', $value)) {
                        $postedData[$fieldName] = sprintf('%s-%s-%s', $value['year'], $value['month'], $value['day']);
                    } else if (array_key_exists('hour', $value)) {
                        $postedData[$fieldName] = sprintf('%s:%s %s', $value['hour'], $value['minute'], $value['ampm']);
                    } else if (array_key_exists(0, $value)) {
                        if (is_array($value[0])) {
                            // file upload
                            foreach ($value as $upload) {
                                $postedData[$fieldName] = $upload['text'];                                
                            }
                        } else {
                            $postedData[$fieldName] = implode(',', array_values($value));
                        }
                    }
                } else {
                    $postedData[$fieldName] = $value;
                }
            }

            return  array(
                        'title'         => $form->getName(),
                        'posted_data'   => $postedData
                    );
        }
    }
}