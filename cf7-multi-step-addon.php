<?php

/**
 * @link              https://www.linkedin.com/in/sajmirdoko/
 * @since             1.0.0
 * @package           Cf7_Multi_Step_Addon
 *
 * @wordpress-plugin
 * Plugin Name:       CF7 Multi-Step Add-on
 * Plugin URI:        https://cf7multistepaddon.com
 * Description:       Extends the Contact Form 7 plugin to create multi-step forms.
 * Version:           1.0.0
 * Author:            Sajmir Doko
 * Author URI:        https://www.linkedin.com/in/sajmirdoko/
 * Text Domain:       cf7-multi-step-addon
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
  die;
}

define('CMSCA_NAME', 'cf7_multi_step_addon');
define('CMSCA_VERSION', '1.0.0');

function activate_cf7_multi_step_addon() {
  require_once plugin_dir_path(__FILE__) . 'includes/class-cf7-multi-step-addon-activator.php';
  Cf7_Multi_Step_Addon_Activator::activate();
}

function deactivate_cf7_multi_step_addon() {
  require_once plugin_dir_path(__FILE__) . 'includes/class-cf7-multi-step-addon-deactivator.php';
  Cf7_Multi_Step_Addon_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_cf7_multi_step_addon');
register_deactivation_hook(__FILE__, 'deactivate_cf7_multi_step_addon');

require plugin_dir_path(__FILE__) . 'includes/class-cf7-multi-step-addon.php';

function run_cf7_multi_step_addon() {

  $plugin = new Cf7_Multi_Step_Addon();
  $plugin->run();

}
run_cf7_multi_step_addon();
