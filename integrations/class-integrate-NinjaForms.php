<?php
class SR_Partner_Integrate_NinjaForms extends SR_Partner_Core {
    /**
     * Register hooks for NinjaForms
     * of the plugin.
     *
     * @since       1.0.0
     * @access      public
     */
    public function register_hooks()
    {
        //http://developer.ninjaforms.com/codex/submission-processing-hooks/
        add_action('ninja_forms_submit_data', array($this, 'catch_form_data'), 20);        
    }   

    /**
     * Catch the form data from NinjaForms
     * @since       1.0.0
     * @param       $form_data from Ninjaform
     * @access      public
     * @return      boolean
     */
    public function catch_form_data($form_data)
    {        
        try {
            $data = $this->convert_data($form_data);
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
     * @param       $form_data from Ninjaform
     * @return      array or null
     */
    public function convert_data($form_data)
    {        
        if ( isset($form_data['fields']) && is_array($form_data['fields'])) {
            $postedData = array();
            global $wpdb;
            $table_name = $wpdb->prefix.'nf3_fields';

            if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
                foreach ($form_data['fields'] as $field_id => $value) {
                    if (isset($value['id']) && is_numeric($value['id']) && isset($value['value'])) {

                        //get field lable name
                        $sql    = "SELECT label FROM ".$table_name." WHERE id = %d";
                        $sql    = $wpdb->prepare($sql, $value['id']);
                        $label  = $wpdb->get_var($sql);

                        if (!empty($label)) {
                            $postedData[$label] = $value['value'];
                        }
                    }
                }
                if (empty($postedData)) {
                    return null;
                } else {

                    $data = array(
                        'posted_data' => $postedData,
                    );

                    return $data;
                }

            } else {
                return null;    
            }

        } else {
            return null;    
        }        
    }
}