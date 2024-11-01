<?php
class SR_Partner_Integrate_CFormsII extends SR_Partner_Core {
    /**
     * Register hooks for CFormsII
     * of the plugin.
     *
     * @since       1.0.0
     * @access      public
     */
    public function register_hooks()
    {
        add_action('cforms2_after_processing_action', array($this, 'catch_form_data'), 10, 1);
    }   

    /**
     * Catch the form data from CFormsII
     * @since       1.0.0     
     * @access      public
     * @param       $trackf data from  CFormsII form
     * @return      boolean
     */
    public function catch_form_data($trackf)
    {
        try {
            if (isset($trackf['data'])) {

                $form_data = array();
                foreach ($trackf['data'] as $key => $value) {
                    if (strpos($key, '$$$') !== 0) {
                        $form_data[$key] = $value;
                    }
                }

                $data = array(
                    'title'         => isset($trackf['title']) ? $trackf['title'] : '',
                    'posted_data'   => $form_data
                );

                return $this->_helper->catch_lead($data);
            }
        } catch (Exception $ex) {
            $this->_helper->log($ex, 'Error');
        }

        return true;
    }
}