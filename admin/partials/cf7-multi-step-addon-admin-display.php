<?php

/**
 * Provide a admin area view for the plugin
 *
 * @link       https://www.linkedin.com/in/sajmirdoko/
 * @since      1.0.0
 * @package    Cf7_Multi_Step_Addon
 * @subpackage Cf7_Multi_Step_Addon/admin/partials
 */

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}
?>
<div class="wrap">

  <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
  <hr>
  <?php
    settings_errors();
    //Plugin options
    $options = get_option($this->plugin_name);
    echo "<pre>";
    print_r($options);
    echo "</pre>";
  ?>
  <form method="post" name="<?php echo $this->plugin_name; ?>_options" id="<?php echo $this->plugin_name; ?>_options" action="options.php">
    <?php
      settings_fields($this->plugin_name);
      do_settings_sections($this->plugin_name);
    ?>

    <table class="form-table" role="presentation">

      <tr>
        <th scope="row"><?php _e( 'Use plugin\'s css styles?' ); ?></th>
        <td>
          <fieldset>
            <legend class="screen-reader-text"><span><?php _e( 'Use plugin\'s css styles?' ); ?></span></legend>
            <label for="cmsca_load_css">
              <input name="cmsca_load_css" type="checkbox" id="cmsca_load_css" value="on" <?php checked($options['cmsca_load_css'], 'on'); ?> />
              <?php _e( 'Un-check to use theme\'s design' ); ?>
            </label>
          </fieldset>
        </td>
      </tr>

    </table>
    <?php submit_button(__('Save Options', $this->plugin_name), 'primary', 'submit', TRUE);?>
  </form>
</div>