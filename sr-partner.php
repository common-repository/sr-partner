<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://profiles.wordpress.org/itamarg
 * @since             1.0.0
 * @package           SR_Partner
 *
 * @wordpress-plugin
 * Plugin Name:       SEOReseller Partner Plugin
 * Plugin URI:        https://www.seoreseller.com/
 * Description:       SEOReseller's suite of tools for building, managing, and growing your digital marketing agency.
 * Version:           1.3.15
 * Author:            SEOReseller Team
 * Author URI:        https://www.seoreseller.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sr-partner
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Lets define constants
 *
 * @since    1.0.0
 * @access   public
 * @var      global
 */

defined('SR_PARTNER_MIN_PHP_VERSION_REQUIRED') || define('SR_PARTNER_MIN_PHP_VERSION_REQUIRED', '5.2.4');

defined('SR_PARTNER_PLUGIN_NAME') || define('SR_PARTNER_PLUGIN_NAME', 'sr-partner');

defined('SR_PARTNER_VERSION') || define('SR_PARTNER_VERSION', '1.3.12');
defined('SR_PARTNER_DEBUG')   || define('SR_PARTNER_DEBUG', false);

defined('SR_PARTNER_GENERATE_URL')  || define('SR_PARTNER_GENERATE_URL', 'https://www.accesshub.co/web-audit-generator');
defined('SR_PARTNER_DASHBOARD_URL') || define('SR_PARTNER_DASHBOARD_URL', 'https://www.accesshub.co/');
defined('SR_PARTNER_PORTFOLIO_URL') || define('SR_PARTNER_PORTFOLIO_URL', 'https://view.accesshub.co/');
defined('SR_PARTNER_API_ACCESSHUB') || define('SR_PARTNER_API_ACCESSHUB', 'https://api.accesshub.co/');

defined('SR_PARTNER_ACCOUNT_URL')    || define('SR_PARTNER_ACCOUNT_URL', 'https://account.seoreseller.com');
defined('SR_PARTNER_HELPCENTER_URL') || define('SR_PARTNER_HELPCENTER_URL', 'http://helpcenter.seoreseller.com');

defined('SR_PARTNER_GEN_KEY')      || define('SR_PARTNER_GEN_KEY', 'sr_partner_api_key');
defined('SR_PARTNER_ADMIN_NOTICE') || define('SR_PARTNER_ADMIN_NOTICE', 'sr_partner_admin_notice');

defined('SR_PARTNER_INSTALL_TO_ALL_PAGES') || define('SR_PARTNER_INSTALL_TO_ALL_PAGES', 'sr_partner_install_to_all_pages');
defined('SR_PARTNER_INSTALLED_TO_ALL')     || define('SR_PARTNER_INSTALLED_TO_ALL', 'sr_partner_installed_to_all');

defined('SR_PARTNER_DASHBOARD_PAGE_TITLE') || define('SR_PARTNER_DASHBOARD_PAGE_TITLE', 'Dashboard');
defined('SR_PARTNER_DASHBOARD_PAGE_INFO')  || define('SR_PARTNER_DASHBOARD_PAGE_INFO', 'sr_partner_dashboard_page_info');

defined('SR_PARTNER_PORTFOLIO_PAGE_TITLE') || define('SR_PARTNER_PORTFOLIO_PAGE_TITLE', 'Web Portfolio');
defined('SR_PARTNER_PORTFOLIO_PAGE_INFO')  || define('SR_PARTNER_PORTFOLIO_PAGE_INFO', 'sr_partner_portfolio_page_info');

defined('SR_PARTNER_DASHBOARD_STATUS') || define('SR_PARTNER_DASHBOARD_STATUS', 'sr_partner_dashboard_status');
defined('SR_PARTNER_PORTFOLIO_STATUS') || define('SR_PARTNER_PORTFOLIO_STATUS', 'sr_partner_portfolio_status');
defined('SR_PARTNER_PORTFOLIO_OPTION') || define('SR_PARTNER_PORTFOLIO_OPTION', 'sr_partner_portfolio_option');
defined('PARTNER_PLUGIN_STATUS')       || define('PARTNER_PLUGIN_STATUS', 'sr_partner_audit_status');
defined('SR_PARTNER_LEAD_STATUS')      || define('SR_PARTNER_LEAD_STATUS', 'sr_partner_lead_status');

defined('SR_PARTNER_ALERT_MESSAGES')      || define('SR_PARTNER_ALERT_MESSAGES', 'sr_partner_alert_messages');
defined('SR_PARTNER_API_REQUEST_TIMEOUT') || define('SR_PARTNER_API_REQUEST_TIMEOUT', 30);

