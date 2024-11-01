<?php
class SR_Partner_Integrate_FormsManagementSystem extends SR_Partner_Core {
    /**
     * Register hooks for FormsManagementSystem
     * of the plugin.
     *
     * @since       1.0.0
     * @access      public
     */
    public function register_hooks()
    {
        // FormsManagementSystem
        add_filter('fms_valid_contact_form', array(&$this, 'catch_form_data'), 10, 1);
    }   

    /**
     * Catch the form data from FormsManagementSystem
     * @since       1.0.0     
     * @access      public
     * @param       $form_id data from  FormsManagementSystem form
     * @return      boolean
     */
    public function catch_form_data($form_id)
    {
        try {
            if (!class_exists('CFDBPostDataConverter')) {
                // FormsManagementSystem not installed
                return true;
            }

            $title = get_the_title($form_id);
            $converter = new CFDBPostDataConverter();
            $converter->addExcludeField('post_nonce_field');
            $converter->addExcludeField('form-type');
            $converter->addExcludeField('fms-ajax');
            $converter->addExcludeField('action');
            $data = $converter->convert($title);

            // CFDBPostDataConverter won't capture files how they are organized here
            if (is_array($_FILES) && !empty($_FILES)) {
                foreach ($_FILES as $key => $file) {
                    if (is_array($file['tmp_name'])) {
                        for ($idx = 0; $idx < count($file['tmp_name']); ++$idx) {
                            if (is_uploaded_file($file['tmp_name'][$idx])) {
                                $fileKey = ($idx > 0) ? ($key . $idx) : $key;
                                $data->posted_data[$fileKey] = $file['name'][$idx];
                                $data->uploaded_files[$fileKey] = $file['tmp_name'][$idx];
                            }
                        }
                    }
                }
            }          

            $data  = $this->object_to_array($data);
            
            return $this->_helper->catch_lead($data);

        } catch (Exception $ex) {
            $this->_helper->log($ex, 'Error');
        }

        return true;
    }

    public function object_to_array($obj) {
        if(is_object($obj)) $obj = (array) $obj;
        if(is_array($obj)) {
            $new = array();
            foreach($obj as $key => $val) {
                $new[$key] = object_to_array($val);
            }
        }
        else $new = $obj;
        return $new;       
    }
}