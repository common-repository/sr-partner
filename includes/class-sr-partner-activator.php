<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    SR_Partner
 * @subpackage SR_Partner/includes 
 */
class SR_Partner_Activator extends SR_Partner_Core {

    /**
     * Widget Activvation functions
     *
     * @since    1.0.0
     */
    public function activate()
    {
        delete_option(SR_PARTNER_GEN_KEY);
        $key = $this->_helper->get_key();

        if (!empty($key)) {

            $params = array();
            $params['token']                = $key;
            $params['check_install_status'] = 1;

            $api_params['url']          = SR_PARTNER_GENERATE_URL.'/verify-token';
            $api_params['post_string']  = $params;

            $res            = $this->_helper->call_api($api_params);
            $post_response  = json_decode($res, true);

            //check if not installed already
            $status = 'unknown_error';
            if (isset($post_response['statusCode'])) {
                $status = 'already_installed';
                if ($post_response['statusCode'] == 200) {
                   $status = '' ;
                } else if ($post_response['statusCode'] == 400 && isset($post_response['response']['error']) && $post_response['response']['error'] == 'Token is not valid.') {
                        $status = 'token_not_valid';
                }
            }

            if (!empty($status)) {
                delete_option(SR_PARTNER_GEN_KEY);
                update_option(SR_PARTNER_ADMIN_NOTICE, $status);
            } else {
                delete_option(SR_PARTNER_ADMIN_NOTICE);
                $this->_helper->activate_plugin($key);
            }

        }

    }
}
