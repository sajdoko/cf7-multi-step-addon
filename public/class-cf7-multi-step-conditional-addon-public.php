<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.linkedin.com/in/sajmirdoko/
 * @since      1.0.0
 * @package    Cf7_Multi_Step_Conditional_Addon
 * @subpackage Cf7_Multi_Step_Conditional_Addon/public
 * @author     Sajmir Doko <sajdoko@gmail.com>
 */
class Cf7_Multi_Step_Conditional_Addon_Public {

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
   * @param      string    $plugin_name       The name of the plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct($plugin_name, $version) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

  }

  /**
   * Register the stylesheets for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {

    wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/cf7-multi-step-conditional-addon-public.css', array(), $this->version, 'all');

  }

  /**
   * Register the JavaScript for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts() {

    wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/cf7-multi-step-conditional-addon-public.js', array('jquery'), $this->version, false);
    wp_localize_script($this->plugin_name, 'cmsca_public_ajax_object',
    array(
      'ajaxurl' => admin_url('admin-ajax.php'),
      'security' => wp_create_nonce($this->plugin_name),
      // 'data_var_1' => 'value 1',
      // 'data_var_2' => 'value 2',
    )
  );
  }

}
