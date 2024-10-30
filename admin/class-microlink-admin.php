<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://microlink.io/
 * @since      1.0.0
 *
 * @package    Microlink
 * @subpackage Microlink/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Microlink
 * @subpackage Microlink/admin
 * @author     Jon Torrado <jontorrado@gmail.com>
 */
class Microlink_Admin {

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
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/microlink-admin.css', array(), $this->version, 'all' );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        wp_register_script( $this->plugin_name . '-cdn', 'https://cdn.jsdelivr.net/npm/microlinkjs@latest/umd/microlink.min.js', null, null, true );
        wp_enqueue_script($this->plugin_name . '-cdn');

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . '../public/js/microlink-public.js', array( 'jquery' ), $this->version, true );

    }

    /**
     * Admin head checks
     *
     * @since    1.0.0
     */
    public function admin_head() {
        // check user permissions
        if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
            return;
        }

        // check if WYSIWYG is enabled
        if ( 'true' == get_user_option( 'rich_editing' ) ) {
            add_filter( 'mce_external_plugins', array( $this, 'mce_external_plugins' ) );
            add_filter( 'mce_buttons', array( $this, 'mce_buttons' ) );
        }
    }

    /**
     * Register the menu item for settings page in WP `Settings` menu
     *
     * @since    1.0.0
     */
    public function admin_menu_item() {
        add_options_page( 'Microlink Settings', 'Microlink', 'manage_options', 'microlink-settings', array($this, 'settings_page') );
    }

    /**
     * Register the options page for `microlink_settings_page`
     *
     * @since    1.0.0
     */
    public function register_settings() {
        register_setting( 'microlink-settings', 'microlink_api_key' );
    }

    /**
     * Register the options page for `microlink_settings_page`
     *
     * @since    1.0.0
     */
    public function settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Microlink Settings', $this->plugin_name);?></h1>
            <p>Global settings applied to all instances of the <code>[microlink href="%link%"]</code> shortcodes or links with class="microlink".</p>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'microlink-settings' );
                do_settings_sections( 'microlink-settings' );
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php _e('API Key', $this->plugin_name);?></th>
                        <td>
                            <input type="text" name="microlink_api_key" value="<?php echo esc_attr( get_option('microlink_api_key') ); ?>" />
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Adds our TinyMCE plugin
     *
     * @param  array $plugin_array
     * @return array
     */
    function mce_external_plugins( $plugin_array ) {
        $plugin_array[$this->plugin_name] = plugin_dir_url( __FILE__ ) . 'js/microlink-plugin.js';

        return $plugin_array;
    }

    /**
     * Adds our TinyMCE button
     *
     * @param  array $buttons
     * @return array
     */
    function mce_buttons( $buttons ) {
        array_push( $buttons, $this->plugin_name );

        return $buttons;
    }

}
