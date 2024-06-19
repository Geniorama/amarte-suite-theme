<?php
/**
 * The template for displaying the list of bookings in the summary for customers.
 * It is used in:
 * - templates/order/booking-display.php
 * - templates/order/admin/booking-display.php
 * It will display in four places:
 * - After checkout,
 * - In the order confirmation email, and
 * - When customer reviews order in My Account > Orders,
 * - When reviewing a customer order in the admin area.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce-bookings/order/booking-summary-list.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/bookings-templates/
 * @author  Automattic
 * @version 1.15.44
 * @since   1.10.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
	<ul class="wc-booking-summary-list">
		<li> Entrada: 
			<?php 
			// Fecha de entrada
			
			echo esc_html( $booking->get_start_date( null, null, wc_should_convert_timezone( $booking ) ) );
			?>
			- Salida: 
			<?php

			// Fecha de salida haciendo la modificación para que reste una hora

			$end_date = $booking->get_end_date( null, null, wc_should_convert_timezone( $booking ) );

			// Convertir la fecha a inglés
			$months = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");
			$months_en = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
			$end_date_en = str_replace($months, $months_en, strtolower($end_date));

			$end_date_timestamp = strtotime($end_date_en);
			$end_date_minus_one_hour = $end_date_timestamp - 3600; // restamos 3600 segundos, que es una hora
			$end_date_formatted = date('F j, Y, g:i a', $end_date_minus_one_hour); // formateamos la fecha

			// Convertir el mes a español
			$end_date_formatted_es = str_replace($months_en, $months, $end_date_formatted);
			echo esc_html( ucfirst($end_date_formatted_es) );
			?>
		</li>
		
		<li>
			<?php 
			// Fecha de entrada
			$start_date = $booking->get_start_date( null, null, wc_should_convert_timezone( $booking ) );
			$start_date_info = date_parse_from_format('F j, Y, g:i a', $start_date);
			$start_hour = $start_date_info['hour'];
			$start_minute = $start_date_info['minute'];

			// Fecha de salida
			$end_date = $booking->get_end_date( null, null, wc_should_convert_timezone( $booking ) );
			$end_date_info = date_parse_from_format('F j, Y, g:i a', $end_date);
			$end_hour = $end_date_info['hour'];
			$end_minute = $end_date_info['minute'];

			// Restar una hora a la hora de salida
			$end_timestamp = mktime($end_hour, $end_minute);
			$end_timestamp_minus_one_hour = strtotime('-1 hour', $end_timestamp);
			$end_hour_minus_one = date_parse_from_format('H:i', date('H:i', $end_timestamp_minus_one_hour))['hour'];
			$end_minute_minus_one = date_parse_from_format('H:i', date('H:i', $end_timestamp_minus_one_hour))['minute'];
			
			// Convertir las horas de entrada y salida en marcas de tiempo Unix
			$start_timestampt = strtotime(date('Y-m-d', time()) . ' ' . date('H:i', mktime($start_hour, $start_minute)));
			$end_timestampt = strtotime(date('Y-m-d', time()) . ' ' . date('H:i', mktime($end_hour_minus_one, $end_minute_minus_one)));

			// Compruebe si la hora de finalización es anterior a la hora de inicio
			if ($end_timestampt < $start_timestampt) {
				$end_timestampt += 24 * 3600; // Añade 24 horas a la hora de finalización.
			}

			// Calcular la diferencia en segundos
			$diff_seconds = abs($end_timestampt - $start_timestampt);

			// Convertir la diferencia en segundos a horas
			$diff_hours = $diff_seconds / 3600;

			echo "El paquete seleccionado es: ";

			// Verificar el rango de la diferencia en horas
			if ($diff_hours >= 6 && $diff_hours <= 11) {
				$extra_hours = $diff_hours - 6;
				echo "Pack 6 horas";
				if ($extra_hours > 0) {
					echo " más " . $extra_hours . " horas adicionales";
				}
			} elseif ($diff_hours >= 12 && $diff_hours <= 21) {
				$extra_hours = $diff_hours - 12;
				echo "Pack 12 horas";
				if ($extra_hours > 0) {
					echo " + " . $extra_hours . " horas adicionales";
				}
			} elseif ($diff_hours >= 22) {
			 	echo "Día hotelero";
			}
			?>
		</li>

		<?php if ( $resource ) : ?>
			<li>
			<?php
			/* translators: 1: label 2: resource name */
			echo esc_html( sprintf( __( '%1$s: %2$s', 'woocommerce-bookings' ), $label, $resource->get_name() ) );
			?>
			</li>
		<?php endif; ?>

		<?php
		if ( $product && $product->has_persons() ) {
			if ( $product->has_person_types() ) {
				$person_types  = $product->get_person_types();
				$person_counts = $booking->get_person_counts();

				if ( ! empty( $person_types ) && is_array( $person_types ) ) {
					foreach ( $person_types as $person_type ) {

						if ( empty( $person_counts[ $person_type->get_id() ] ) ) {
							continue;
						}

						?>
						<li><?php echo esc_html( sprintf( '%s: %d', $person_type->get_name(), $person_counts[ $person_type->get_id() ] ) ); ?></li>
						<?php
					}
				}
			} else {
				?>
				<li>
				<?php
				/* translators: 1: person count */
				echo esc_html( sprintf( __( '%d Persons', 'woocommerce-bookings' ), array_sum( $booking->get_person_counts() ) ) );
				?>
				</li>
				<?php
			}
		}
		?>
	</ul>