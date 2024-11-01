<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    SR_Partner
 * @subpackage SR_Partner/admin
 */

class SR_Partner_Admin  extends SR_Partner_Core {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;


    /**
     * The base filename of the plugin
     *
     * @since    1.1.3
     * @access   protected
     * @var      string    $base_filename   The base filename of the plugin
     */
    private $base_filename;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version, $base_filename )
    {
        parent::__construct();
        $this->plugin_name      = $plugin_name;
        $this->version          = $version;
        $this->base_filename    = $base_filename;
    }

    /**
     * Loads the admin page content
     *
     * @since    1.0.0
     */
    public function load_admin_page_content()
    {
        require_once plugin_dir_path( __FILE__ ). 'partials/main.php';
    }

    /**
     * Register menu for the admin area.
     *
     * @since    1.0.0
     */
    public function add_admin_menu()
    {
        $notice = get_option(SR_PARTNER_ADMIN_NOTICE);

        if($notice != 'already_installed') {
            global $_wp_last_object_menu;
            $_wp_last_object_menu++;

            /*
            * Hook to add new menu on admin page
            */
            add_menu_page(
                'SEOReseller Partner', //page title
                'SEOReseller', //menu title
                'manage_options', //capability
                $this->plugin_name, //menu slug,
                array( $this, 'load_admin_page_content' ), //function to output the plugin page
                $this->get_menu_icon(),
                $_wp_last_object_menu
            );
        }


    }

    /**
     * Get the menu icon of the plugin.
     *
     * @since    1.0.0
     */
    public function get_menu_icon()
    {
        return 'dashicons-admin-site';
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style( $this->plugin_name.'-main', plugin_dir_url( __FILE__ ) . 'css/sr-partner-main.css', array(), $this->version, 'all' );

        wp_enqueue_style( $this->plugin_name.'-colorpicker', plugin_dir_url( __FILE__ ) . 'css/sr-partner-colorpicker.css', array(), $this->version, 'all' );

        wp_enqueue_style( $this->plugin_name.'-dops', plugin_dir_url( __FILE__ ) . 'css/sr-partner-dops.css', array(), $this->version, 'all' );

        wp_enqueue_style( $this->plugin_name.'-forms', plugin_dir_url( __FILE__ ) . 'css/sr-partner-forms.css', array(), $this->version, 'all' );

        wp_enqueue_style( $this->plugin_name.'-portfolio', plugin_dir_url( __FILE__ ) . 'css/sr-partner-portfolio.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script( $this->plugin_name . '-colorpicker',
            plugin_dir_url( __FILE__ ) . 'js/sr-partner-colorpicker.js', array( 'jquery' ), $this->version, false );

        wp_enqueue_script( $this->plugin_name . '-scripts',
            plugin_dir_url( __FILE__ ) . 'js/sr-partner-scripts.js', array( 'jquery' ), $this->version, false );


        wp_enqueue_script( $this->plugin_name . '-scripts-portfolio-1',
            plugin_dir_url( __FILE__ ) . 'js/sr-partner-admin-portfolio.js', array( 'jquery' ), $this->version, false );
    }

    /**
     * Admin initialization. This for checking the POST submission.
     *
     * @since    1.0.0
     */
    public function admin_init()
    {
        if (isset($_POST['sr-partner-form-generate-code-portfolio'])) {
            $this->generatePortfolioShortCode();
        } else if (isset($_POST['sr-partner-form-generate-submit'])) {
            $this->generate_short_code('add', false);
            $this->update_audit_status();
            $this->_helper->tracker('post');

        } else if (isset($_POST['sr-partner-form-update-submit'])) {

            $this->generate_short_code('update', false);

        } else if (isset($_POST['sr-partner-form-install-to-all-submit'])) {

            $this->generate_short_code('add', true);
            $this->update_audit_status();
            $this->_helper->tracker('post');

        } else if (isset($_POST['sr-partner-form-dashboard-submit'])) {

            $this->update_dashboard();
            $this->update_porfolio();
            $this->update_audit_status();
            $this->update_lead_status();
            $this->_helper->tracker('post');

        } else if (isset($_POST['sr-partner-form-check-updates-submit'])) {

            $this->check_updates();

        } else if (isset($_GET['delete']) && is_numeric($_GET['delete']) ) {

            $id =$_GET['delete'];
            $r = $this->_db->delete_shortcode($id);

            if (get_option(SR_PARTNER_INSTALL_TO_ALL_PAGES) ==$id ) {
                delete_option(SR_PARTNER_INSTALL_TO_ALL_PAGES);
            }

            $this->_helper->set_msg(__("Successfully Deleted!","sr-partner"), true);
            wp_redirect(admin_url('admin.php?page=sr-partner#setup-audit-widget'));
        } else if (isset($_POST['sr-partner-form-activate-plugin-submit'])) {
            $this->activate_plugin();
        }
    }

    /**
     * Checks if there is any admin notices that needs to be outputed.
     *
     * @since    1.0.0
     */
    public function admin_notices()
    {
        $notice = get_option(SR_PARTNER_ADMIN_NOTICE);
        if ($notice == 'already_installed') {

            $html = '<div class="notice notice-error" style="margin-left:2px;">';
            $html .=  '<p><b>'. __("Error!","sr-partner") .'</b> '. __("SEOReseller Partner Plugin has already been installed on another website. You can only install this plugin on one website.","sr-partner") .'</p></div>';

            echo $html;

            $this->deactivate_plugin();

        } else if ($notice == 'token_not_valid') {

            $html = '<div class="notice notice-error" style="margin-left:2px;">';
            $html .=  '<p><b>'. __("Error!","sr-partner") .'</b> '. __("Your SEOReseller token is not valid.","sr-partner") .'</p></div>';

            echo $html;

            $this->deactivate_plugin();

        } else if ($notice == 'unknown_error') {

            $html = '<div class="notice notice-error" style="margin-left:2px;">';
            $html .=  '<p><b>'. __("Error!","sr-partner") .'</b> '. __("We have encountered an issue while activating SEOReseller Partner Plugin. Please contact SEOReseller for support.","sr-partner") .'</p></div>';

            echo $html;

            $this->deactivate_plugin();

        }
    }


    /** private functions **/
    /** private functions **/

    /**
     * Deactivates the plugin
     *
     * @since       1.1.1
     */
    private function deactivate_plugin()
    {
        if (current_user_can('activate_plugins') &&  is_plugin_active($this->base_filename)) {
            deactivate_plugins($this->base_filename);
            if ( isset( $_GET['activate'] ) ) {
                unset( $_GET['activate'] );
            }
            echo '<style type="text/css">.toplevel_page_'.SR_PARTNER_PLUGIN_NAME.'{ display:none }</style>';
        }
    }

    /**
     * Get POST value
     *
     * @since       1.0.0
     * @param       String      $key - post key
     * @param       String      $default - default value of the post data.
     * @return      String
     */
    private function get_post_value($key, $default = "")
    {
        if ($_POST && isset($_POST[$key])) {
            return stripslashes($_POST[$key]);
        }
        return $default;
    }

    /**
     * Get POST value from edit function.
     *
     * @since    1.0.0
     * @param       String      $key - post key
     * @param       String      $index - indext in post data
     * @param       String      $default  - default value of the post data.
     * @return      String      $data[$index] or $default.
     */
    private function get_post_value_from_data($data, $index, $default)
    {
        return isset($data[$index]) ? stripslashes($data[$index]) : $default;
    }

    /**
     * Get POST value from edit function.
     *
     * @since       1.0.0
     * @param       boolean     $all - if want to include all of the list or not.
     * @return      Array       $types - array of form types.
     */
    private function get_form_types($all = true)
    {
        $types = array();
        if ($all) {
            $types['large']         = __("Large","sr-partner");
            $types['small']         = __("Small","sr-partner");
            $types['slim']          = __("Slim","sr-partner");
            $types['calltoaction']  = __("Call to Action","sr-partner");
        }

        $types['notification']  = __("Notification Bar","sr-partner");
        $types['takeover']      = __("Page Takeover","sr-partner");

        return $types;
    }

    /**
     * Get Takeover Types
     *
     * @since       1.0.0
     * @param       boolean     $select - if want to include "select the type" in the array
     * @return      Array       $types - array of takeover types.
     */
    private function get_takeover_types($select = false)
    {
        $types = array();
        if ($select) {
            $types[''] = __("Select the type of takeover","sr-partner");
        }
        $types['delay']   = __("Time Delay","sr-partner");
        $types['scroll']  = __("On Scroll","sr-partner");

        return $types;
    }

    /**
     * Short code generation
     *
     * @since       1.0.0
     * @param       String      $type - checks either its an add or update function
     * @param       boolean     $install_to_all - checks either this is an intall to all pages or not option
     * @return      String      null
     */
    private function generate_short_code($type='add', $install_to_all)
    {
        $api_key = $this->_helper->get_key();

        if(empty($api_key)) {
            $this->_helper->set_msg(__("You do not have a valid token.","sr-partner"), false);
            return;
        }

        $form_type          = sanitize_text_field($_POST['sr_partner_type']);
        $token              = preg_replace('/\d+\//i', '', $api_key);
        $form_parameters    = $this->get_type_parameters($form_type);
        $params             = $form_parameters;

        foreach ($params as $key => $value) {
            if (!$this->is_not_empty($_POST['sr_partner_' . $key])) {
                $this->_helper->set_msg($value . __(" is required!","sr-partner"), false);
                return;
            }
            $params[$key] = sanitize_text_field($_POST['sr_partner_' . $key]);
        }

        $params['token']                = $api_key;
        $params['check_install_status'] = 0;
        $api_params['url']          = SR_PARTNER_GENERATE_URL.'/verify-token';
        $api_params['post_string']  = $params;

        $res            = $this->_helper->call_api($api_params);

        $response   = json_decode($res, true);

        if (isset($response['statusCode'])) {
            if ($response['statusCode'] == 200) {
                if (isset($response['response']['data']) && $response['response']['data'] == 'valid token') {

                    //valid token lets generate the embed code here
                    //valid token lets generate the embed code here
                    $attrib_data = '';

                    foreach ($form_parameters as $key => $value) {
                        $attrib_data .= 'data-'.$key.'="'.sanitize_text_field($_POST['sr_partner_' . $key]).'" ';
                    }

                    $unique_code = md5(time().rand());
                    $embedded_code = '
                        <!--Begin WebAuditGen-->
                        <div id="sr-partner-code-'.$unique_code.'" '.$attrib_data.' class="sr-partner-audit-widget" ></div>
                        <!--End WebAuditGen-->
                    ';
                    //valid token lets generate the embed code here
                    //valid token lets generate the embed code here

                    ///saving  of embed code
                    ///saving  of embed code
                    ///saving  of embed code
                    $data = array();
                    $data['form_name']  = $params['form_name'];
                    $data['form_type']  = $params['type'];
                    $data['attributes'] = serialize($params);
                    $data['embed_code'] = html_entity_decode($embedded_code);

                    if ($type == 'add') {
                        $id = $this->_db->save_shortcode($data);
                        $msg = __("Added Successfully!","sr-partner");
                    } else if ($type == 'update') {
                        $data['id'] = $_GET['edit'];
                        $id         = $this->_db->update_shortcode($data);
                        $id         = $_GET['edit'];
                        $msg        = __("Updated Successfully!","sr-partner");
                    }

                    if (isset($_POST['sr_partner_install_to_all_pages']) || $install_to_all) {
                        update_option(SR_PARTNER_INSTALL_TO_ALL_PAGES, $id, 'yes');
                        if ($install_to_all) {
                            update_option(SR_PARTNER_INSTALLED_TO_ALL, $id, '', 'yes');
                        }

                    } else if (!isset($_POST['sr_partner_install_to_all_pages']) ) {
                        $install_all_status = get_option(SR_PARTNER_INSTALL_TO_ALL_PAGES);
                        if ($install_all_status==$id ) {
                            delete_option(SR_PARTNER_INSTALL_TO_ALL_PAGES);
                        }
                    }

                    if ($id > 0) {
                        $this->_helper->set_msg($msg, true);
                    } else {
                        $this->_helper->set_msg(__("Something wrong!","sr-partner"), false);
                    }
                    ///saving  of embed code
                    ///saving  of embed code

                } else{
                    $this->_helper->set_msg(__("You do not have a valid token.","sr-partner"), false);
                }
            } else if ($response['statusCode'] == 400) {
                $message = isset($response['response']['error']) ?  $response['response']['error'] : 'Unknown error occured.';
                $this->_helper->set_msg(__($message,"sr-partner"), false);
            }
        } else {
            $this->_helper->set_msg(__("Unknown error occured.","sr-partner"), false);
        }
    }


    /**
     *
     * Generate short code for portfolio
     * @param string $type
     *
     */
    private function generatePortfolioShortCode()
    {
        $message = '';
        $id      = 0;

        try {
            $apiKey = $this->_helper->get_key();
            $post   = $_POST;

            if(empty($apiKey)) {
                throw new Exception(__("You do not have a valid token.","sr-partner"));
            }

            $res = $this->_helper->call_api(array(
                'url'         => SR_PARTNER_GENERATE_URL . '/verify-token',
                'post_string' => array(
                    'token'                => $apiKey,
                    'check_install_status' => 0
                )
            ));

            $response = json_decode($res, true);
            $defaultVal = $this->portfolioDefaultValue();

            if (isset($response['statusCode']) && $response['statusCode'] == 200) {
                if (isset($response['response']['data']) && $response['response']['data'] == 'valid token') {

                    $portfolioOption = get_option(SR_PARTNER_PORTFOLIO_OPTION);

                    $newValues = array(
                        'sr_partner_web_portfolio_no_cards_per_row'       => (isset($post['sr_partner_web_portfolio_no_cards_per_row'])) ? $post['sr_partner_web_portfolio_no_cards_per_row'] : $defaultVal['sr_partner_web_portfolio_no_cards_per_row'],
                        'sr_partner_web_portfolio_mockup_title_color'     => (isset($post['sr_partner_web_portfolio_mockup_title_color'])) ? $post['sr_partner_web_portfolio_mockup_title_color'] : $defaultVal['sr_partner_web_portfolio_mockup_title_color'],
                        'sr_partner_web_portfolio_button_primary_color'   => (isset($post['sr_partner_web_portfolio_button_primary_color'])) ? $post['sr_partner_web_portfolio_button_primary_color'] : $defaultVal['sr_partner_web_portfolio_button_primary_color'],
                        'sr_partner_web_portfolio_button_secondary_color' => (isset($post['sr_partner_web_portfolio_button_secondary_color'])) ? $post['sr_partner_web_portfolio_button_secondary_color'] : $defaultVal['sr_partner_web_portfolio_button_secondary_color'],
                        'sr_partner_web_portfolio_show_mockup_menu'       => (!empty($post['sr_partner_web_portfolio_show_mockup_menu'])) ? 1 : 0,
                    );

                    if (!$portfolioOption) {
                        add_option(SR_PARTNER_PORTFOLIO_OPTION, serialize($newValues));
                        $msg = __("Added Successfully!","sr-partner");
                    } else {
                        update_option(SR_PARTNER_PORTFOLIO_OPTION, serialize($newValues));
                        $msg        = __("Updated Successfully!","sr-partner");
                    }

                    ///saving  of attribute
                    $this->_helper->set_msg($msg, true);
                } else {
                    throw new Exception(__("You do not have a valid token.","sr-partner"));
                }
            }
            elseif (isset($response['statusCode']) && $response['statusCode'] == 400) {
                $message = isset($response['response']['error']) ?  $response['response']['error'] : 'Unknown error occured.';
                throw new Exception(__($message, "sr-partner"));
            } else {
                throw new Exception(__("Unknown error occurred.", "sr-partner"));
            }
        } catch (Exception $e) {
            $this->_helper->set_msg($e->getMessage(), false);
        }

    }
    /*
     * Default values
     */
    public function portfolioDefaultValue()
    {
        return array(
            'sr_partner_web_portfolio_no_cards_per_row'       => 2,
            'sr_partner_web_portfolio_mockup_title_color'     => '#FFF',
            'sr_partner_web_portfolio_button_primary_color'   => '#0A89C7',
            'sr_partner_web_portfolio_button_secondary_color' => '#FFF',
            'sr_partner_web_portfolio_show_mockup_menu'       => 1
        );
    }

    /**
     * Get Form Type Parameters.
     *
     * @since       1.0.0
     * @param       String     $formType - type of form
     * @return      Array      $forms - array of paramets in the requested form.
     */
    private function get_type_parameters($formType)
    {
        $forms = array(
            'large' => array(
                'form_name'                     => __("Form Name","sr-partner"),
                'type'                          => __("Large Form","sr-partner"),
                'language'                      => __("Language","sr-partner"),
                'heading'                       => __("Heading Text","sr-partner"),
                'subheading'                    => __("Sub Heading Text","sr-partner"),

                'form_background_color'         => __("Form Background Color","sr-partner"),
                'form_text_color'               => __("Form Text Color","sr-partner"),
                'button_background_color'       => __("Form Button Background Color","sr-partner"),
                'button_text_color'             => __("Form Button Text Color","sr-partner"),
            ),
            'small' => array(
                'form_name'                     => __("Form Name","sr-partner"),
                'type'                          => __("Small Form","sr-partner"),
                'language'                      => __("Language","sr-partner"),
                'heading'                       => __("Heading Text","sr-partner"),
                'subheading'                    => __("Sub Heading Text","sr-partner"),

                'button_background_color'       => __("Form Button Background Color","sr-partner"),
                'button_text_color'             => __("Form Button Text Color","sr-partner"),
            ),
            'slim' => array(
                'form_name'                     => __("Form Name","sr-partner"),
                'type'                          => __("Slim Form","sr-partner"),
                'language'                      => __("Language","sr-partner"),

                'form_background_color'         => __("Form Background Color","sr-partner"),
                'form_text_color'               => __("Form Text Color","sr-partner"),
                'button_background_color'       => __("Form Button Background Color","sr-partner"),
                'button_text_color'             => __("Form Button Text Color","sr-partner"),
            ),
            'notification' => array(
                'form_name'                     => __("Form Name","sr-partner"),
                'type'                          => __("Notification Form","sr-partner"),
                'language'                      => __("Language","sr-partner"),
                'heading'                       => __("Heading Text","sr-partner"),
                'subheading'                    => __("Sub Heading Text","sr-partner"),

                'form_background_color'         => __("Form Background Color","sr-partner"),
                'form_text_color'               => __("Form Text Color","sr-partner"),
                'button_background_color'       => __("Form Button Background Color","sr-partner"),
                'button_text_color'             => __("Form Button Text Color","sr-partner"),

                'page_background_color'         => __("Page Background Color","sr-partner"),
                'page_background_opacity'       => __("Page Background Opacity","sr-partner"),

                'banner_label'                  => __("Banner Label","sr-partner"),
                'banner_button_label'           => __("Banner Button Text","sr-partner"),
                'banner_background_color'       => __("Banner Background Color","sr-partner"),
                'banner_text_color'             => __("Banner Text Color","sr-partner"),
                'banner_button_background_color'=> __("Banner Button Background Color","sr-partner"),
                'banner_button_color'           => __("Banner Button Color","sr-partner"),
                'banner_reshow_delay'           => __("Banner Reshow Delay","sr-partner"),

            ),
            'takeover' => array(
                'form_name'                     => __("Form Name","sr-partner"),
                'type'                          => __("Notification Form","sr-partner"),
                'language'                      => __("Language","sr-partner"),
                'heading'                       => __("Heading Text","sr-partner"),
                'subheading'                    => __("Sub Heading Text","sr-partner"),

                'form_background_color'         => __("Form Background Color","sr-partner"),
                'form_text_color'               => __("Form Text Color","sr-partner"),
                'button_background_color'       => __("Form Button Background Color","sr-partner"),
                'button_text_color'             => __("Form Button Text Color","sr-partner"),

                'page_background_color'         => __("Page Background Color","sr-partner"),
                'page_background_opacity'       => __("Page Background Opacity","sr-partner"),
                'takeover_type'                 => __("Takeover Type","sr-partner"),
                'takeover_delay'                => __("Takeover Show Delay","sr-partner"),
                'takeover_scroll'               => __("Takeover On Scroll Percentage","sr-partner"),

            ),

            'calltoaction' => array(
                'form_name'                     => __("Form Name","sr-partner"),
                'type'                          => __("Notification Form","sr-partner"),
                'language'                      => __("Language","sr-partner"),
                'heading'                       => __("Heading Text","sr-partner"),
                'subheading'                    => __("Sub Heading Text","sr-partner"),

                'form_background_color'         => __("Form Background Color","sr-partner"),
                'form_text_color'               => __("Form Text Color","sr-partner"),
                'button_background_color'       => __("Form Button Background Color","sr-partner"),
                'button_text_color'             => __("Form Button Text Color","sr-partner"),

                'page_background_color'         => __("Page Background Color","sr-partner"),
                'page_background_opacity'       => __("Page Background Opacity","sr-partner"),

                'page_button_label'             => __("On Page Button Label","sr-partner"),
                'page_button_background_color'  => __("On Page Button Background Color","sr-partner"),
                'page_button_color'             => __("On Page Button Text Color","sr-partner"),
            ),
        );


        return $forms[$formType];
    }

    /**
     * Get Form Type Parameters.
     *
     * @since       1.0.0
     * @param       String     $formType - type of form
     * @return      array      $forms - array of paramets in the requested form.
     */
    private function getPorfolioParameters($formType)
    {
        $forms = array(
            'onpage' => array()
        );

        return $forms[$formType];
    }

    /**
     * Checks string if empty
     *
     * @since    1.0.0
     * @param       String     $string
     * @return      Boolean
     */
    private function is_not_empty($string)
    {
        return (isset($string) && $string !== '');
    }


    /**
     * update the audit status
     *
     * @since    1.0.0
     */
    private function update_audit_status()
    {
        if ($_POST) {
            if (isset($_POST['enable_audit_status']) && $_POST['enable_audit_status'] == 1 ) {
                $status = 1;

                if (get_option(PARTNER_PLUGIN_STATUS) != 1) {
                    $this->_helper->set_msg( sprintf( __("%s activated successfully!","sr-partner"), 'Audit Widget') , true);
                }

            } else {
                $status = 0;

                if (get_option(PARTNER_PLUGIN_STATUS) != 0) {
                    $this->_helper->set_msg( sprintf( __("%s deactivated successfully!","sr-partner"), 'Audit Widget') , 'warning');
                }
            }
            update_option(PARTNER_PLUGIN_STATUS, $status);
        }
    }

    /**
     * for updating widget lead status
     *
     * @since    1.0.0
     */
    private function update_lead_status()
    {
        if ($_POST) {
            if (isset($_POST['enable_lead_status']) && $_POST['enable_lead_status'] == 1 ) {
                $status = 1;
                if (get_option(SR_PARTNER_LEAD_STATUS) != 1) {
                    $this->_helper->set_msg( sprintf( __("%s activated successfully!","sr-partner"), 'Lead Tracker') , true);
                }
            } else {
                $status = 0;
                if (get_option(SR_PARTNER_LEAD_STATUS) != 0) {
                    $this->_helper->set_msg( sprintf( __("%s deactivated successfully!","sr-partner"), 'Lead Tracker') , 'warning');
                }
            }
            update_option(SR_PARTNER_LEAD_STATUS, $status);
        }
    }


    /**
     * for updating widget dashboard page
     *
     * @since    1.0.0
     */
    private function update_dashboard()
    {
        if ($_POST) {
            if (!isset($_POST['enable_dashboard_status'])) {

                if (get_option(SR_PARTNER_DASHBOARD_STATUS) == 1) {
                    $this->_helper->set_msg( sprintf( __("%s deactivated successfully!","sr-partner"), 'White Label Dashboard') , 'warning');
                }

                update_option(SR_PARTNER_DASHBOARD_STATUS, 0);

                //delete
                //delete
                if (get_permalink($_POST['dashboard_page_id']) !==false) {
                    $res = wp_delete_post($_POST['dashboard_page_id'], true );

                    $page_info = array(
                        'id'        => '',
                        'title'     => '',
                        'permalink' => ''
                    );

                    // update page status
                    update_option(SR_PARTNER_DASHBOARD_PAGE_INFO, serialize($page_info));
                }
                //delete
                //delete
            }

            if (isset($_POST['enable_dashboard_status'])) {

                if (get_option(SR_PARTNER_DASHBOARD_STATUS) != 1) {
                    $this->_helper->set_msg( sprintf( __("%s activated successfully!","sr-partner"), 'White Label Dashboard') , true);
                }

                update_option(SR_PARTNER_DASHBOARD_STATUS, $_POST['enable_dashboard_status']);

                if ($_POST['enable_dashboard_status'] == 1) {

                    if (isset($_POST['dashboard-page']) && !empty($_POST['dashboard-page'])) {

                        if ( isset($_POST['dashboard_page_id']) && !empty($_POST['dashboard_page_id']) && get_permalink($_POST['dashboard_page_id']) != false ) {

                            $dashboard_page_info = unserialize(get_option(SR_PARTNER_DASHBOARD_PAGE_INFO));
                            if (isset($dashboard_page_info['title']) && $dashboard_page_info['title'] != $_POST['dashboard-page']) {
                                $this->_helper->set_msg( sprintf( __("%s Page Title updated successfully!","sr-partner"), 'White Label Dashboard') , true);
                            }

                            $page = $this->update_page($_POST['dashboard_page_id'], $_POST['dashboard-page'], SR_PARTNER_DASHBOARD_PAGE_INFO);
                            if ($page) {

                                if (isset($page['status']) && $page['status'] == 'failed') {

                                    foreach ($page['errors'] as $msg) {
                                        $this->_helper->set_msg($msg, false);
                                    }
                                }
                            }

                        } else {
                            // seems that dashboard page is not yet created, lets create one
                            $params['title']        = $_POST['dashboard-page'];
                            $params['page_title']   = SR_PARTNER_DASHBOARD_PAGE_TITLE;
                            $params['page_info']    = SR_PARTNER_DASHBOARD_PAGE_INFO;

                            $page = $this->_helper->create_page($params);
                        }

                    } else {
                        $this->_helper->set_msg(SR_PARTNER_DASHBOARD_PAGE_TITLE. __(" Page Title is required.","sr-partner"), false);
                    }
                }//if ($_POST['enable_dashboard_status'] == 1)

            }//if (isset($_POST['enable_dashboard_status']))
        } //if post

    }

    /**
     * for updating widget portfolio page
     *
     * @since    1.0.0
     */
    private function update_porfolio()
    {
        // SAVING PORTFOLOP PAGE
        if ($post = $_POST) {
            //$this->_helper->set_msg('Successfully saved!', true);
            if (!isset($post['enable_portfolio_status'])) {

                if (get_option(SR_PARTNER_PORTFOLIO_STATUS) == 1) {
                    $this->_helper->set_msg( sprintf( __("%s deactivated successfully!","sr-partner"), 'Web Design Portfolio') , 'warning');
                }

                update_option(SR_PARTNER_PORTFOLIO_STATUS, 0);

                //delete
                //delete
                if (get_permalink($post['portfolio_page_id']) !==false) {
                    $res = wp_delete_post($post['portfolio_page_id'], true );

                    $page_info = array(
                        'id'        => '',
                        'title'     => '',
                        'permalink' => ''
                    );

                    // update page status
                    update_option(SR_PARTNER_PORTFOLIO_PAGE_INFO, serialize($page_info));
                }
                //delete
                //delete
            }

            if (isset($post['enable_portfolio_status'])) {

                if (get_option(SR_PARTNER_PORTFOLIO_STATUS) != 1) {
                    $this->_helper->set_msg( sprintf( __("%s activated successfully!","sr-partner"), 'Web Design Portfolio') , true);
                }

                update_option(SR_PARTNER_PORTFOLIO_STATUS, $post['enable_portfolio_status']);

                if ($post['enable_portfolio_status'] == 1){

                    if (isset($post['portfolio-page']) && !empty($post['portfolio-page'])) {

                        // check if dashboard page id is already set so we can just update its page name
                        if ( isset($post['portfolio_page_id']) && !empty($post['portfolio_page_id']) && get_permalink($post['portfolio_page_id']) != false ) {

                            $portfolio_page_info = unserialize(get_option(SR_PARTNER_PORTFOLIO_PAGE_INFO));
                            if (isset($portfolio_page_info['title']) && $portfolio_page_info['title'] != $_POST['portfolio-page']) {
                                $this->_helper->set_msg( sprintf( __("%s Page Title updated successfully!","sr-partner"), 'Web Design Portfolio') , true);
                            }

                            $page = $this->update_page($post['portfolio_page_id'], $post['portfolio-page'], SR_PARTNER_PORTFOLIO_PAGE_INFO);
                            if ($page) {

                                if (isset($page['status']) && $page['status'] == 'failed'){

                                    foreach ($page['errors'] as $msg) {
                                        $this->_helper->set_msg($msg, false);
                                    }

                                }
                            }

                        } else {
                            // seems that portfolio page is not yet created, lets create one
                            $params['title']        = $post['portfolio-page'];
                            $params['page_title']   = SR_PARTNER_PORTFOLIO_PAGE_TITLE;
                            $params['page_info']    = SR_PARTNER_PORTFOLIO_PAGE_INFO;

                            $page = $this->_helper->create_page($params);
                        }

                    } else {
                        $this->_helper->set_msg(SR_PARTNER_PORTFOLIO_PAGE_TITLE. __(" Page Title is required.","sr-partner"), false);
                    }

                }

            }
        }
    }

    /**
     * for updating widget dashboard page
     *
     * @since       1.0.0
     * @param       Int         $pageId - page ID
     * @param       String      $newPageName - new page name
     * @param       String      $page_info_name - default page info name
     * @return      Array       $response - array of response data
     */
    private function update_page($pageId, $newPageName, $page_info_name)
    {
        $page = get_post($pageId);

        $response = array('status' => 'success');

        if (is_null($page) === false) {

            $current_item = array(
                'ID'           => $page->ID,
                'post_title'   => $newPageName,
            );

            wp_update_post( $current_item, true);

            if (is_wp_error($pageId)) {

                $response['status'] = 'failed';
                $errors             = $pageId->get_error_messages();
                foreach ($errors as $error) {
                    $response['errors'][] = $error;
                }
            }
        }

        $page_info = array(
            'id'        => $pageId,
            'title'     => $newPageName,
            'permalink' => get_permalink($pageId)
        );

        // update page status
        update_option($page_info_name, serialize($page_info));
        return $response;
    }

    /**
     * for checking new update of the plugin
     *
     * @since    1.0.0
     */
    private function activate_plugin()
    {
        if (isset($_POST['sr-partner-form-activate-token']) && !empty($_POST['sr-partner-form-activate-token'])) {
            $token = trim($_POST['sr-partner-form-activate-token']);
            $temp  = explode('/', $token);

            if (isset($temp[0]) && is_numeric($temp[0])) {
                if (isset($temp[1]) && !empty($temp[1])) {


                    $params['token']                = $token;
                    $params['check_install_status'] = 1;

                    $api_params['url']          = SR_PARTNER_GENERATE_URL.'/verify-token';
                    $api_params['post_string']  = $params;

                    $res            = $this->_helper->call_api($api_params);

                    $post_response  = json_decode($res, true);
                    if (isset($post_response['statusCode'])) {

                        $status = '';
                        if ($post_response['statusCode'] == 200) {
                            $status = '' ;
                        } else if ($post_response['statusCode'] == 400) {

                            if (isset($post_response['response']['error']) && $post_response['response']['error'] == 'installed') {
                                $status = 'already_installed';
                            } else {
                                $status = isset($post_response['response']['error']) ? $post_response['response']['error'] : '';
                            }
                        }

                        if ($status == 'already_installed') {
                            delete_option(SR_PARTNER_GEN_KEY);
                            $message = __("This token has already been used on another website.","sr-partner");
                            $this->_helper->set_msg($message, false);
                        } elseif (empty($status)) {
                            $message = __("Your plugin has been activated.","sr-partner");
                            $this->_helper->set_msg($message, true);
                            $this->_helper->activate_plugin($token);
                        } else {
                            $this->_helper->set_msg(__($status,"sr-partner"), false);
                        }

                    } else {
                        $this->_helper->set_msg(__("Unknown error occured.","sr-partner"), false);
                    }

                } else {
                    $this->_helper->set_msg(__("You do not have a valid token.","sr-partner"), false);
                }
            } else {
                $this->_helper->set_msg(__("You do not have a valid token.","sr-partner"), false);
            }
        } else {
            $this->_helper->set_msg(__("Please enter a your activation token.","sr-partner"), false);
        }
    }

    /**
     * for checking new update of the plugin
     *
     * @since    1.0.0
     */
    private function check_updates()
    {
        $key = $this->_helper->get_key();
        if (empty($key)) {
            $this->_helper->set_msg(__("You do not have a valid token.","sr-partner"), false);
            return;
        }

        $key = preg_replace('/\d+\//i', '', $key);

        $params['version'] = SR_PARTNER_VERSION;
        $params['token']   = $key;

        $api_params['url']          = SR_PARTNER_GENERATE_URL.'/check-updates';
        $api_params['post_string']  = $params;

        $res            = $this->_helper->call_api($api_params);
        $post_response  = json_decode($res, true);

        if(isset($post_response['statusCode'])) {
            if($post_response['statusCode'] == 200) {
                $message = $post_response['response']['data'];

            } else if($post_response['statusCode'] == 400) {

                $message = $post_response['response']['error'];
            }
            $_SESSION['sr_partner_update_message'] = $message;
        } else {
            $this->_helper->set_msg(__("Unknown error occured.","sr-partner"), false);
        }

    }

}
