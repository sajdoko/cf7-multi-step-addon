<?php

/**
 * The file that defines the core plugin class
 *
 * @link       https://www.linkedin.com/in/sajmirdoko/
 * @since      1.0.0
 *
 * @package    Cf7_Multi_Step_Conditional_Addon
 * @subpackage Cf7_Multi_Step_Conditional_Addon/includes
 */

/**
 * The core plugin class.
 *
 * @since      1.0.0
 * @package    Cf7_Multi_Step_Conditional_Addon
 * @subpackage Cf7_Multi_Step_Conditional_Addon/includes
 * @author     Sajmir Doko <sajdoko@gmail.com>
 */
class Cf7_Multi_Step_Conditional_Addon {

  /**
   * The loader that's responsible for maintaining and registering all hooks that power
   * the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      Cf7_Multi_Step_Conditional_Addon_Loader    $loader    Maintains and registers all hooks for the plugin.
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
   * Define the core functionality of the plugin.
   *
   * @since    1.0.0
   */
  public function __construct() {
    if (defined('CMSCA_VERSION')) {
      $this->version = CMSCA_VERSION;
    } else {
      $this->version = '1.0.0';
    }
    if (defined('CMSCA_NAME')) {
      $this->plugin_name = CMSCA_NAME;
    } else {
      $this->plugin_name = 'cf7_multi_step_conditional_addon';
    }

    $this->load_dependencies();
    $this->set_locale();
    $this->define_admin_hooks();
    $this->define_public_hooks();
    $this->define_multistep_admin_hooks();
    $this->define_multistep_public_hooks();

  }

  /**
   * Load the required dependencies for this plugin.
   * Create an instance of the loader which will be used to register the hooks
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function load_dependencies() {

    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cf7-multi-step-conditional-addon-loader.php';

    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cf7-multi-step-conditional-addon-i18n.php';

    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-cf7-multi-step-conditional-addon-admin.php';

    require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-cf7-multi-step-conditional-addon-public.php';

    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-cf7-multi-step-conditional-addon-multistep-admin.php';

    require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-cf7-multi-step-conditional-addon-multistep-public.php';

    $this->loader = new Cf7_Multi_Step_Conditional_Addon_Loader();

  }

  /**
   * Define the locale for this plugin for internationalization.
   *
   * @since    1.0.0
   * @access   private
   */
  private function set_locale() {

    $plugin_i18n = new Cf7_Multi_Step_Conditional_Addon_i18n();

    $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

  }

  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_admin_hooks() {

    $plugin_admin = new Cf7_Multi_Step_Conditional_Addon_Admin($this->get_plugin_name(), $this->get_version());

    $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'cmsca_enqueue_styles');
    $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'cmsca_enqueue_scripts');

    $this->loader->add_filter('plugin_action_links_' . plugin_basename(plugin_dir_path(__DIR__) . 'cf7-multi-step-conditional-addon.php'), $plugin_admin,'cmsca_add_action_links');

    $this->loader->add_action('admin_menu', $plugin_admin, 'cmsca_add_admin_menu', 99);

    $this->loader->add_action('plugins_loaded', $plugin_admin, 'cmsca_check_for_cf7', 10);

  }

  /**
   * Register all of the hooks related to the public-facing functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_public_hooks() {

    $plugin_public = new Cf7_Multi_Step_Conditional_Addon_Public($this->get_plugin_name(), $this->get_version());

    $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
    $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

  }

  /**
   * Register all of the hooks related to the multistep admin functionality of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_multistep_admin_hooks() {

    $plugin_multistep_admin = new Cf7_Multi_Step_Conditional_Addon_Multistep_Admin($this->get_plugin_name(), $this->get_version());

    $this->loader->add_action('admin_init', $plugin_multistep_admin, 'cmsca_add_multistep_tag_generator', 30);

    $this->loader->add_filter('wpcf7_messages', $plugin_multistep_admin, 'cmsca_multistep_messages');
    $this->loader->add_filter('wpcf7_collect_mail_tags', $plugin_multistep_admin, 'cmsca_remove_multistep_mail_tag');

  }

  /**
   * Register all of the hooks related to the multistep public functionality of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_multistep_public_hooks() {

    $plugin_multistep_public = new Cf7_Multi_Step_Conditional_Addon_Multistep_Public($this->get_plugin_name(), $this->get_version());

    $this->loader->add_action('wpcf7_init', $plugin_multistep_public, 'cmsca_add_multistep_shortcode');

    $this->loader->add_filter('wpcf7_form_elements', $plugin_multistep_public, 'cmsca_wpcf7_form_elements_process');

    $this->loader->add_action('wp_ajax_cmsca_public_ajax', $plugin_multistep_public, 'cmsca_public_ajax');
    $this->loader->add_action('wp_ajax_nopriv_cmsca_public_ajax', $plugin_multistep_public, 'cmsca_public_ajax');

  }

  /**
   * Run the loader to execute all of the hooks with WordPress.
   *
   * @since    1.0.0
   */
  public function run() {
    $this->loader->run();
  }

  /**
   * The name of the plugin used to uniquely identify it within the context of
   * WordPress and to define internationalization functionality.
   *
   * @since     1.0.0
   * @return    string    The name of the plugin.
   */
  public function get_plugin_name() {
    return $this->plugin_name;
  }

  /**
   * The reference to the class that orchestrates the hooks with the plugin.
   *
   * @since     1.0.0
   * @return    Cf7_Multi_Step_Conditional_Addon_Loader    Orchestrates the hooks of the plugin.
   */
  public function get_loader() {
    return $this->loader;
  }

  /**
   * Retrieve the version number of the plugin.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public function get_version() {
    return $this->version;
  }

}
