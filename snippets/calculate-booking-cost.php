<?php
/**
 * Filter para editar el costo de las habitaciones en función de 
 * la cantidad de horas seleccionadas, de la cantidad de personas adicionales 
 * y por el tipo de decoración seleccionado
 */

add_filter('woocommerce_bookings_calculated_booking_cost', 'hz_custom_booking_cost', 10, 3);
function hz_custom_booking_cost($booking_cost, $product, $data)
{
    // Obtener costo por la decoración adicional de la habitación
    $resource_base_costs = $product->get_resource_base_costs();
    if (isset($data['_resource_id'])) $cost_decoration = isset($resource_base_costs[$data['_resource_id']]) && $resource_base_costs[$data['_resource_id']] > 0 ? $resource_base_costs[$data['_resource_id']] : 0;
    else $cost_decoration = 0;
    // Obtener la hora de salida
    $end_date = filter_var($data["_end_date"], FILTER_SANITIZE_NUMBER_INT);
    $departure_date = date("Y-m-d\TH:i:s", $end_date);
    $departure_time = substr($departure_date, 11, 8);

    // Obtener la fecha de inicio de la reserva en formato Unix
    $booking_start_timestamp = isset($data['_start_date']) ? $data['_start_date'] : 0;

    if (!empty($booking_start_timestamp)) {
        $booking_day_of_week = date('l', $booking_start_timestamp);
    }

    // Obtener la duración de la reserva en horas
    $booking_duration = 0;

    if (isset($data['_duration'])) {
        $booking_duration = absint($data['_duration']);
    } elseif (isset($data['_end_date']) && isset($data['_start_date'])) {
        $start_time = strtotime($data['_start_date']);
        $end_time = strtotime($data['_end_date']);
        $booking_duration = max(0, ($end_time - $start_time) / HOUR_IN_SECONDS);
    }

    // Retornar un precio diferente en función de la cantidad de horas seleccionadas por el usuario
    // El costo es agregado en un custom field dentro de la habitación desde el admin
    if (is_weekend($booking_day_of_week)) {
        $cost_by_aditional_person = get_field('costo_por_persona_adicional_fs', $product->id);
        if ($booking_duration >= 5 && $booking_duration < 9) {
            $horas_extras = $booking_duration - 5;
            $cost_by_duration = get_field('costo_de_4_horas_fs', $product->id) + ($horas_extras * get_field('costo_de_hora_adicional_fs', $product->id));
        } elseif ($booking_duration >= 9 && $booking_duration < 13) {
            $horas_extras = $booking_duration - 9;
            $cost_by_duration = get_field('costo_de_6_horas_fs', $product->id) + ($horas_extras * get_field('costo_de_hora_adicional_fs', $product->id));
        } elseif ($booking_duration >= 13 && $booking_duration < 25 && $departure_time !== '13:00:00') {
            $horas_extras = $booking_duration - 13;
            $cost_by_duration = get_field('costo_de_12_horas_fs', $product->id) + ($horas_extras * get_field('costo_de_hora_adicional_fs', $product->id));
        } elseif ($booking_duration >= 14) {
            $cost_by_duration = get_field('costo_de_24_horas_fs', $product->id);
        }
    } else {
        $cost_by_aditional_person = get_field('costo_por_persona_adicional', $product->id);
        if ($booking_duration >= 5 && $booking_duration < 9) {
            $horas_extras = $booking_duration - 5;
            $cost_by_duration = get_field('costo_de_4_horas', $product->id) + ($horas_extras * get_field('costo_de_hora_adicional', $product->id));
        } elseif ($booking_duration >= 9 && $booking_duration < 13) {
            $horas_extras = $booking_duration - 9;
            $cost_by_duration = get_field('costo_de_6_horas', $product->id) + ($horas_extras * get_field('costo_de_hora_adicional', $product->id));
        } elseif ($booking_duration >= 13 && $booking_duration < 25 && $departure_time !== '13:00:00') {
            $horas_extras = $booking_duration - 13;
            $cost_by_duration = get_field('costo_de_12_horas', $product->id) + ($horas_extras * get_field('costo_de_hora_adicional', $product->id));
        } elseif ($booking_duration >= 14) {
            $cost_by_duration = get_field('costo_de_24_horas', $product->id);
        }
    }

    // Costo total por persona adicional - las habitaciones por defecto vienen hasta para 2 personas
    $cost_total_aditional_person = 0;
    if ($data['_persons'][0] > 0) {
        $cost_total_aditional_person = $data['_persons'][0] * $cost_by_aditional_person;
    }

    /**
     * Calcula el costo de la reserva cuando se está reservando un paquete / plan
     */
    $hasPlan = isset($_POST['ha_type_plan_field']);
    if ($hasPlan) {
        // A cada bloque se le agrega 1hora más para considerar el tiempo necesario para limpieza
        $bloque_4hs = 5;
        $bloque_6hs = 7;
        $bloque_8hs = 9;
        $bloque_12hs = 13;        
        $roomTypePlan = sanitize_text_field($_POST['ha_type_plan_field']);
        $hotelSettings = get_page_by_path('configuraciones-generales', OBJECT, 'hotel_settings');
        $hotelSettingsID = $hotelSettings->ID;
        $allPlans = get_field('configuraciones_planes', $hotelSettingsID)['planes'];
        foreach ($allPlans as $plan) {
            if ($plan['tipo_de_plan'] === $roomTypePlan) {
                if ($booking_duration >= $bloque_4hs) {
                    $horas_extras = $booking_duration - $bloque_4hs;
                    if ($plan['costo_4horas_fds'] && $plan['costo_4horas']) {                        
                        $cost_by_duration = is_weekend($booking_day_of_week) ?
                            $plan['costo_4horas_fds'] + ($horas_extras * get_field('costo_de_hora_adicional_fs', $product->id)) :
                            $plan['costo_4horas'] + ($horas_extras * get_field('costo_de_hora_adicional', $product->id));
                    }
                } 
                if ($booking_duration >= $bloque_6hs) {
                    $horas_extras = $booking_duration - $bloque_6hs;
                    if ($plan['costo_6horas_fds'] && $plan['costo_6horas']) {
                        $cost_by_duration = is_weekend($booking_day_of_week) ?
                            $plan['costo_6horas_fds'] + ($horas_extras * get_field('costo_de_hora_adicional_fs', $product->id)) :
                            $plan['costo_6horas'] + ($horas_extras * get_field('costo_de_hora_adicional', $product->id));
                    }
                } 
                if ($booking_duration >= $bloque_8hs) {
                    $horas_extras = $booking_duration - $bloque_8hs;
                    if ($plan['costo_8horas_fds'] && $plan['costo_8horas']) {
                        $cost_by_duration = is_weekend($booking_day_of_week) ?
                            $plan['costo_8horas_fds'] + ($horas_extras * get_field('costo_de_hora_adicional_fs', $product->id)) :
                            $plan['costo_8horas'] + ($horas_extras * get_field('costo_de_hora_adicional', $product->id));
                    }
                } 
                if ($booking_duration >= $bloque_12hs && $booking_duration < 25 && $departure_time !== '13:00:00') {
                    $horas_extras = $booking_duration - $bloque_12hs;
                    if ($plan['costo_12horas_fds'] && $plan['costo_12horas']) {
                        $cost_by_duration = is_weekend($booking_day_of_week) ?
                            $plan['costo_12horas_fds'] + ($horas_extras * get_field('costo_de_hora_adicional_fs', $product->id)) :
                            $plan['costo_12horas'] + ($horas_extras * get_field('costo_de_hora_adicional', $product->id));
                    }
                } 
                if ($booking_duration >= 14) {
                    if ($plan['dia_hotelero_fds'] && $plan['dia_hotelero']) {
                        $cost_by_duration = is_weekend($booking_day_of_week) ?
                            $plan['dia_hotelero_fds'] :
                            $plan['dia_hotelero'];
                    }
                }
            }
        }
    }

    $total_booking_cost = $cost_by_duration + $cost_total_aditional_person + $cost_decoration;

    return $total_booking_cost;
}

/**
 * Retorna true si el día de la semana es sábado o viernes
 */
function is_weekend($booking_day_of_week)
{
    return $booking_day_of_week === 'Saturday' || $booking_day_of_week === 'Friday';
}