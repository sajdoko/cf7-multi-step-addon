<?php

/**
 * The multistep-specific functionality of the plugin.
 *
 * @link       https://www.linkedin.com/in/sajmirdoko/
 * @since      1.0.0
 * @package    Cf7_Multi_Step_Conditional_Addon
 * @subpackage Cf7_Multi_Step_Conditional_Addon/public
 * @author     Sajmir Doko <sajdoko@gmail.com>
 */
class Cf7_Multi_Step_Conditional_Addon_Multistep_Public {

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
   * Handle the multistep handler
   * Adds the multistep separator tags to form.
   * @since    1.0.0
   */
  public function cmsca_multistep_shortcode_handler($tag) {
    if (empty($tag->name)) {
      return '';
    }
    $classes_string = '';
    $step_name_string = '';
    if (class_exists('WPCF7_FormTag')) {
      $tag = new WPCF7_FormTag($tag);
      $classes_string = '';
      $step_name_string = (string) reset($tag->values);
      $classes_arr = ($tag->has_option('class')) ? $tag->get_option('class', 'class') : array();
      foreach ($classes_arr as $class) {
        if ($class != 'cmsca-step-separator') {
          $classes_string .= ' ' . $class;
        }
      }
    }
    return "[CMSCA-STEP-SEPARATOR][CMSCA-CLASSES]" . $classes_string . "[/CMSCA-CLASSES][CMSCA-NAME]" . $step_name_string . "[/CMSCA-NAME]";
  }

  /**
   * Check if there are step separators. If so, process the form in multisteps.
   * @since    1.0.0
   */
  public function cmsca_wpcf7_form_elements_process($form) {
    $cmsca_step_separator = '[CMSCA-STEP-SEPARATOR]';
    $cmsca_step_div_class = 'cmsca-step-separator';
    $cmsca_step_classes = '';
    $cmsca_step_titles = array();
    $cmsca_step_classes_regex = '/\[CMSCA-CLASSES\]([^`]*?)\[\/CMSCA-CLASSES\]/';
    $cmsca_step_title_regex = '/\[CMSCA-NAME\]([^`]*?)\[\/CMSCA-NAME\]/';
    $next_step_button = '<button type="button" name="cmsca_next_button" class="next action-button">Next</button>';
    $previous_step_button = '<button type="button" name="cmsca_previous_button" class="previous action-button">Previous</button>';

    if (preg_match_all('/' . preg_quote($cmsca_step_separator) . '/', $form, $step_maches)) {
      $form = preg_replace('/<p>|<\/p>|<br \/>/', '', $form); // Remove paragraphs and new lines
      foreach ($step_maches[0] as $key => $value) {
        if (preg_match($cmsca_step_classes_regex, $form, $classes_maches)) {
          $cmsca_step_classes = $classes_maches[1];
          $form = preg_replace($cmsca_step_classes_regex, '', $form, 1);
        }
        if (preg_match($cmsca_step_title_regex, $form, $title_maches)) {
          array_push($cmsca_step_titles, $title_maches[1]);
          $form = preg_replace($cmsca_step_title_regex, '', $form, 1);
        }

        $not_first_step = '</div>';
        $is_step = 'step';
        reset($step_maches[0]);
        if ($key === key($step_maches[0])) {
          $is_step = 'first-step';
          $not_first_step = '';
        }
        end($step_maches[0]);
        if ($key === key($step_maches[0])) {
          $is_step = 'last-step';
        }
        $form = preg_replace('/' . preg_quote($cmsca_step_separator) . '/', $not_first_step . '<div class="cmsca-step-separator">', $form, 1);
        $form = preg_replace('/' . preg_quote($cmsca_step_div_class) . '/', 'cmsca-step-' . ((int) $key + 1) . ' ' . $is_step . $cmsca_step_classes, $form, 1);
      }
      $ul_progressbar = $this->cmsca_build_progressbar($cmsca_step_titles);
      $footer_form = '<div class="cmsca-multistep-form-footer">' . $previous_step_button . $next_step_button . '</div>';
      $form = '<div class="cmsca-multistep-form">' . $ul_progressbar . $form . '</div>' . $footer_form . '</div>';
      // echo '<script>console.log(' . json_encode($form) . ')</script>';
      return $form;
    } else {
      return $form;
    }
  }

  /**
   * Build form progressbar.
   * @since    1.0.0
   */
  public function cmsca_build_progressbar($step_titles) {
    if (!is_array($step_titles)) {
      return;
    }

    $progressbar = '<div class="cmsca-multistep-form-header"><ul class="cmsca-multistep-progressbar">';
    foreach ($step_titles as $key => $title) {
      if ($title == '') {
        $title = 'Step ' . ((int) $key + 1);
      }
      $progressbar .= '<li>' . $title . '</li>';
    }
    $progressbar .= '</ul></div>';
    return $progressbar;
  }
}
