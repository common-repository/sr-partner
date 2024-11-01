<?php
/**
 * Fired during plugin uninstallation.
 *
 * This class defines all code necessary to run during the plugin's uninstallation.
 *
 * @since      1.0.0
 * @package    SR_Partner
 * @subpackage SR_Partner/includes 
 */
class SR_Partner_Uninstall extends SR_Partner_Core {


    /**
     * Removes the table related to this plugin
     *
     * @since    1.0.0
     */
    public function uninstall() {
        $this->_db->drop_tb();
    }
}
