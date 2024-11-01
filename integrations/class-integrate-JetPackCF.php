<?php
class SR_Partner_Integrate_JetPackCF extends SR_Partner_Core {
    
    /**
     * Register hooks for JetPack Contact Form 
     * of the plugin.
     *
     * @since       1.0.0
     * @access      public
     */
    public function register_hooks()
    {
        add_action('grunion_pre_message_sent', array($this, 'catch_form_data'), 10, 3);        
    }

    /**
     * Catch the form data from JetPack Contact Form 
     * @since       1.0.0
     * @access      public
     * @param       int     $post_id 
     * @param       array   $all_values 
     * @param       array   $extra_values 
     * @return      boolean
     */
    public function catch_form_data($post_id, $all_values, $extra_values)
    {
        try {
            $data = $this->convert_data($post_id, $all_values);
            return $this->_helper->catch_lead($data);
        } catch (Exception $ex) {
            $this->_helper->log($ex, 'Error');
        }
        return true;
    }


    /**
     * @since       1.0.0
     * @access      public
     * @param       int     $post_id 
     * @param       array   $all_values 
     * @return      array
     */
    public function convert_data($post_id, $all_values)
    {
        $title = 'JetPack Contact Form';
        if (isset($_POST['contact-form-id'])) {
            $title .= ' ' . $_POST['contact-form-id'];
        }
        else {
            $title .= ' ' . $post_id;
        }
        $all_values['post_id'] = $post_id;
        return  array(
                    'title'         => $title,
                    'posted_data'   => $all_values
                );
    }
}