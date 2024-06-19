<?php

/**
 * The template used for number fields in the booking form, such as persons or durations.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce-bookings/booking-form/number.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/bookings-templates/
 * @author  Automattic
 * @version 1.8.0
 * @since   1.0.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

$after = isset($field['after']) ? $field['after'] : null;
$class = $field['class'];
$label = $field['label'];
$max   = isset($field['max']) ? $field['max'] : null;
$min   = isset($field['min']) ? $field['min'] : null;
$name  = $field['name'];
$step  = isset($field['step']) ? $field['step'] : null;
?>
<div class="hz-form-field hz-number-person">
	<p class="hz-additional__person">
		<label class="hz-label" for="<?php echo esc_attr($name); ?>"><strong>Persona(s) adicional(es)</strong>
			<i style="font-weight: 500;">(opcional)</i>:
		</label>
		<input type="number" class="hz-input <?php echo esc_attr(implode(' ', $class)); ?>" value="<?php echo (!empty($min)) ? esc_attr($min) : 0; ?>" step="<?php echo (isset($step)) ? esc_attr($step) : ''; ?>" min="<?php echo (isset($min)) ? esc_attr($min) : ''; ?>" max="<?php echo (isset($max)) ? esc_attr($max) : ''; ?>" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($name); ?>" /> <?php echo (!empty($after)) ? esc_html($after) : ''; ?>
	</p>
</div>