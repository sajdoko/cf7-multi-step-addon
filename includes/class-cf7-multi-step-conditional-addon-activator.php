<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.linkedin.com/in/sajmirdoko/
 * @since      1.0.0
 *
 * @package    Cf7_Multi_Step_Conditional_Addon
 * @subpackage Cf7_Multi_Step_Conditional_Addon/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Cf7_Multi_Step_Conditional_Addon
 * @subpackage Cf7_Multi_Step_Conditional_Addon/includes
 * @author     Sajmir Doko <sajdoko@gmail.com>
 */
class Cf7_Multi_Step_Conditional_Addon_Activator {

  /**
   * Short Description. (use period)
   *
   * Long Description.
   *
   * @since    1.0.0
   */
  public static function activate() {

    if ( !in_array( 'contact-form-7/wp-contact-form-7.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
      if ( current_user_can( 'activate_plugins' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );
        wp_die( '<p>' . esc_html__( 'This plugin requires ', CF7_MULTI_STEP_CONDITIONAL_ADDON_NAME ) . '<a href="' . esc_url( 'https://wordpress.org/plugins/contact-form-7/' ) . '" target="_blank">Contact Form 7</a>' . esc_html__( ' plugin to be active.', CF7_MULTI_STEP_CONDITIONAL_ADDON_NAME ) . '</p>', 'Plugin dependency check', array( 'back_link' => true ) );
      }
    }

  }

}
