<?php
/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    SR_Partner
 * @subpackage SR_Partner/includes 
 */
class SR_Partner_Deactivator extends SR_Partner_Core {

    /**
     * deactivates the plugin
     *
     * @since       1.0.0
     */
    public function deactivate()
    {
        $dashboard_info     = get_option(SR_PARTNER_DASHBOARD_PAGE_INFO);
        $portfolio_info     = get_option(SR_PARTNER_PORTFOLIO_PAGE_INFO);

        if ($dashboard_info !== false) {
            $dashboard_info = unserialize($dashboard_info);

            if (isset($dashboard_info['id']) && !empty($dashboard_info['id']) && is_numeric($dashboard_info['id'])) {
                $res = wp_delete_post($dashboard_info['id'], true );
            }
        }

        if ($portfolio_info !== false) {
            $portfolio_info = unserialize($portfolio_info);
            if (isset($portfolio_info['id']) && !empty($portfolio_info['id']) && is_numeric($portfolio_info['id'])) {
                $res = wp_delete_post($portfolio_info['id'], true );
            }
        }

        if (get_option(SR_PARTNER_GEN_KEY) !== false ) {
            $this->_helper->tracker('uninstall');
        }

        delete_option(SR_PARTNER_ADMIN_NOTICE);
        delete_option(SR_PARTNER_DASHBOARD_PAGE_INFO);
        delete_option(SR_PARTNER_PORTFOLIO_PAGE_INFO);
        delete_option(SR_PARTNER_DASHBOARD_STATUS);
        delete_option(SR_PARTNER_PORTFOLIO_STATUS);
        delete_option(PARTNER_PLUGIN_STATUS);
        delete_option(SR_PARTNER_LEAD_STATUS);

        delete_option(SR_PARTNER_ALERT_MESSAGES);

        delete_option(SR_PARTNER_GEN_KEY);
    }
}
