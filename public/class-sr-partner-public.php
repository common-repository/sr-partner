<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    SR_Partner
 * @subpackage SR_Partner/public
 */
class SR_Partner_Public extends SR_Partner_Core {

    const API_ACCESSHUB_ENDPOINT_MOCKUP_CATEGORY       = 'property-category/has-mockup';
    const API_ACCESSHUB_ENDPOINT_MOCKUP_LIST           = 'mockup/details';
    const FILE_OBJECT_TYPE_MOCKUP                      = 'mockup_template';

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
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        parent::__construct();
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->add_shortcode();
    }


    /**
     * WebAudit hook checker to all pages
     *
     * @since    1.0.0
     */
    public function hook_to_all_pages()
    {
        $audit_is_active = get_option(PARTNER_PLUGIN_STATUS);
        $audit_is_active = $audit_is_active !== false ? true : false;

        if ($audit_is_active) {
            $id = get_option(SR_PARTNER_INSTALL_TO_ALL_PAGES);
            if (is_numeric($id)) {
                echo $this->_db->get_shortcode($id)->embed_code;
            }
        }

        //this for localization of audit widget
        //this for localization of audit widget
        $audit_is_active = get_option(PARTNER_PLUGIN_STATUS);
        $audit_is_active = $audit_is_active !== false ? $audit_is_active : false;

        if ($audit_is_active == 1) {
            $js = '
                <script type="text/javascript">
                var sr_partner_localization = {
                    run_audit           : "'. __("Run Audit","sr-partner") .'",
                    running_audit       : "'. __("Running your web audit.","sr-partner") .'",
                    please_wait         : "'. __("Please wait...","sr-partner") .'",
                    success             : "'. __("Success!","sr-partner") .'",
                    oh_snap             : "'. __("Oh snap!","sr-partner") .'",
                    error_occured       : "'. __("There was a problem processing your request. Please try again.","sr-partner") .'",
                    web_url_required    : "'. __("Website URL is required.","sr-partner") .'",
                    web_url_invalid     : "'. __("Website URL is invalid.","sr-partner") .'",
                    email_required      : "'. __("Email is required.","sr-partner") .'",
                    email_invalid       : "'. __("Email is invalid.","sr-partner") .'",
                };
                </script>
            ';
            echo $js;
        }
        //this for localization of audit widget
        //this for localization of audit widget
    }

    /**
     * Add a shortcode listener to WP
     *
     * @since       1.0.0
     * @param       Array       $params - array of shortcode info
     * @return      String      $string - html value of the shortcode
     */
    public function hook_shortcode($params = array())
    {

        $audit_is_active = get_option(PARTNER_PLUGIN_STATUS);
        $audit_is_active = $audit_is_active !== false ? $audit_is_active : 0;
        if ($audit_is_active) {
            if (isset($params['id'])) {
                $short_code_id = intval($params['id']);
                return $this->_db->get_shortcode($short_code_id)->embed_code;
            }
        }
    }

    /**
     * Add a shortcode listener to WP
     *
     * @since       1.0.0
     * @param       Array       $params - array of shortcode info
     * @return      String      $string - html value of the shortcode
     */
    public function hook_shortcode_portfolio($params = array())
    {
        $portfolioIsActive = get_option(SR_PARTNER_PORTFOLIO_STATUS);
        $portfolioOption   = get_option(SR_PARTNER_PORTFOLIO_OPTION);
        $portfolioIsActive = $portfolioIsActive !== false ? $portfolioIsActive : 0;
        $portfolioOption   = $portfolioOption ? unserialize($portfolioOption) : array();

        if ($portfolioIsActive && !empty($portfolioOption) && is_array($portfolioOption)) {
            $count       = $this->_getAllMockupCount();
            $companyName = '';
            $attributes  = [
                'data-category-count=' . $count,
                'data-company-name="' . $companyName . '"'
            ];

            foreach ($portfolioOption as $key => $option) {
                switch ($key) {
                    case 'sr_partner_web_portfolio_no_cards_per_row': $attributes[] = 'data-cards-per-row=' . $option; break;
                    case 'sr_partner_web_portfolio_mockup_title_color': $attributes[] = 'data-title-color=' . $option; break;
                    case 'sr_partner_web_portfolio_button_primary_color': $attributes[] = 'data-button-primary=' . $option; break;
                    case 'sr_partner_web_portfolio_button_secondary_color': $attributes[] = 'data-button-secondary=' . $option; break;
                    case 'sr_partner_web_portfolio_show_mockup_menu': $attributes[] = 'data-show-mockup-menu=' . $option; break;
                }
            }

            return '
                <!--Begin Portfolio get-->
                    <div id="sr-partner-code-web-design-portfolio" class="sr-partner-portfolio" ' . implode(' ', $attributes) . '></div>
                <!--End Portfolio get-->
            ';
        }
    }

    /**
     * Add a shortcode listener to WP
     *
     * @since    1.0.0
     */
    public function add_shortcode()
    {
        add_shortcode('sr-partner-os-audit', array($this, 'hook_shortcode' ));
        add_shortcode('sr-partner-os-portfolio', array($this, 'hook_shortcode_portfolio' ));
    }

    /**
     * Catches the ajax call from front end using the widget
     *
     * @since    1.0.0
     * @return   outputs the json data of the result
     */
    public function sr_partner_ajax()
    {
        $response = array (
            'statusCode'    => 422,
            'response'      => array (
                'error'     => 'Failed'
            )
        );

        $post         = $_POST;
        $get          = $_GET;
        $params       = $get;
        $methodPrefix = '_get_';

        if (!empty($post)) {
            $params = $post;
            $methodPrefix = '_post_';
        }

        if (isset($params['m'])) {
            $method = $params['m'];

            if (method_exists($this, $methodPrefix . $method)) {
                call_user_func([$this, $methodPrefix . $method], $params);
            }
        } else {
            // default action found
            $this->_webAuditAjaxAction();
        }

        wp_die();
    }

    private function _webAuditAjaxAction()
    {
        if ($_POST && isset($_POST['email']) && isset($_POST['url'])) {

            $email      = sanitize_text_field($_POST['email']);
            $url        = sanitize_text_field($_POST['url']);
            $source_url = sanitize_text_field($_POST['source_url']);
            $ip         = $this->_helper->get_user_ip();
            $has_error  = false;
            $err_msg    = "";

            if (is_email($email)) {

                if (strpos($url,'http://') === false && strpos($url,'https://') === false) {
                    $url = 'http://'.$url;
                }

                //url ok here... do the API Call now
                $key = $this->_helper->get_key();
                if (empty($key)) {
                    $has_error  = true;
                    $err_msg    = __("An error has occured. Please contact the administrator.","sr-partner");
                }

                $key = preg_replace('/\d+\//i', '', $key); // this will remove the number that is prepend from the token

                $params = array(
                    'email'      => strtolower($email),
                    'url'        => strtolower(rtrim($url,'/')),
                    'token'      => $key,
                    'source_url' => $source_url,
                    'ip'         => $ip,
                );

                $api_params = array(
                    'url'         => SR_PARTNER_GENERATE_URL.'/generate',
                    'post_string' => $params,
                );

                $res = $this->_helper->call_api($api_params);

                if (!empty($res)) {
                    $post_response  = json_decode($res, true);
                } else {
                    $post_response = array(
                        'statusCode' => '422',
                        'response'   => array(
                            'error' => __("There was a problem processing your request. Please try again.", "sr-partner")
                        )
                    );
                }

                echo json_encode($post_response);
                //url ok here... do the API Call now end


            } else {
                $has_error  = true;
                $err_msg    = __("Invalid email.","sr-partner");
            }

            if ($has_error) {
                $response['data']['error'] = $err_msg;
                echo json_encode($response);
            }

        } else {
            $response['data']['error'] = __("Invalid POST parameters.","sr-partner");
            echo json_encode($response);
        }
    }

    private function _get_getMockupData($params)
    {
        $categoryId   = (isset($params['categoryId'])) ? $params['categoryId'] : 0;
        $page         = (isset($params['page'])) ? $params['page'] : 1;
        $limit        = (isset($params['limit'])) ? $params['limit'] : 1;
        $token        = $this->_helper->get_key();

        $params = [
            'object'             => self::FILE_OBJECT_TYPE_MOCKUP,
            'is_deleted'         => 0,
            'is_active'          => 1,
            'sql_clause'         => [
                'order_by' => [
                    'is_new'      => 'desc',
                    'mockup_name' => 'asc',
                ]
            ],
            'page'               => $page,
            'pagination_clause'  => [
                'pagination_limit' => $limit,
            ],
            'mockup_publish_date_less_equal' => date('Y-m-d H:i:s')
        ];

        $endPoint = self::API_ACCESSHUB_ENDPOINT_MOCKUP_LIST;

        if (!empty($categoryId)) {
            $params['cat_or_subcat_id'] = $categoryId;
        }

        $mockups = $this->_helper->call_api_get(array(
            'url'         => SR_PARTNER_API_ACCESSHUB . $endPoint,
            'post_string' => $params,
        ));

        $mockups  = json_decode($mockups, true);

        $nextPage = (!empty($mockups['response']['next_page_url'])) ? str_replace('?page=', '', $mockups['response']['next_page_url']) : null;
        $mockups  = (!empty($mockups['response']['data']) && is_array($mockups['response']['data'])) ? $mockups['response']['data'] : array();

        if (!empty($mockups)) {
            $token = explode('/', $token);
            foreach ($mockups as $key => $mockup) {
                if (!isset($mockup['mockupId']) || !isset($token[1])) continue;

                $mockups[$key]['downloadLink'] = sprintf('%s%s/portfolio/download/%s', SR_PARTNER_PORTFOLIO_URL, $token[1], $mockup['mockupId']);

            }
        }

        echo json_encode([
            'nextPage' => $nextPage,
            'mockups'   => $mockups,
        ]);
    }

    /**
     * @desc   : get mockup category list
     * @author : RTM <ryan@truelogic.com.ph>
     * @date   : 2018-12-19
     **/
    private function _get_mockupCategoryList()
    {
        $categoryData = $this->_helper->call_api_get(array(
            'url'         => SR_PARTNER_API_ACCESSHUB . self::API_ACCESSHUB_ENDPOINT_MOCKUP_CATEGORY,
            'post_string' => array(
                'client_id'         => 0,
                'is_deleted'        => 0,
                'site_id'           => 1,
                'sql_clause'        => array(
                    'having' => array(
                        'mockup_count' => array(
                            'operand' => '>',
                            'value'   => 0,
                        ),
                    ),
                ),
                'pagination_clause' => array(
                    'pagination_limit' => 'none',
                )
            )
        ));

        $categoryData = json_decode($categoryData, true);
        $categoryList = (!empty($categoryData['response']['data']) && is_array($categoryData['response']['data'])) ? $categoryData['response']['data']: array();

        array_unshift($categoryList, array(
            'id'           => false,
            'categoryName' => 'All Mockups'
        ));

        echo json_encode($categoryList);
    }


    /**
     * @desc   : get all mockup mockup count
     * @author : RTM <ryan@truelogic.com.ph>
     * @date   : 2018-12-20
     **/
    private function _getAllMockupCount()
    {
        $result = $this->_helper->call_api_get(array(
            'url'         => SR_PARTNER_API_ACCESSHUB . self::API_ACCESSHUB_ENDPOINT_MOCKUP_LIST,
            'post_string' => array(
                'object'     => self::FILE_OBJECT_TYPE_MOCKUP,
                'is_deleted' => 0,
                'is_active'  => 1,
                'sql_clause' => array(
                    'order_by' => array(
                        'is_new'      => 'desc',
                        'mockup_name' => 'asc',
                    )
                ),
                'pagination_clause'  => array(
                    'pagination_limit' => 'none',
                ),
                'mockup_publish_date_less_equal' => date('Y-m-d H:i:s')
            )
        ));
        $result = json_decode($result, true);
        $result = (!empty($result['response']['data']) && is_array($result['response']['data'])) ? $result['response']['data'] : array();

        return (!empty($result) && is_array($result)) ? count($result) : 0;
    }

    /**
     * Hook that checks if the current page is same with SR_PARTNER_DASHBOARD_PAGE_INFO or SR_PARTNER_PORTFOLIO_PAGE_INFO
     *
     * @since    1.0.0
     */
    public function public_init_hook()
    {
        $dashboard_info     = get_option(SR_PARTNER_DASHBOARD_PAGE_INFO);
        $portfolio_info     = get_option(SR_PARTNER_PORTFOLIO_PAGE_INFO);
        $token              = $this->_helper->get_key();
        $token              = !$token ? "" : $token;
        $key                = preg_replace('/\d+\//i', '', $token); // this will remove the number that is prepend from the token

        if ($dashboard_info !== false || $portfolio_info !== false) {

            $current_url= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

            if ($dashboard_info !== false) {
                $dashboard_info = unserialize($dashboard_info);

                if (isset($dashboard_info['permalink']) && !empty($dashboard_info['permalink'])) {
                    $dashboard_permalink = $this->remove_http($dashboard_info['permalink']);

                    if ($dashboard_permalink == $current_url) {
                        $params['page_id']  = $dashboard_info['id'];
                        $params['title']    = $dashboard_info['title'];
                        $params['url']      = SR_PARTNER_DASHBOARD_URL.$token;
                        $this->output_custom_page($params);
                    }
                }

            }

            if ($portfolio_info !== false) {
                $portfolio_info = unserialize($portfolio_info);

                if (isset($portfolio_info['permalink']) && !empty($portfolio_info['permalink'])) {
                    $portfolio_permalink = $this->remove_http($portfolio_info['permalink']);

                    if ($portfolio_permalink == $current_url) {

                        $params['page_id']  = $portfolio_info['id'];
                        $params['title']    = $portfolio_info['title'];
                        $params['url']      = SR_PARTNER_PORTFOLIO_URL.$key.'/portfolio';

                        $this->output_custom_page($params);
                    }
                }
            }
        }
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        $audit_is_active = get_option(PARTNER_PLUGIN_STATUS);
        $audit_is_active = $audit_is_active !== false ? $audit_is_active : false;

        $portfolioIsActive = get_option(SR_PARTNER_PORTFOLIO_STATUS);

        if ($audit_is_active == 1) {
            wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sr-partner-public.css', array(), $this->version, 'all' );
        }

        if (intval($portfolioIsActive) == 1) {
            wp_enqueue_style( $this->plugin_name. '-public-portfolio', plugin_dir_url( __FILE__ ) . 'css/sr-partner-public-portfolio.css', array(), $this->version, 'all' );
        }
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        $audit_is_active = get_option(PARTNER_PLUGIN_STATUS);
        $audit_is_active = $audit_is_active !== false ? $audit_is_active : false;

        $portfolioIsActive = get_option(SR_PARTNER_PORTFOLIO_STATUS);

        if ($audit_is_active == 1) {

            wp_enqueue_script( $this->plugin_name . '-public', plugin_dir_url( __FILE__ ) . 'js/sr-partner-public.js', array( 'jquery' ), $this->version, true );
            wp_localize_script( $this->plugin_name . '-public', 'sr_partner_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
        }

        if (intval($portfolioIsActive) == 1) {
            wp_enqueue_script( $this->plugin_name . '-public-portfolio', plugin_dir_url( __FILE__ ) . 'js/sr-partner-public-portfolio.js', array( 'jquery' ), $this->version, true );
            wp_localize_script( $this->plugin_name . '-public-portfolio', 'sr_partner_ajax_portfolio_object', array( 'ajax_portfolio_url' => admin_url( 'admin-ajax.php' ) ) );
        }

    }

    /*** private functions ****/
    /**
     * Outputs the custom page to browser
     *
     * @since    1.0.0
     * @param       Array       $params - array info need to output
     */
    private function output_custom_page($params)
    {
        $file      = plugin_dir_path( __FILE__ ) . '/crawlers-list.php';
        $isBlocked = false;

        if (file_exists($file)) {
            include_once($file);

            $userAgent = (isset($_SERVER['HTTP_USER_AGENT'])) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';

            if (!empty($userAgent) && isset($config['crawlers']['bots']['blocked']) && is_array($config['crawlers']['bots']['blocked'])) {
                foreach ($config['crawlers']['bots']['blocked'] as $blockedCrawler) {
                    if(strstr($userAgent, strtolower($blockedCrawler)) !== false) {
                        $isBlocked = true;
                        break;
                    }
                }
            }
        }

        if ($isBlocked) {
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . SR_PARTNER_REDIRECT_URL);
        } else {
            $html = '
                <!DOCTYPE html>
                    <html>
                        <head>
                            <title>'.$params['title'].'</title>
                            <style>
                                html, body {
                                    margin: 0;
                                    overflow: hidden;
                                    padding: 0;
                                }
                            </style>
                        </head>
                    <body><iframe src="'.$params['url'].'" style="border:none; width:100%; height:100%; position:absolute;"></iframe></body>
                </html>
            ';
            echo $html;
        }
        exit();
    }

    /**
     * Removes http and https int the url
     *
     * @since    1.0.0
     * @param    $url - URL
     * @return   $url - string
     */
    private function remove_http($url)
    {

        $disallowed = array('http://', 'https://');
        foreach($disallowed as $d) {
            if (strpos($url, $d) === 0) {
                return str_replace($d, '', $url);
            }
        }
        return $url;
    }
}