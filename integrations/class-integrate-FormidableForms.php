<?php
class SR_Partner_Integrate_FormidableForms extends SR_Partner_Core {
    
    /**
     * Register hooks for Formidable Forms
     * of the plugin.
     *
     * @since       1.0.0
     * @access      public
     */
    public function register_hooks()
    {    
        // https://formidablepro.com/knowledgebase/frm_after_create_entry/    
        add_action('frm_after_create_entry', array($this, 'catch_form_data'), 30, 2);
    }

    /**
     * Catch the form data from Formidable Forms
     * @since       1.0.0
     * @access      public
     * @param       int         $entry_id
     * @param       int         $form_id
     * @return      boolean
     */
    public function catch_form_data($entry_id, $form_id)
    {

        $this->_helper->log('form_id'.$form_id);
         global $wpdb;

        // Get form title
        $sql = "SELECT name FROM {$wpdb->prefix}frm_forms WHERE id = %d";
        $sql = $wpdb->prepare($sql, $form_id);
        $title = $wpdb->get_var($sql);

        if (!$title) {
            return true;
        }

        // Get submission values
        $sql = "SELECT f.name AS 'key', m.meta_value AS 'value' FROM {$wpdb->prefix}frm_item_metas m, {$wpdb->prefix}frm_fields f WHERE m.field_id = f.id AND m.item_id = %d";
        $sql = $wpdb->prepare($sql, $entry_id);
        $results = $wpdb->get_results($sql, ARRAY_A);
        if (!$results) {
            return true;
        }

        $postedData = array();
        foreach ($results as $result) {
            $key = $result['key'];
            $value = $result['value'];
            if (is_serialized($value)) {
                $value = unserialize($value);
                if (is_array($value)) {
                    $value = implode(',', $value);
                } else {
                    $value = (string)$value; // shouldn't get here
                }
            }
            $postedData[$key] = $value;
        }

        // Save submission
        $data = array(
            'title'         => $title,
            'posted_data'   => $postedData,
        ); 

        return $this->_helper->catch_lead($data);
    }

}