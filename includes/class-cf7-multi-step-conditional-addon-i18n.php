<?php

/**
 * Define the internationalization functionality
 *
 * @link       https://www.linkedin.com/in/sajmirdoko/
 * @package    Cf7_Multi_Step_Conditional_Addon
 * @subpackage Cf7_Multi_Step_Conditional_Addon/includes
 * @author     Sajmir Doko <sajdoko@gmail.com>
 */
class Cf7_Multi_Step_Conditional_Addon_i18n {

  public function load_plugin_textdomain() {

    load_plugin_textdomain(
      'cf7-multi-step-conditional-addon',
      false,
      dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
    );

  }

}
