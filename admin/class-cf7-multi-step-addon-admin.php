<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.linkedin.com/in/sajmirdoko/
 * @since      1.0.0
 * @package    Cf7_Multi_Step_Addon
 * @subpackage Cf7_Multi_Step_Addon/admin
 * @author     Sajmir Doko <sajdoko@gmail.com>
 */
class Cf7_Multi_Step_Addon_Admin {

  private $plugin_name;
  private $version;

  public function __construct($plugin_name, $version) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

  }

  public function cmsca_enqueue_styles() {

    wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/cf7-multi-step-addon-admin.css', array(), $this->version, 'all');

  }

  public function cmsca_enqueue_scripts() {

    wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/cf7-multi-step-addon-admin.js', array('jquery'), $this->version, false);

  }

  public function cmsca_add_admin_menu() {

    add_submenu_page('wpcf7', __('CF7 Multi Step Options', $this->plugin_name), __('Multi Step Options', $this->plugin_name), 'manage_options', $this->plugin_name, array($this, 'cmsca_display_plugin_setup_page'));

  }

  public function cmsca_display_plugin_setup_page() {
    include_once 'partials/cf7-multi-step-addon-admin-display.php';
  }

  public function cmsca_validate_options() {
    $valid = array();
    // wp_die(json_encode($_POST));
    $valid['cmsca_load_css'] = (isset($_POST['cmsca_load_css']) && $_POST['cmsca_load_css'] === 'on') ? 'on' : 'off';

    $exiting_options = get_option($this->plugin_name);
    if ($exiting_options) {
      $valid = array_merge($exiting_options, $valid);
    }
    return $valid;
  }

  public function cmsca_options_update() {
    register_setting($this->plugin_name, $this->plugin_name, array($this, 'cmsca_validate_options'));
  }

  public function cmsca_add_action_links($links) {
    $settings_link = array(
      '<a href="' . admin_url('admin.php?page=' . $this->plugin_name) . '">' . __('Settings', $this->plugin_name) . '</a>',
    );
    return array_merge($settings_link, $links);
  }

  public function cmsca_check_for_cf7() {
    add_action('admin_notices', array($this, 'cmsca_display_admin_warning'));
  }

  public function cmsca_display_admin_warning() {
    $html = '<div class="error" id="messages"><p>';
    if (defined('WPCF7_VERSION') && version_compare(WPCF7_VERSION, 5) < 0) {
      $html .= sprintf(__('CF7 Multi-Step Add-on plugin requires Contact Form 7 version %s or above.', $this->plugin_name), 5);
    } else {
      if (!file_exists(WP_PLUGIN_DIR . '/contact-form-7/wp-contact-form-7.php')) {
        $html .= sprintf(__('<strong>You must install Contact Form 7</strong> for the CF7 Multi-Step Add-on to work. <a href="%s" title="Contact Form 7">Install Now.</a>', $this->plugin_name), wp_nonce_url(admin_url('update.php?action=install-plugin&plugin=contact-form-7'), 'install-plugin_contact-form-7'));
      } else if (!in_array('contact-form-7/wp-contact-form-7.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        $html .= sprintf(__('<strong>You must activate Contact Form 7</strong> for the CF7 Multi-Step Add-on to work. <a href="%s" title="Contact Form 7">Activate Now.</a>', $this->plugin_name), wp_nonce_url(admin_url('plugins.php?action=activate&plugin=contact-form-7/wp-contact-form-7.php'), 'activate-plugin_contact-form-7/wp-contact-form-7.php'));
      } else {
        return;
      }
    }
    $html .= '</p></div>';
    echo $html;
  }

}
