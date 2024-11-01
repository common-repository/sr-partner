<?php
class SR_Partner_Integrate_FSCF extends SR_Partner_Core {

    /**
     * Register hooks for FSCF 
     * of the plugin.
     *
     * @since       1.0.0
     * @access      public
     */
    public function register_hooks()
    {
        add_action('fsctf_mail_sent', array($this, 'catch_form_data'));
    }

    /**
     * Catches the form data from FSCF
     * @since       1.0.0
     * @access      public
     * @param       $form from FSCF form
     * @return      bool
     */
    public function catch_form_data($form)
    {
        try {
            if (isset($form->posted_data['full_message'])) {
                unset($form->posted_data['full_message']);    
            }            
            return $this->_helper->catch_lead($form);
        } catch (Exception $ex) {
            $this->_helper->log($ex, 'FSCF');
        }
        return true;
    }    

}