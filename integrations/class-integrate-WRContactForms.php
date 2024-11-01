<?php
class SR_Partner_Integrate_WRContactForms extends SR_Partner_Core {
    
    /**
     * Register hooks for WR Contact Forms
     * of the plugin.
     *
     * @since       1.0.0
     * @access      public
     */
    public function register_hooks()
    {
        add_action('wr_contactform_before_save_form', array($this, 'catch_form_data'), 10, 7);
    }

    /**
     * Catch the form data from for WR Contact Forms
     * @since       1.0.0
     * @access      public
     * @param       $dataForms array
     * @param       $postID array
     * @param       $post array
     * @param       $submissionsData array
     * @param       $dataContentEmail array
     * @param       $nameFileByIdentifier array
     * @param       $requiredField array
     * @param       $fileAttach array
     * @return      bool
     */
    public function catch_form_data($dataForms, $postID, $post, $submissionsData, $dataContentEmail, $nameFileByIdentifier, $requiredField, $fileAttach)
    {
        try {
            $data = $this->convert_data($dataForms, $postID, $post, $submissionsData, $dataContentEmail, $nameFileByIdentifier, $requiredField, $fileAttach);
            return $this->_helper->catch_lead($data);
        } catch (Exception $ex) {
            $this->_helper->log($ex, 'Error');
        }
        return true;
    }


    /**
     * converts the form data so we can proccess it later
     * @param       $dataForms array
     * @param       $postID array
     * @param       $post array
     * @param       $submissionsData array
     * @param       $dataContentEmail array
     * @param       $nameFileByIdentifier array
     * @param       $requiredField array
     * @param       $fileAttach array
     * @return      array
     */
    public function convert_data($dataForms, $postID, $post, $submissionsData, $dataContentEmail, $nameFileByIdentifier, $requiredField, $fileAttach)
    {
        $postedData = array();
        foreach ($dataContentEmail as $fieldKey => $fieldValue) {
            $fieldName  = $nameFileByIdentifier[$fieldKey];            
            $fieldValue = trim(preg_replace('#<[^>]+>#', ' ', $fieldValue));

            $postedData[$fieldName] = $fieldValue;
        }

        $data = array(
            'title'         => get_the_title($postID),
            'posted_data'   => $postedData
        );

        return $data;
    }
}