<?php
class SR_Partner_Integrate_FormCraft extends SR_Partner_Core {
    /**
     * Register hooks for FormCraft
     * of the plugin.
     *
     * @since       1.0.0
     * @access      public
     */
    public function register_hooks()
    {
        // http://formcraft-wp.com/help/formcraft-hooks-filters/
        add_action('formcraft_before_save', array($this, 'catch_form_data'), 10, 4);        
    }   

    /**
     * Catch the form data from FormCraft
     * @since       1.0.0     
     * @access      public
     * @param       $content data from  FormCraft form
     * @param       $meta data from  FormCraft form
     * @param       $raw_content data from  FormCraft form
     * @param       $integrations data from  FormCraft form
     * @return      boolean
     */
    public function catch_form_data($content, $meta, $raw_content, $integrations)
    {
        try {            
            $form_data = array();
            if (!is_array($raw_content)) {
                return;
            }

            foreach ($raw_content as $field) {
                if (is_array($field)) {
                    if ($field['type'] == 'fileupload' &&
                            is_array($field['value']) &&
                            is_array($field['url'])
                    ) {
                        // Handle file uploads
                        $idx = 0;
                        // Array of file names and array of urls to them
                        foreach ($field['value'] as $fileName) {
                            $url = $field['url'][$idx];
                            $pos = strpos($url, 'wp-content');
                            if ($pos !== FALSE) {                                
                                $label = $field['label'];
                                if ($idx > 0) {
                                    // FormCraft allows more then one file to be uploaded under the same form field name
                                    // so create a new field name for additional files
                                    $label = "$label-$idx";
                                }
                                $form_data[$label] = $fileName;
                            }
                            ++$idx;
                        }
                    } else if ($field['type'] = 'matrix' &&
                            is_array($field['value'])) {
                        // Matrix value question:answer
                        $questionAndAnswerArray = array();
                        foreach ($field['value'] as $qAndA) {
                            $questionAndAnswerArray[] = "{$qAndA['question']}|{$qAndA['value']}";
                        }
                        $form_data[$field['label']] = implode("\n", $questionAndAnswerArray);
                    } else if (is_array($field['value'])) {
                        // Array of Strings Value
                        $form_data[$field['label']] = implode(',', $field['value']);
                    } else {
                        // String Value
                        $form_data[$field['label']] = $field['value'];
                    }
                }
            }


            $data = array(
                'title'         => isset($content['Form Name']) ? $content['Form Name'] : '',
                'posted_data'   => $form_data
            );

            return $this->_helper->catch_lead($data);
        } catch (Exception $ex) {
            $this->_helper->log($ex, 'Error');
        }

        return true;
    }
}