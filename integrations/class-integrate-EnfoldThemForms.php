<?php
class SR_Partner_Integrate_EnfoldThemForms extends SR_Partner_Core {
    /**
     * Register hooks for EnfoldThemForms
     * of the plugin.
     *
     * @since       1.0.0
     * @access      public
     */
    public function register_hooks()
    {
        add_filter('avf_form_send', array($this, 'catch_form_data'), 10, 3);
    }   

    /**
     * Catch the form data from EnfoldThemForms
     * @since       1.0.0     
     * @access      public
     * @param       $bool boolean
     * @param       $new_post array
     * @param       $form_params string
     * @return      boolean
     */
    public function catch_form_data($bool, $new_post, $form_params)
    {
        try {
            if (is_array($new_post)) {
                $postedData = array();
                foreach ($new_post as $key => $value) {
                    $postedData[$key] = urldecode($value);
                }

                $title = 'Enfold';
                if (is_array($form_params) &&
                        isset($form_params['heading']) &&
                        $form_params['heading']
                ) {
                    $title = strip_tags($form_params['heading']);
                }

                $data = array(
                    'title'         => $title,
                    'posted_data'   => $postedData
                );

                return $this->_helper->catch_lead($data);

            }
        } catch (Exception $ex) {
            $this->_helper->log($ex, 'Error');
        }

        return true;
    }
}