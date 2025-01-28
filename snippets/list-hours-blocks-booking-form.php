<?php
/**
 * Filtra las horas de salida para los paquetes de 4, 6, 12 y día hotelero
 * El filtro se visualiza en el select del formulario de reservas en el single product
 * Esto es para que no se pueda seleccionar una hora de salida que no corresponda
 */
add_filter('wc_bookings_get_end_time_html', 'hz_custom_end_time_html', 10, 4);
function hz_custom_end_time_html($block_html, $data, $blocks, $product)
{
    /**
     * Script para cambiar la palabra quedan y queda del select de la hora de inicio
     */
?>
    <script>
        jQuery(document).ready(function($) {

            $('.wc-bookings-start-time-container label[for="wc-bookings-form-start-time"]').text("Hora Check-in:");

            $('#wc-bookings-form-start-time option').each(function() {
                var textLeft = $(this).text();

                // Verificar si el texto contiene la palabra "quedan" o "queda"
                if (textLeft.includes('quedan')) {
                    var textAvailable = textLeft.replace('quedan', 'disponibles');
                    $(this).text(textAvailable);
                } else if (textLeft.includes('queda')) {
                    var textAvailable = textLeft.replace('queda', 'disponibles');
                    $(this).text(textAvailable);
                }
            });

            // 	Agregar frase "(precio pareja)" al Coste de la reserva
            $('#wc-bookings-form-end-time').on('change', function() {
                var inputValuePersons = $('.wc_bookings_field_persons').val();
                setTimeout(() => {
                    if (inputValuePersons == 0) {
                        $('.hz-book-resume__totals strong').before('<span style="font-size: 16px;font-weight: 500;margin-left: -4px;margin-right: 6px;">(precio pareja)</span>');
                    }
                }, "1000");
            });
        });
    </script>
    <?php

    $block_html  = '';
    $block_html .= '<div class="wc-bookings-end-time-container hz-packages">';

    $block_html .= '<span class="hz-label">Tiempo de estadía:</span>';
    if (empty($data)) {
        // No hay datos disponibles
        $block_html .= '<div class="hz-alert hz-package-not-selected">';
        $block_html .= '<span>Selecciona una fecha y hora para ver los paquetes disponibles</span>';
        $block_html .= '</div>';
    } else {
        $block_html .= '<select id="wc-bookings-form-end-time" name="end_time">';
        $block_html .= '<option value="0" disabled selected>' . esc_html__('Selecciona un paquete', 'woocommerce-bookings') . '</option>';
        $hasPlan = isset($_POST['typePlanField']);
        foreach ($data as $booking_data) {
            $display  = $booking_data['display'];
            $end_time = $booking_data['end_time'];
            $duration = $booking_data['duration'];

            if (validarBloqueHora($duration, $hasPlan, $end_time)) {
                $package_name = '';

                // Asignar el nombre del paquete
                if ($duration === 5) {
                    $package_name = 'Pack 4 horas';
                } elseif ($duration === 7) {
                    $package_name = 'Pack 6 horas';
                } elseif ($duration === 9) {
                    $package_name = 'Pack 8 horas';
                } elseif ($duration === 13) {
                    $package_name = 'Pack 12 horas';
                } elseif (strpos(get_time_as_iso8601($end_time), 'T13:00:00') !== false && $duration >= 14) {
                    $package_name = 'Día hotelero';
                }

                if (validarBloquePrecio($duration, $product, $hasPlan)) {
                    $block_html .= '<option data-duration-display="' . esc_attr($display) . '" data-value="' . get_time_as_iso8601($end_time) . '" value="' . esc_attr($duration) . '">' . $package_name . '</option>';
                }
            }
        }
        $block_html .= '</select>';
    }

    $block_html .= '</div>';

    return $block_html;
}

/**
 * Valida que el bloque de horas seleccionado sea alguno de los permitidos
 * Siendo estos, 4horas, 8horas, 12horas o Día hotelero
 */
function validarBloqueHora($duration, $hasPlan, $end_time)
{
    if ($hasPlan) return ($duration === 5 || $duration === 7 || $duration === 13 || (strpos(get_time_as_iso8601($end_time), 'T13:00:00') !== false && $duration >= 14));
    else return ($duration === 5 || $duration === 9 || $duration === 13 || (strpos(get_time_as_iso8601($end_time), 'T13:00:00') !== false && $duration >= 14));
}

/**
 * Valida que el bloque de horas seleccionado tenga un precio asignado
 * Esto también aplica para poder mantener el bloque de hora de manera opcional
 * Por lo que si un bloque de hora no tiene precio asignado, no se mostrará en el select
 */
function validarBloquePrecio($duration, $product, $hasPlan)
{
    if($hasPlan) {
        $roomTypePlan = sanitize_text_field($_POST['typePlanField']);
        $hotelSettings = get_page_by_path('configuraciones-generales', OBJECT, 'hotel_settings');
        $hotelSettingsID = $hotelSettings->ID;
        $allPlans = get_field('configuraciones_planes', $hotelSettingsID)['planes'];
        foreach ($allPlans as $plan) {
            if ($plan['tipo_de_plan'] === $roomTypePlan) {
                if ($duration === 5) return $plan['costo_4horas_fds'] && $plan['costo_4horas'];
                if ($duration === 7) return $plan['costo_6horas_fds'] && $plan['costo_6horas'];
                if ($duration === 13) return $plan['costo_12horas_fds'] && $plan['costo_12horas'];
                if ($duration >= 14) return $plan['dia_hotelero_fds'] && $plan['dia_hotelero'];
            }
        }
    } else {
        if ($duration === 5) return get_field('costo_de_4_horas_fs', $product->id) && get_field('costo_de_4_horas', $product->id);
        if ($duration === 9) return get_field('costo_de_6_horas_fs', $product->id) && get_field('costo_de_6_horas', $product->id);
        if ($duration === 13) return get_field('costo_de_12_horas_fs', $product->id) && get_field('costo_de_12_horas', $product->id);
        if ($duration >= 14) return get_field('costo_de_24_horas_fs', $product->id) && get_field('costo_de_24_horas', $product->id);
    }
}