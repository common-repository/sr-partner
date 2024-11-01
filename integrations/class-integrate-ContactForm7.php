<?php
class SR_Partner_Integrate_ContactForm7 extends SR_Partner_Core {
    
    /**
     * Register hooks for ContactForm7 
     * of the plugin.
     *
     * @since       1.0.0
     * @access      public
     */
    public function register_hooks()
    {
        add_action('wpcf7_before_send_mail', array($this, 'catch_form_data'));        
    }

    /**
     * Callback from Contact Form 7. CF7 passes an object with the posted data.
     * by this function.
     * @since       1.0.0
     * @access      public
     * @param       $form_data WPCF7_ContactForm
     * @return      bool
     */
    public function catch_form_data($form_data)
    {
        try {
            $data = $this->convert_data($form_data);
            return $this->_helper->catch_lead($data);
        } catch (Exception $ex) {
            $this->_helper->log($ex, 'Error');
        }
        return true;
    }


    /**
     * @since       1.0.0
     * @access      public
     * @param       $form_data WPCF7_ContactForm
     * @return      array
     */
    public function convert_data($form_data)
    {
        if (!isset($form_data->posted_data) && class_exists('WPCF7_Submission')) {
            // Contact Form 7 version 3.9 removed $form_data->posted_data and now
            // we have to retrieve it from an API
            $submission = WPCF7_Submission::get_instance();
            if ($submission) {
                $data = array();

                $data['posted_data']    = $submission->get_posted_data();                                
                $data['title']          = $form_data->title();                
                //$data['uploaded_files'] = $submission->uploaded_files();
                //$data['WPCF7_ContactForm'] = $form_data;
                
                return $data;
            }
        }
        return $form_data;
    }
}