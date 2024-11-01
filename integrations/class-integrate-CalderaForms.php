<?php
class SR_Partner_Integrate_CalderaForms extends SR_Partner_Core {
    /**
     * Register hooks for CalderaForms
     * of the plugin.
     *
     * @since       1.0.0
     * @access      public
     */
    public function register_hooks()
    {
        // https://calderawp.com/doc/caldera_forms_submit_post_process/
        add_action('caldera_forms_submit_post_process', array($this, 'catch_form_data'), 10, 4);
    }   

    /**
     * Catch the form data from CalderaForms
     * @since       1.0.0     
     * @access      public
     * @param       $form array
     * @param       $referrer array
     * @param       $process_id string
     * @param       int $entry_id
     * @return      boolean
     */
    public function catch_form_data($form, $referrer, $process_id, $entry_id)
    {
        if (!class_exists('Caldera_Forms')) {
            // Caldera not installed
            return true;
        }

        try {
            $data = $this->convert_data($form, $entry_id);

            if ($data) {
                return $this->_helper->catch_lead($data);
            }            
        } catch (Exception $ex) {
            $this->_helper->log($ex, 'Error');
        }

        return true;
    }


    /**
     * @since       1.0.0
     * @access      public
     * @param       $form array
     * @param       int $entry_id
     * @return      array or null
     */
    public function convert_data($form, $entry_id)
    {
        if (is_array($form) && isset($form['fields'])) {
            $title      = isset($form['name']) ? $form['name'] : '';
            $postedData = array();
            $fields     = $form['fields'];
            
            foreach ($fields as $field_id => $field) {

                $field_value = Caldera_Forms::get_field_data($field_id, $form);
                
                if (!array_key_exists($field_id, $form['fields'])) {
                    // ignore non-field entries _entry_id and _entry_token
                    continue;
                }

                $field_name = isset($field['label']) ? $field['label'] : '';
                $is_file    = in_array($field['type'], array('file', 'advanced_file'));

                if (is_array($field_value)) {
                    $postedData[$field_name] = implode(',', $field_value);
                } else if ($is_file && $field_value != null) {
                    //do nothing for files                    
                } else {
                    $postedData[$field_name] = $field_value;
                }
            }

            return  array(
                'title'         => $title,
                'posted_data'   => $postedData
            );
        }
        return null;    
    }
}