<?php
/**
 *
 *
 * This class defines all code necessary to run database functions.
 *
 * @since      1.0.0
 * @package    SR_Partner
 * @subpackage SR_Partner/includes 
 */
class SR_Partner_Db {

    /**
     * Table name
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $table_name    The table name of this plugin.
     */
    private $table_name;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'sr_partner_shortcodes';
    }

    /**
     * Creates a table for the plugin
     *
     * @since    1.0.0
     */
    public function create_tb()
    {
        global $wpdb;
        $sql = "CREATE TABLE IF NOT EXISTS $this->table_name (
            id  int(11) NOT NULL AUTO_INCREMENT,
            form_name   varchar(255) NOT NULL,
            form_type   varchar(50) NOT NULL,
            attributes  text NOT NULL,
            embed_code  text NOT NULL,
          UNIQUE KEY id (id));";
        $wpdb->query($sql);
    }

    /**
     * Drops the for this plugin
     *
     * @since    1.0.0
     */
    public function drop_tb()
    {
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS $this->table_name;");
    }

    /**
     * Save the shortcode for this plugin
     *
     * @since       1.0.0
     * @param       Array   $data - data that needs to be inserted
     * @return      Int     $id - Insert id of the shortcode
     */
    public function save_shortcode($data)
    {
        global $wpdb;

        $wpdb->insert(
            $this->table_name,
            array(
                'form_name'     => $data['form_name'],
                'form_type'     => $data['form_type'],
                'attributes'    => $data['attributes'],
                'embed_code'    => $data['embed_code']
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            )
        );

        return $wpdb->insert_id;
    }

    /**
     * Update the shortcode for this plugin
     *
     * @since       1.0.0
     * @param       Array    $data - data that needs to be inserted
     * @return      boolean  $boolean - if the update is success or not
     */
    public function update_shortcode($data)
    {
        global $wpdb;

        return $wpdb->update(
            $this->table_name,
            array(
                'form_name'     => $data['form_name'],
                'form_type'     => $data['form_type'],
                'attributes'    => $data['attributes'],
                'embed_code'    => $data['embed_code']
            ),
            array( 'id'         => $data['id'])
        );
    }

    /**
     * Get the embed_code for this plugin
     *
     * @since       1.0.0
     * @param       Int             $short_code_id - id of shortcode data that needs to be query
     * @return      String or empy   $embed_code - embed_code data of this shortcode or empty string
     */
    public function get_shortcode($short_code_id)
    {
        if ($short_code_id > 0) {
            global $wpdb;
            $short_code_id = intval($short_code_id);
            $shortcode = $wpdb->get_row("select embed_code from $this->table_name where id=$short_code_id");
            if (isset($shortcode))
                return $shortcode;
        }
        return '';
    }

    /**
     * get the shortcode for this plugin
     *
     * @since       1.0.0
     * @param       Int             $short_code_id - id of shortcode data that needs to be query
     * @return      Array           $data - array data of this shortcode or empty string
     */
    public function get_shortcode_object($short_code_id)
    {
        if ($short_code_id > 0) {
            global $wpdb;
            $short_code_id = intval($short_code_id);

            $shortcode = $wpdb->get_row("select * from $this->table_name where id=$short_code_id");
            if (isset($shortcode))
                return $shortcode;
        }
        return array();
    }

    /**
     * get ALL of the shortcodes the the table
     *
     * @since       1.0.0
     * @return      Array           $data - array data of the shortcodes
     */
    public function get_all_shortcodes()
    {
        global $wpdb;
        $shortcodes = array();
        $shortcodes = $wpdb->get_results("select * from $this->table_name ORDER BY id DESC ", ARRAY_A);
        return $shortcodes;
    }

    /**
     * delete the entryin the table
     *
     * @since       1.0.0
     * @param       Int             $shortcodeid - id of shortcode data that needs to be query
     * @return      Boolean         $Boolean - if delete was successful or not
     */
    public function delete_shortcode($shortcodeid)
    {
        global $wpdb;
        $shortcodeid = intval($shortcodeid);
        return $wpdb->delete($this->table_name,array(
            'id'=>$shortcodeid
        ));
    }

    /**
     * delete bul the entryin the table
     *
     * @since       1.0.0
     * @param       Array           $shortcodes -  array of ids of  that needs to be query
     * @return      Boolean         $Boolean - if delete was successful or not
     */
    public function delete_bulk_shortcodes($shortcodes)
    {
        global $wpdb;

        if (is_array($shortcodes) && !empty($shortcodes)) {
            $count = count($shortcodes);
            $sql = "DELETE FROM $this->table_name WHERE `id` IN(" . implode(', ', array_fill(0, $count, '%d')) . ")";
            $query = call_user_func_array(array($wpdb, 'prepare'), array_merge(array($sql), $shortcodes));

            return $wpdb->query($query);
        }

    }

    /**
     * checks if the slug exists or not
     *
     * @since       1.0.0
     * @param       String          $post_name -  slug of the page
     * @return      Boolean         $Boolean - if the slug exists of not
     */
    public function the_slug_exists($post_name)
    {
        global $wpdb;
        if($wpdb->get_row("SELECT post_name FROM $wpdb->posts WHERE post_name = '" . $post_name . "'", 'ARRAY_A')) {
            return true;
        } else {
            return false;
        }
    }
}
