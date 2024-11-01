<?php

/**
*Load the global database class
**/
require_once plugin_dir_path( __FILE__ )  . 'class-sr-partner-db.php';

/**
*Load the global helper class
**/
require_once plugin_dir_path( __FILE__ ) . 'class-sr-partner-helper.php';

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    SR_Partner
 * @subpackage SR_Partner/includes
 */
class SR_Partner_Core  {

    /**
     * The global db of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of this plugin.
     */
    protected $_db;

    /**
     * The global helper of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of this plugin.
     */
    protected $_helper;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->_helper  = new SR_Partner_Helper();
        $this->_db      = new SR_Partner_Db();
    }

}
