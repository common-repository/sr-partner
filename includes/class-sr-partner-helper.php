<?php
/**
 * A general helper functions of the plugin
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    SR_Partner
 * @subpackage SR_Partner/includes
 */
class SR_Partner_Helper {

    /**
     * The global db of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The db of this plugin.
     */
    protected $_db;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->_db = new SR_Partner_Db();
    }

     /**
     * Plugin activation process
     *
     * @since       1.0.0
     * @return      String      $key - token of that need to use the plugin.
     */
    public function activate_plugin($token)
    {

        $this->_db->create_tb();

        update_option(SR_PARTNER_GEN_KEY, $token);

        add_option(SR_PARTNER_DASHBOARD_STATUS, 1, '', 'yes');
        add_option(SR_PARTNER_PORTFOLIO_STATUS, 1, '', 'yes');
        add_option(PARTNER_PLUGIN_STATUS, 0, '', 'yes');
        add_option(SR_PARTNER_LEAD_STATUS, 1, '', 'yes');

        $params                 = array();
        $params['title']        = false;
        $params['page_title']   = SR_PARTNER_DASHBOARD_PAGE_TITLE;
        $params['page_info']    = SR_PARTNER_DASHBOARD_PAGE_INFO;

        $this->create_page($params);

        $params                 = array();
        $params['title']        = false;
        $params['page_title']   = SR_PARTNER_PORTFOLIO_PAGE_TITLE;
        $params['page_info']    = SR_PARTNER_PORTFOLIO_PAGE_INFO;
        $this->create_page($params);

        $this->tracker('install');
    }

    /**
     * Get Activation key from file.
     *
     * @since       1.0.0
     * @return      String      $key - token of that need to use the plugin.
     */
    public function get_key()
    {
        $key = get_option(SR_PARTNER_GEN_KEY);
        if (empty($key)) {
            $this->read_key();
            $key = get_option(SR_PARTNER_GEN_KEY);
        }
        return $key;
    }

    /**
     * Reads Activation token from file.
     *
     * @since    1.0.0
     */
    public function read_key()
    {
        if (file_exists(plugin_dir_path( dirname( __FILE__ )).'sr-partner.json')) {
            $file = json_decode(file_get_contents(plugin_dir_path(dirname( __FILE__ )).'sr-partner.json'), true);
            if (isset($file['token'])) {
                $option = update_option(SR_PARTNER_GEN_KEY, trim($file['token']));
            }
        }
    }

    /**
     * Setting alert message to session
     *
     * @since       1.0.0
     * @param       String      $message -  message to output
     * @param       Boolean     $success -  message type true - success message / false - error message
     */
    public function set_msg($message, $success, $auto_close=1)
    {

        $messages = get_option(SR_PARTNER_ALERT_MESSAGES);
        if ($messages !== false) {
            $messages   = unserialize($messages);
        }

        $status =  $success === false ? 'error' : ($success === true ? 'success' : $success);

        $messages[] = array (
            'message'     => $message,
            'status'      => $status,
            'auto_close'  => $auto_close,
        );
        $messages =  serialize($messages);

        update_option(SR_PARTNER_ALERT_MESSAGES,$messages);
    }


    /**
     * Calls the api
     *
     * @since       1.0.0
     * @param       Array        $params -  array of data that is needed to post.
     * @return      String       $post_response -  String of the response data
     */
    public function call_api($params)
    {
        $response = wp_remote_post(
            $params['url'],
            array (
                'body'    => $params['post_string'],
                'timeout' => SR_PARTNER_API_REQUEST_TIMEOUT,
            )
        );

        $this->log($params, 'call_api');
        $this->log($response, 'call_api_response');

        if (is_wp_error($response)) {
          return false;
        }

        if (is_object($response)) {
          $response =  $this->objectToArray($response);
        }

        if (isset($response['body'])) {
          return $response['body'];
        } else {
          return false;
        }
    }

    /**
     * Calls the api GET method
     *
     * @since       1.0.0
     * @param       Array        $params -  array of data that is needed to post.
     * @return      String       $post_response -  String of the response data
     */
    public function call_api_get($params)
    {
        $response = wp_remote_get(
            $params['url'],
            array (
                'body' => $params['post_string'],
                'timeout' => SR_PARTNER_API_REQUEST_TIMEOUT,
            )
        );

        $this->log($params, 'call_api');
        $this->log($response, 'call_api_response');

        if (is_wp_error($response)) {
            return false;
        }

        if (is_object($response)) {
            $response =  $this->objectToArray($response);
        }

        if (isset($response['body'])) {
            return $response['body'];
        } else {
            return false;
        }
    }

    /**
     * Creates a new page to wordpress
     *
     * @since       1.0.0
     * @param       Array        $params -  array of data that is needed to create a page.
     */
    public function create_page($params)
    {
        $page_title = $params['page_title'];
        if ( $params['page_title'] !== false ) {
            $page_title = $params['page_title'];
        }

        $page_content = 'just leave it here.';
        $adde_name = "";
        $ctr=1;
        do {

            $temp_title   = $page_title.$adde_name;
            $page_check   = get_page_by_title($temp_title);
            $adde_name    = '-'.$ctr;

            //create the slug and check
            $page_slug    = strtolower(str_replace(" ", "-", $temp_title));
            $slug_exists  = $this->_db->the_slug_exists($page_slug);

            $ctr++;

        } while (isset($page_check->ID) && $slug_exists);

        $page_title = $temp_title;
        $page_slug  = strtolower(str_replace(" ", "-", $temp_title));

        $page = array(
            'post_type'     => 'page',
            'post_title'    => $page_title,
            'post_content'  => $page_content,
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_slug'     => $page_slug
        );

        // create the apge
        $page_id    = wp_insert_post($page);
        $page_info  = array(
            'id'        => $page_id,
            'title'     => $page_title,
            'permalink' => get_permalink($page_id)
        );

        update_option($params['page_info'], serialize($page_info));

        if ($params['page_info'] == SR_PARTNER_DASHBOARD_PAGE_INFO) {

          $is_dashboard_url_okay = true;
          $dashboard_page_info = unserialize(get_option(SR_PARTNER_DASHBOARD_PAGE_INFO));

          if (isset($dashboard_page_info['permalink'])) {

            $is_dashboard_url_okay = strpos($dashboard_page_info['permalink'], 'dashboard/' );

            if ($is_dashboard_url_okay == false) {

              $anchor = '<a class="global-auto-close-no" href="'.$dashboard_page_info['permalink'].'" target="_blank">'.$dashboard_page_info['permalink'].'</a>';
              $msg    = sprintf( __("We found an existing page called %s in your domain. We have created your %s at %s instead.", "sr-partner"), '"dashboard"', 'dashboard', $anchor);

              $this->set_msg($msg, 'warning', 0);
            }
          }
        } else if ($params['page_info'] == SR_PARTNER_PORTFOLIO_PAGE_INFO) {

          $is_portfolio_url_okay = true;
          $portfolio_page_info = unserialize(get_option(SR_PARTNER_PORTFOLIO_PAGE_INFO));
          if (isset($portfolio_page_info['permalink']) && !empty($portfolio_page_info['permalink'])) {
            $is_portfolio_url_okay = strpos($portfolio_page_info['permalink'], 'web-portfolio/' );

              if ($is_portfolio_url_okay == false) {

                $anchor = '<a class="global-auto-close-no" href="'.$portfolio_page_info['permalink'].'" target="_blank">'.$portfolio_page_info['permalink'].'</a>';
                $msg    = sprintf( __("We found an existing page called %s in your domain. We have created your %s at %s instead.", "sr-partner"), '"web-portfolio"', 'web portfolio', $anchor);

                $this->set_msg($msg, 'warning', 0);
            }
          }
        }

        return $page_id;
    }

    /**
     * Sends a signal to API endpoint for tracking of user sumbit actions
     *
     * @since       1.0.0
     * @param       String          $type - track type
     * @return      Array           $response - Array of the response data
     */
    public function tracker($type='install')
    {
        $apiKey = $this->get_key();
        $apiKey = preg_replace('/\d+\//i', '', $apiKey);

        $params['token']                    = $apiKey;

        if($type =='install') {

            $params['white_label_dashboard']    = 1;
            $params['web_design_portfolio']     = 1;
            $params['audit_widget']             = 0;
            $params['lead_tracker']             = 1;

        } else if($type =='uninstall') {

            $params['white_label_dashboard']    = 0;
            $params['web_design_portfolio']     = 0;
            $params['audit_widget']             = 0;
            $params['lead_tracker']             = 0;

        } else if($type =='post') {

            $params['white_label_dashboard']    = (isset($_POST['enable_dashboard_status']) && $_POST['enable_dashboard_status'] == 1 ) ? 1 : 0;
            $params['web_design_portfolio']     = (isset($_POST['enable_portfolio_status']) && $_POST['enable_portfolio_status'] == 1 ) ? 1 : 0;
            $params['audit_widget']             = (isset($_POST['enable_audit_status']) && $_POST['enable_audit_status'] == 1 ) ? 1 : 0;
            $params['lead_tracker']             = (isset($_POST['enable_lead_status']) && $_POST['enable_lead_status'] == 1 ) ? 1 : 0;
        }

        $website = (isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : false;

        if ($website === false) {
          $website = (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : false;
        }

        if ($website === false) {
          $website = (isset($_SERVER['SERVER_ADDR'])) ? $_SERVER['SERVER_ADDR'] : 'no server name';
        }

        $params['website']    = $website;
        $params['track_type'] = $type;
        $params['version']    = SR_PARTNER_VERSION;

        $api_params['url']          = SR_PARTNER_GENERATE_URL.'/track-partner';
        $api_params['post_string']  = $params;

        $res      = $this->call_api($api_params);
        $response = json_decode($res, true);

        return $response;

    }

    /**
     * catch the lead from contact pages
     *
     * @since       1.0.0
     * @param       String          $log - the log message
     * @param       String          $type - the type of log message
     */
    public function catch_lead($form_data)
    {
        // log the data for debugging purposes
        $this->log($form_data, 'catch_lead');

        $lead_is_active = get_option(SR_PARTNER_LEAD_STATUS);
        $lead_is_active = $lead_is_active !== false ? $lead_is_active : false;
        if ($lead_is_active === false) {
            return;
        }

        $get_data = $this->clean_map_data($form_data);


        //check if variable we're passing to API is not all in false value
        $status = false;
        foreach ($get_data as $key => $val) {
            if ( $val != false ) {
                $status = true;
            }
        }

        /** fall back for FSCF **/
        if ($get_data['from_email'] != false ) {
            $get_data['email'] = $get_data['from_email'];
            unset($get_data['from_email']);
        }

        if ($get_data['from_name'] != false ) {
            $get_data['name'] = $get_data['from_name'];
            unset($get_data['from_name']);
        }

        if ($get_data['message2'] != false ) {
            $get_data['message'] = $get_data['message2'];
            unset($get_data['message2']);
        }

        if ($get_data['comments'] != false ) {
            $get_data['message'] = $get_data['comments'];
            unset($get_data['comments']);
        }



        //check the status of data
        if ( $status ) {
            $key = $this->get_key();
            $token = preg_replace('/\d+\//i', '', $key);

            //we check if the token is set
            if (!empty($token)) {
                $get_data['token']          = $token;
                $get_data['source']         = isset($_SERVER['HTTP_REFERER']) && ! empty($_SERVER['HTTP_REFERER'])
                                            ? $_SERVER['HTTP_REFERER']
                                            : ((isset($_SERVER['SERVER_NAME']) && isset($_SERVER['REQUEST_URI']))
                                              ? $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']
                                              : '');
                $get_data['source_url']     = $get_data['source'];
                $get_data['source-slug']    = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
                $get_data['ip']             = $this->get_user_ip();
                $this->submit_lead($get_data);
            }
        }

        // Indicate success to WordPress so it continues processing other unrelated hooks.
        return true;
    }

    /*
     * Save the Lead data from CFDB Plugin
     * @date   : 04-28-2016
     */
    private function submit_lead($form_data)
    {
        try {
            if( empty($form_data)) {
                throw new Exception("Error Processing Request", 1);
            }

            $api_params['url'] = SR_PARTNER_GENERATE_URL.'/track-leads';
            $api_params['post_string'] = array_merge($form_data, [
                'http_user_agent' => (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '',
                'http_referer'    => (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '',
            ]);
            $res = $this->call_api($api_params);

            $this->log($form_data, 'form_data');
            $this->log($res, 'call_api');
        } catch (Exception $e) {
            //there is a problem saving lead
            $this->log($e, 'Error Submit Lead');
        }
    }

    /*
     * get the visitors ip
     * @date   : 08-16-2016
     */
    public function get_user_ip()
    {
      if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
      } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      } else {
        $ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '';
      }
      return $ip;
    }

    /**
     * converts object to array
     *
     * @since       1.0.0
     * @param       Object          $object - the object
     * @return      Array           $array -
     */
    public function objectToArray ($object) {
        if (!is_object($object) && !is_array($object)) {
            return $object;
        }

        return array_map(array($this, 'objectToArray'), (array) $object);
    }

    /**
     * Clean and Map data
     *
     * @since       1.0.0
     * @param       Object          $data - the log message
     * @return      Array           $map_string - array of cleaned form data we needed
     */
    private function clean_map_data($data)
    {
        if( ! empty($data)) {

          if (is_object($data)) {
            $data =  $this->objectToArray($data);
          }

            if( count($data['posted_data']) > 0 ) {

                //set the needed string to find in form data
                $map_string = array(
                    'from_email'    => false,
                    'from_name'     => false,
                    'email'         => false,
                    'name'          => false,
                    'phone'         => false,
                    'subject'       => false,
                    'message'       => false,
                    'message2'      => false,
                    'comments'      => false,
                );

                foreach ($data['posted_data'] as $form_key => $form_val) {

                    // get email
                    if ( filter_var($form_val, FILTER_VALIDATE_EMAIL) && $map_string['email'] == false ) {
                        $map_string['email'] = $form_val;
                    }

                    // try to get from_email for FSCF form
                    if ( filter_var($form_val, FILTER_VALIDATE_EMAIL) && $map_string['from_email'] == false ) {
                        if ($form_val != $map_string['email']) {
                            $map_string['from_email'] = $form_val;
                        }

                    }

                    // get phone
                    if ( strlen($form_val) >= 7 && $map_string['phone'] == false ) {
                        if ( preg_match('/^[\+0-9\-\(\)\s]*$/', $form_val) ) {
                            $map_string['phone'] = $form_val;
                        }
                    }

                    // get name
                    if ( strpos(strtolower($form_key), "name") !== false &&  strlen($form_val) >= 3 && $map_string['name'] == false ) {
                            $map_string['name'] = $form_val;
                    }

                     // try to get from_name for FSCF form
                    if ( strpos(strtolower($form_key), "from_name") !== false && strlen($form_val) >= 3 && $map_string['from_name'] == false ) {
                        if ($form_val != $map_string['name']) {
                            $map_string['from_name'] = $form_val;
                        }
                    }

                    // get subject
                    if ( strpos(strtolower($form_key), "subject") !== false && $map_string['subject'] == false ) {
                        $map_string['subject'] = $form_val;
                    }

                    // get message
                    if ( strpos(strtolower($form_key), "message") !== false && $map_string['message'] == false ) {
                        $map_string['message'] = $form_val;
                    }

                    // get message2 fallback for WR forms
                    if ( strpos(strtolower($form_key), "message") !== false && $map_string['message2'] == false ) {
                        if ($form_val != $map_string['message']) {
                            $map_string['message2'] = $form_val;
                        }
                    }

                    // get comments fallback for Caldera forms
                    if ( strpos(strtolower($form_key), "comments") !== false && $map_string['comments'] == false ) {
                        $map_string['comments'] = $form_val;
                    }



                }

                return $map_string;

            }
        }
    }


    /**
     * Writes log to wordpress
     *
     * @since       1.0.0
     * @param       String          $log - the log message
     * @param       String          $type - the type of log message
     */
    public function log($log, $type='message')
    {
        $log_dest = plugin_dir_path( dirname( __FILE__ ) ) . 'logs/logs-'. gmdate('Y-m-d') .'.php';
        if (SR_PARTNER_DEBUG === true) {
            if (is_array($log) || is_object($log)) {
                $log = print_r($log, true);
            }
            $log = gmdate('Y-m-d H:i:s'). ' - '.$type.' - ' .$log.PHP_EOL;
            error_log($log , 3, $log_dest);
        }
    }
}
