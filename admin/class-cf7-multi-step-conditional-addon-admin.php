<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.linkedin.com/in/sajmirdoko/
 * @since      1.0.0
 *
 * @package    Cf7_Multi_Step_Conditional_Addon
 * @subpackage Cf7_Multi_Step_Conditional_Addon/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cf7_Multi_Step_Conditional_Addon
 * @subpackage Cf7_Multi_Step_Conditional_Addon/admin
 * @author     Sajmir Doko <sajdoko@gmail.com>
 */
class Cf7_Multi_Step_Conditional_Addon_Admin {

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
  public function __construct($plugin_name, $version) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

  }

  /**
   * Register the stylesheets for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Cf7_Multi_Step_Conditional_Addon_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Cf7_Multi_Step_Conditional_Addon_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/cf7-multi-step-conditional-addon-admin.css', array(), $this->version, 'all');

  }

  /**
   * Register the JavaScript for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts() {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Cf7_Multi_Step_Conditional_Addon_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Cf7_Multi_Step_Conditional_Addon_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/cf7-multi-step-conditional-addon-admin.js', array('jquery'), $this->version, false);

  }

  public function cf7_multi_step_conditional_addon_add_admin_menu() {
    /*
     * Add a settings page for this plugin to the Settings menu.
     *
     */
    add_submenu_page('wpcf7', __('CF7 Multi Step Options', $this->plugin_name), __('Multi Step Options', $this->plugin_name), 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page'));
  }

  /**
   * Render the settings page for this plugin.
   *
   */

  public function display_plugin_setup_page() {
    include_once 'partials/cf7-multi-step-conditional-addon-admin-display.php';
  }

  /**
   * Add's action links to the plugins page.
   *
   */

  public function cf7_multi_step_conditional_addon_add_action_links($links) {
    $settings_link = array(
      '<a href="' . admin_url('admin.php?page=' . $this->plugin_name) . '">' . __('Settings', $this->plugin_name) . '</a>',
    );
    return array_merge($settings_link, $links);
  }

}
