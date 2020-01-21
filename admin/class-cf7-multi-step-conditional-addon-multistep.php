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
class Cf7_Multi_Step_Conditional_Addon_Multistep {

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
   * Initialize wpcf7 shortcode.
   * @since    1.0.0
   */
  public function cmsca_add_multistep_shortcode() {
    if (function_exists('wpcf7_add_form_tag')) {
      wpcf7_add_form_tag(array('multistep'), array($this, 'cmsca_multistep_shortcode_handler'), true);
    } else if (function_exists('wpcf7_add_shortcode')) {
      wpcf7_add_shortcode(array('multistep'), array($this, 'cmsca_multistep_shortcode_handler'), true);
    }
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
   * Handle the multistep handler
   * This shortcode lets the plugin determine if the form is a multi-step form
   * @since    1.0.0
   */
  public function cmsca_multistep_shortcode_handler($tag) {
    $tag = new WPCF7_FormTag($tag);
    if (empty($tag->name)) {
      return $this->cmsca_multistep_shortcode_handler_old($tag);
    }
    $class = wpcf7_form_controls_class($tag->type, 'cmsca-multistep');

    $options = array();

    $atts = array(
      'type' => 'hidden',
      'name' => 'cmsca_multistep_tag',
      'class' => $tag->get_class_option($class),
      'value' => '' . json_encode($options),
    );
    $atts = wpcf7_format_atts($atts);
    $html = sprintf('<input %1$s />', $atts);

    echo json_encode($tag);
    return $html;
  }

  /**
   *
   * @since    1.0.0
   */
  public function cmsca_multistep_shortcode_handler_old($tag) {
    $class = wpcf7_form_controls_class($tag->type, 'cmsca-multistep');
    $class .= ' cmsca-multistep';
    if ('multistep*' === $tag->type) {
      $class .= ' wpcf7-validates-as-required';
    }

    $value = (string) reset($tag->values);

    $multistep_values = $this->cmsca_format_multistep_value($value);
    $step_value = $multistep_values['curr_step'] . '-' . $multistep_values['total_steps'];

    $atts = array(
      'type' => 'hidden',
      'class' => $tag->get_class_option($class),
      'value' => $step_value,
      'name' => 'cmsca-step',
    );
    $atts = wpcf7_format_atts($atts);
    $html = sprintf('<input %1$s />', $atts);

    return $html;
  }

  /**
   * Multistep tag pane.
   * @since    1.0.0
   */
  public function cmsca_multistep_tag_generator($contact_form, $args = '') {
    $args = wp_parse_args($args, array());
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
   * Return the step value and next url in an array.  URL may be empty.
   */
  public function cmsca_format_multistep_value($valueString) {
    $no_url = false;
    $next_url = '';

    $i = stripos($valueString, '-');
    $curr_step = substr($valueString, 0, $i);
    $j = stripos($valueString, '-', $i + 1);
    if ($j === FALSE) {
      $j = strlen($valueString);
      $no_url = true;
    }
    $total_steps = substr($valueString, $i + 1, $j - ($i + 1));
    if (!$no_url) {
      $next_url = substr($valueString, $j + 1);
    }

    return array(
      'curr_step' => $curr_step,
      'total_steps' => $total_steps,
      'next_url' => $next_url,
    );
  }
}
