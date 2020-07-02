<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.linkedin.com/in/sajmirdoko/
 * @since      1.0.0
 * @package    Cf7_Multi_Step_Addon
 * @subpackage Cf7_Multi_Step_Addon/includes
 * @author     Sajmir Doko <sajdoko@gmail.com>
 */
class Cf7_Multi_Step_Addon_Activator {

  public static function activate() {
    if (!get_option(CMSCA_NAME)) {
      $initial_options = array(
        'cmsca_load_css' => 1,
        'cmsca_wizard_style' => 1,
      );
      add_option(CMSCA_NAME, $initial_options);
    }
    if (!get_option(CMSCA_NAME . '_version')) {
      add_option(CMSCA_NAME . '_version', CMSCA_VERSION);
    }

  }

}
