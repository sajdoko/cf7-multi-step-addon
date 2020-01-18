<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.linkedin.com/in/sajmirdoko/
 * @since      1.0.0
 *
 * @package    Cf7_Multi_Step_Conditional_Addon
 * @subpackage Cf7_Multi_Step_Conditional_Addon/admin/partials
 */

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}
?>
<div class="wrap">

  <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
  <hr>
  <?php settings_errors();?>
    <?php
      //Plugin options
      $options = get_option($this->plugin_name);
      settings_fields($this->plugin_name);
      do_settings_sections($this->plugin_name);
    ?>

  <form method="post" name="<?php echo $this->plugin_name; ?>_options" id="<?php echo $this->plugin_name; ?>_options" action="options.php">
    <div id="poststuff">
      <div id="post-body" class="metabox-holder columns-2">
        <!-- main content -->
        <div id="post-body-content">
          <div class="postbox">
            <div class="inside">

            </div>
          </div>
        </div>
      </div>
      <br class="clear">
    </div>

    <?php submit_button(__('Save Options', $this->plugin_name), 'primary', 'submit', TRUE);?>
  </form>
</div>