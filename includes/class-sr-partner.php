<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    SR_Partner
 * @subpackage SR_Partner/includes 
 */
class SR_Partner extends SR_Partner_Core {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      SR_Partner_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * The base filename of the plugin
     *
     * @since    1.1.3
     * @access   protected
     * @var      string    $base_filename   The base filename of the plugin
     */
    protected $base_filename;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct($base_filename)
    {
        parent::__construct();
        $this->plugin_name      = SR_PARTNER_PLUGIN_NAME;
        $this->version          = SR_PARTNER_VERSION;
        $this->base_filename    = $base_filename;

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_integration_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - SR_Partner_Loader. Orchestrates the hooks of the plugin.
     * - SR_Partner_i18n. Defines internationalization functionality.
     * - SR_Partner_Admin. Defines all hooks for the admin area.
     * - SR_Partner_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sr-partner-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sr-partner-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-sr-partner-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-sr-partner-public.php';

        $this->loader = new SR_Partner_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the SR_Partner_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new SR_Partner_i18n();
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }
    /**
     * Register all of the hooks related to the ingegration with other plugins
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_integration_hooks()
    {
        $lead_is_active = get_option(SR_PARTNER_LEAD_STATUS);
        $lead_is_active = $lead_is_active !== false ? $lead_is_active : false;
        if ($lead_is_active == 1) {

            $path = plugin_dir_path( dirname( __FILE__ ) );
            // Hook into Contact Form 7 when a form post is made - tested
            require_once $path . 'integrations/class-integrate-ContactForm7.php';
            $integration = new SR_Partner_Integrate_ContactForm7();
            $integration->register_hooks();

            // Hook into Fast Secure Contact Form - tested
            require_once $path . 'integrations/class-integrate-FSCF.php';
            $integration = new SR_Partner_Integrate_FSCF();
            $integration->register_hooks();

            // Hook into JetPack Contact Form
            require_once $path . 'integrations/class-integrate-JetPackCF.php';
            $integration = new SR_Partner_Integrate_JetPackCF();
            $integration->register_hooks();

            // Hook into Gravity Forms
            require_once $path . 'integrations/class-integrate-GravityForms.php';
            $integration = new SR_Partner_Integrate_GravityForms();
            $integration->register_hooks();


            // Hook into Formidable Forms
            require_once $path . 'integrations/class-integrate-FormidableForms.php';
            $integration = new SR_Partner_Integrate_FormidableForms();
            $integration->register_hooks();


            // Hook to work with WR ContactForms - tested
            require_once $path . 'integrations/class-integrate-WRContactForms.php';
            $integration = new SR_Partner_Integrate_WRContactForms();
            $integration->register_hooks();

            // Hook to work with Quform
            require_once $path . 'integrations/class-integrate-Quform.php';
            $integration = new SR_Partner_Integrate_Quform();
            $integration->register_hooks();


            // Hook to work with Ninja Forms - tested
            require_once $path . 'integrations/class-integrate-NinjaForms.php';
            $integration = new SR_Partner_Integrate_NinjaForms();
            $integration->register_hooks();


            // Hook to work with Caldera Forms Forms  - tested
            require_once $path . 'integrations/class-integrate-CalderaForms.php';
            $integration = new SR_Partner_Integrate_CalderaForms();
            $integration->register_hooks();


            // Hook to work with EnfoldThemForms  - tested
            require_once $path . 'integrations/class-integrate-EnfoldThemForms.php';
            $integration = new SR_Partner_Integrate_EnfoldThemForms();
            $integration->register_hooks();


            // Hook to work with CFormsII - tested
            require_once $path . 'integrations/class-integrate-CFormsII.php';
            $integration = new SR_Partner_Integrate_CFormsII();
            $integration->register_hooks();

            // Hook to work with FormCraft - tested
            require_once $path . 'integrations/class-integrate-FormCraft.php';
            $integration = new SR_Partner_Integrate_FormCraft();
            $integration->register_hooks();

            // Hook to work with Very Simple Contact Form - tested
            require_once $path . 'integrations/class-integrate-VerySimpleCF.php';
            $integration = new SR_Partner_Integrate_VerySimpleCF();
            $integration->register_hooks();


            // Hook to work with Forms Management System
            require_once $path . 'integrations/class-integrate-FormsManagementSystem.php';
            $integration = new SR_Partner_Integrate_FormsManagementSystem();
            $integration->register_hooks();




        }

    }
    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new SR_Partner_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_base_filename() );

        $this->loader->add_action( 'admin_notices', $plugin_admin, 'admin_notices' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'admin_init' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menu' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new SR_Partner_Public( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'init', $plugin_public, 'public_init_hook' );
        $this->loader->add_action( 'wp_ajax_sr_partner_ajax', $plugin_public, 'sr_partner_ajax' );
        $this->loader->add_action( 'wp_ajax_nopriv_sr_partner_ajax', $plugin_public, 'sr_partner_ajax' );

        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        $this->loader->add_action( 'wp_footer', $plugin_public, 'hook_to_all_pages' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    SR_Partner_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

     /**
     * Retrieve the base filename of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_base_filename()
    {
        return $this->base_filename;
    }
}
