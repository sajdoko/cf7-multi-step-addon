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
      wpcf7_add_form_tag(array('cmscamultistep'), array($this, 'cmsca_multistep_shortcode_handler'), true);
    } else if (function_exists('wpcf7_add_shortcode')) {
      wpcf7_add_shortcode(array('cmscamultistep'), array($this, 'cmsca_multistep_shortcode_handler'), true);
    }
  }

  /**
   * Handle the cmscamultistep handler
   * Adds the cmscamultistep separator tags to form.
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
    $send_button = '';
    $cmsca_step_titles = array();
    $cmsca_step_classes_regex = '/\[CMSCA-CLASSES\]([^`]*?)\[\/CMSCA-CLASSES\]/';
    $cmsca_step_title_regex = '/\[CMSCA-NAME\]([^`]*?)\[\/CMSCA-NAME\]/';
    $previous_step_button = '<button type="button" class="cmsca_previous_button" style="display:none;">Previous</button>';
    $next_step_button = '<button type="button" class="cmsca_next_button">Next</button>';

    if (preg_match_all('/' . preg_quote($cmsca_step_separator) . '/', $form, $step_maches)) {
      // echo '<script>console.log('.json_encode(count($step_maches[0])).')</script>';
      if (count($step_maches[0]) < 2) {
        $form = preg_replace('/' . preg_quote($cmsca_step_separator) . '/', '', $form);
        $form = preg_replace($cmsca_step_classes_regex, '', $form);
        $form = preg_replace($cmsca_step_title_regex, '', $form);
        return $form;
      }
      if (preg_match_all('/<input type="submit"([^`]*?) \/>/', $form, $send_button_maches)) {
        $send_button = $send_button_maches[0][0];
        $send_button = preg_replace('/\/>/', 'style="display:none;" />', $send_button);
        $form = preg_replace('/<input type="submit"([^`]*?) \/>/', '', $form);
      }

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
          $is_step = 'first-step cmsca-step-active';
          $not_first_step = '';
        }
        end($step_maches[0]);
        if ($key === key($step_maches[0]) - 1) {
          $is_step = 'last-step';
          $form = preg_replace('/' . preg_quote($cmsca_step_separator) . '/', $not_first_step . '<div class="cmsca-step-separator">', $form, 1);
          $form = preg_replace('/' . preg_quote($cmsca_step_div_class) . '/', 'cmsca-step ' . $is_step . $cmsca_step_classes, $form, 1);
        } else if ($key === key($step_maches[0])) {
          $form = preg_replace('/' . preg_quote($cmsca_step_separator) . '/', '', $form, 1);
        } else {
            $form = preg_replace('/' . preg_quote($cmsca_step_separator) . '/', $not_first_step . '<div class="cmsca-step-separator">', $form, 1);
            $form = preg_replace('/' . preg_quote($cmsca_step_div_class) . '/', 'cmsca-step ' . $is_step . $cmsca_step_classes, $form, 1);
        }
      }
      $ul_progressbar = $this->cmsca_build_progressbar($cmsca_step_titles);
      $footer_form = '<div class="cmsca-multistep-form-footer">' . $previous_step_button . $next_step_button . $send_button . '</div>';
      $form = '<div class="cmsca-multistep-form">' . $ul_progressbar . $form . '</div>' . $footer_form . '</div>';
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
    array_pop($step_titles);
    $progressbar = '<div class="cmsca-multistep-form-header"><ul class="cmsca-multistep-progressbar">';
    $li_width = round((100 / count($step_titles)), 2) . '%';
    foreach ($step_titles as $key => $title) {
      $is_first_li = '';
      reset($step_titles);
      if ($key === key($step_titles)) {
        $is_first_li = ' active';
      }
      if ($title == '') {
        $title = 'Step ' . ((int) $key + 1);
      }
      $progressbar .= '<li class="step-' . $key . $is_first_li . '" style="width:' . $li_width . '">' . $title . '</li>';
    }
    $progressbar .= '</ul></div>';
    return $progressbar;
  }


  public function cmsca_public_ajax() {
    if (!check_ajax_referer($this->plugin_name, 'security')) {
      wp_send_json_error(__('Security is not valid!', $this->plugin_name));
      die();
    }
    if (isset($_POST['action']) && $_POST['action'] == 'cmsca_public_ajax') {
      $to_validate = isset($_POST['validate']) ? $_POST['validate'] : array();
      $valid = array();
      foreach ($to_validate as $key => $val) {
        // $valid[$val['name']] = $this->cmsca_validate_text_fields($val['formId'], $val['type'], $val['value']);
        $validate = $this->cmsca_validate_text_fields($val['formId'], $val['type'], $val['value']);
        if ($validate['valid'] == false) {
          $valid[$val['name']] = $validate['message'];
        }
      }
      if (!empty($valid)) {
        wp_send_json_error($valid);
      } else {
        wp_send_json_success(__('Valid fields!', $this->plugin_name));
      }
      die();
    } else {
      wp_send_json_error(__('Action is not valid!', $this->plugin_name));
      die();
    }
  }

  public function cmsca_validate_text_fields($form_id, $type, $value)  {
    $result = array('valid' => true, 'message' => 'ok');
    require_once WPCF7_PLUGIN_DIR . '/includes/formatting.php';
    if ( $type == 'text' ) {
      if ( $value == '' ) {
        $result['valid'] = false;
        $result['message'] = $this->cmsca_get_form_messages($form_id, 'invalid_required');
      }
    }
    if ( $type == 'email' ) {
      if ( $value == '' ) {
        $result['valid'] = false;
        $result['message'] = $this->cmsca_get_form_messages($form_id, 'invalid_required');
      } else if ( $value != '' && !wpcf7_is_email( $value )) {
        $result['valid'] = false;
        $result['message'] = $this->cmsca_get_form_messages($form_id, 'invalid_email');
      }
    }
    if ( $type == 'url' ) {
      if ( $value == '' ) {
        $result['valid'] = false;
        $result['message'] = $this->cmsca_get_form_messages($form_id, 'invalid_required');
      } else if ( $value != '' && !wpcf7_is_url( $value )) {
        $result['valid'] = false;
        $result['message'] = $this->cmsca_get_form_messages($form_id, 'invalid_url');
      }
    }
    if ( $type == 'tel' ) {
      if ( $value == '' ) {
        $result['valid'] = false;
        $result['message'] = $this->cmsca_get_form_messages($form_id, 'invalid_required');
      } else if ( $value != '' && !wpcf7_is_tel( $value )) {
        $result['valid'] = false;
        $result['message'] = $this->cmsca_get_form_messages($form_id, 'invalid_tel');
      }
    }
    return $result;
  }

  public function cmsca_get_form_messages($form_id, $message_key)  {
    $messages = get_post_meta($form_id, '_messages', true);
    return $messages[$message_key];
  }
}
