<?php
class SR_Partner_Integrate_VerySimpleCF extends SR_Partner_Core {
    /**
     * Register hooks for VerySimpleCF
     * of the plugin.
     *
     * @since       1.0.0
     * @access      public
     */
    public function register_hooks()
    {
        // Very Simple Form
        add_action('vscf_before_send_mail', array($this, 'catch_form_data'), 10, 1);
    }   

    /**
     * Catch the form data from VerySimpleCF
     * @since       1.0.0     
     * @access      public
     * @param       $form_data data from  VerySimpleCF form
     * @return      boolean
     */
    public function catch_form_data($form_data)
    {
        try {            
            
            $title = 'Very Simple Form';
            $data  = array(
                'title'         => $title,
                'posted_data'   => $form_data
            );

            return $this->_helper->catch_lead($data);

        } catch (Exception $ex) {
            $this->_helper->log($ex, 'Error');
        }

        return true;
    }
}