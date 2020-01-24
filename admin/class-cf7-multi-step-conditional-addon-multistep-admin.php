<?php

/**
 * The multistep-specific functionality of the plugin.
 *
 * @link       https://www.linkedin.com/in/sajmirdoko/
 * @since      1.0.0
 * @package    Cf7_Multi_Step_Conditional_Addon
 * @subpackage Cf7_Multi_Step_Conditional_Addon/admin
 * @author     Sajmir Doko <sajdoko@gmail.com>
 */
class Cf7_Multi_Step_Conditional_Addon_Multistep_Admin {

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
   * Add multistep to wpcf7 tag generator.
   * @since    1.0.0
   */
  public function cmsca_add_multistep_tag_generator() {
    if (class_exists('WPCF7_TagGenerator')) {
      $tag_generator = WPCF7_TagGenerator::get_instance();
      $tag_generator->add('multistep', esc_html(__('multistep', $this->plugin_name)), array($this, 'cmsca_multistep_tag_generator'));
    }
  }

  /**
   * Multistep tag pane.
   * @since    1.0.0
   */
  public function cmsca_multistep_tag_generator($contact_form, $args = '') {
    $args = wp_parse_args($args, array());

    // echo '<script>console.log('.json_encode($args).')</script>';
    include_once 'partials/cf7-multi-step-conditional-addon-multistep-box-display.php';
  }

  /**
   * Error messages if first step is not set and user did not already visit the first step.
   * @since    1.0.0
   */
  public function cmsca_multistep_messages($messages) {
    $messages = array_merge($messages, array(
      'invalid_first_step' => array(
        'description' => __("The sender visited this form without submitting the first step of the multistep forms.", $this->plugin_name),
        'default' => __("Please fill out the form on the previous step.", $this->plugin_name),
      ),
    ));
    return $messages;
  }

  /**
   * Remove multistep mail tag from mail tab tag suggestions.
   * @since    1.0.0
   */
  public function cmsca_remove_multistep_mail_tag($args) {
    if (is_array($args) && !empty($args)) {
      foreach ($args as $key => $value) {
        if (preg_match('/multistep/', $value)) {
          unset($args[$key]);
        }
      }
    }
    return $args;
  }
}
