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

<div class="control-box cmsca-multistep">
  <fieldset>
    <legend><?php echo esc_html(__( 'Generate a form-tag to enable a multistep form', $this->plugin_name)); ?></legend>

    <table class="form-table">
      <tbody>
        <tr>
          <th scope="row">
            <label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', $this->plugin_name ) ); ?></label>
          </th>
          <td>
            <input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" readonly="readonly" /><br>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="<?php echo esc_attr( $args['content'] . '-values' ); ?>"><?php echo esc_html( __( 'Step heading title', $this->plugin_name ) ); ?></label>
          </th>
          <td>
            <input type="text" name="values" class="oneline" id="<?php echo esc_attr( $args['content'] . '-values' ); ?>" />
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', $this->plugin_name ) ); ?></label>
          </th>
          <td>
            <input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" />
          </td>
        </tr>
      </tbody>
    </table>
  </fieldset>
</div>
<div class="insert-box">
  <input type="text" name="<?php echo esc_attr( $args['title'] ); ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

  <div class="submitbox">
    <input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', $this->plugin_name ) ); ?>" />
  </div>

  <br class="clear" />

  <p class="description mail-tag">
    <label><?php echo esc_html( __( "This field should not be used on the Mail tab.", $this->plugin_name ) ); ?></label>
  </p>
</div>