defined('SR_PARTNER_REDIRECT_URL') || define('SR_PARTNER_REDIRECT_URL', 'https://www.google.com');

/**
 * echo error message indicating the old plugin is installed and need to be removed first
 */
function sr_partner_old_plugin_installed() {
    echo '<div class="notice notice-error" style="margin-left:2px;"><p>' .
        __('Error: The old version of SEOReseller Partner Plugin is still in your plugin directory. Please remove it first and try again.',  'sr-partner').'</p></div>';
    sr_partner_deactivate_plugin_notice();
}

/**
 * echo error message indicating the minimum PHP version required for this plugin
 */
function sr_partner_version_wrong() {
    echo '<div class="notice notice-error" style="margin-left:2px;"><p>' .
        __('Error: plugin SEOReseller Partner Plugin requires a newer version of PHP to be running.',  'sr-partner').
        '<br/>' . __('Minimal version of PHP required: ', 'sr-partner') . '<strong>' . SR_PARTNER_MIN_PHP_VERSION_REQUIRED . '</strong>' .
        '<br/>' . __('Your server\'s PHP version: ', 'sr-partner') . '<strong>' . phpversion() . '</strong>' .
        '</p></div>';
    sr_partner_deactivate_plugin_notice();
}

/**
 * diables activate the plugin since the plugin requirement is not required.
 */
function sr_partner_deactivate_plugin_notice() {
    if ( current_user_can('activate_plugins') && is_plugin_active(plugin_basename(__FILE__)) ) {
        deactivate_plugins(plugin_basename(__FILE__));
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }
}
/**
 * Checks if the old version of the plugin is installed
 * @param  bolean $add_action.
 * @return boolean true if version check passed. If false, triggers an error which WP will handle, by displaying
 * an error message on the Admin page
 */
function sr_partner_old_plugin_check($add_action =  true) {

    if (file_exists( plugin_dir_path( __FILE__ ) . '../seo-reseller-partner/seo-reseller-partner.php' ) ||
        file_exists( plugin_dir_path( __FILE__ ) . '../partner-plugin/partner-plugin.php' )
    ) {
        if ($add_action) {
            add_action('admin_notices', 'sr_partner_old_plugin_installed');
        }
        return false;
    }
    return true;
}


/**
 * Check the PHP version and give a useful error message if the user's version is less than the required version
 * @param  bolean $add_action.
 * @return boolean true if version check passed. If false, triggers an error which WP will handle, by displaying
 * an error message on the Admin page
 */
function sr_partner_version_check($add_action =  true) {
    if (version_compare(phpversion(), SR_PARTNER_MIN_PHP_VERSION_REQUIRED) < 0) {
        if ($add_action) {
            add_action('admin_notices', 'sr_partner_version_wrong');
        }
        return false;
    }
    return true;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sr-partner-activator.php
 */
function activate_sr_partner() {
    require_once plugin_dir_path( __FILE__ ) . 'admin/class-sr-partner-admin.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-sr-partner-activator.php';

    $activate = new SR_Partner_Activator();
    $activate->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sr-partner-deactivator.php
 */
function deactivate_sr_partner() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-sr-partner-deactivator.php';

    $deactivate = new SR_Partner_Deactivator();
    $deactivate->deactivate();
}

/**
 * The code that runs during plugin uninstallation.
 * This action is documented in includes/class-sr-partner-uninstall.php
 */
function uninstall_sr_partner() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-sr-partner-uninstall.php';

    $uninstall = new SR_Partner_Uninstall();
    $uninstall->uninstall();
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sr_partner() {

    $plugin = new SR_Partner(plugin_basename( __FILE__ ));
    $plugin->run();

}

/**
 * now lets check the php version and culr before running the plugin
 *
 */
if ( sr_partner_old_plugin_check() === false || sr_partner_version_check() === false) {
    add_action( 'admin_init', 'sr_partner_deactivate_plugin_notice');
} else {
    /**
     *Load the base core class that includes db and global helper classes
     **/
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-sr-partner-core.php';

    /**
     * The core plugin class that is used to define internationalization, admin-specific hooks, and public-facing site hooks.
     **/
    require plugin_dir_path( __FILE__ ) . 'includes/class-sr-partner.php';

    register_activation_hook( __FILE__, 'activate_sr_partner' );
    register_deactivation_hook( __FILE__, 'deactivate_sr_partner' );
    register_uninstall_hook(__FILE__, 'uninstall_sr_partner' );

    run_sr_partner();
}