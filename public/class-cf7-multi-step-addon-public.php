<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.linkedin.com/in/sajmirdoko/
 * @since      1.0.0
 * @package    Cf7_Multi_Step_Addon
 * @subpackage Cf7_Multi_Step_Addon/public
 * @author     Sajmir Doko <sajdoko@gmail.com>
 */
class Cf7_Multi_Step_Addon_Public {

  private $plugin_name;
  private $version;

  public function __construct($plugin_name, $version) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

  }

  public function enqueue_styles() {

    wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/cf7-multi-step-addon-public.css', array(), $this->version, 'all');

  }

  public function enqueue_scripts() {

    wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/cf7-multi-step-addon-public.js', array('jquery'), $this->version, false);
    wp_localize_script($this->plugin_name, 'cmsca_public_ajax_object',
      array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'security' => wp_create_nonce($this->plugin_name),
      )
    );
  }

  public function echo_critical_css() {
    echo "<style>
      ul.cmsca-multistep-progressbar {
        overflow: hidden;
        margin: 0px;
        padding: 1em 0;
        counter-reset: step;
      }
      .cmsca-multistep-progressbar li {
        text-align: center;
        list-style-type: none;
        float: left;
        position: relative;
        margin: 0px;
      }
      .cmsca-multistep-progressbar li:before {
        content: counter(step);
        counter-increment: step;
        width: 24px;
        height: 24px;
        line-height: 26px;
        display: block;
        font-size: 12px;
        color: #333;
        background: white;
        border-radius: 25px;
        margin: 0 auto 10px auto;
      }
      .cmsca-multistep-progressbar li:after {
        content: '';
        width: 100%;
        height: 2px;
        background: white;
        position: absolute;
        left: -50%;
        top: 9px;
        z-index: -1;
      }
      .cmsca-multistep-progressbar li:first-child:after {
        content: none;
      }
      .cmsca-multistep-form .cmsca-multistep-form-footer {
        margin: 0px;
        padding: 1em 0;
        text-align: right;
      }
      .cmsca-multistep-form .step, .cmsca-multistep-form .last-step {
        display: none;
      }
    </style>";
  }

}
