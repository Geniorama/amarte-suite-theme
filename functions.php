<?php

/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

define('HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0');

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles()
{

    wp_enqueue_style(
        'hello-elementor-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        [
            'hello-elementor-theme-style',
        ],
        HELLO_ELEMENTOR_CHILD_VERSION
    );
}
add_action('wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20);

/**
 * Agregando Scripts personalizados
 */
function hz_custom_scripts()
{
    // Registrar el archivo de JavaScript
    wp_enqueue_script('hz_custom_script', get_stylesheet_directory_uri() . '/inc/js/main.js', array('jquery'), '1.0', true);

    if (is_product()) {
        // JavaScript para el carousel
        wp_enqueue_script('hz_carousel_script', get_stylesheet_directory_uri() . '/shortcodes/carousel/carousel-features-slick.js', array('jquery'), '1.0', true);

        // JavaScript para banner 360
        wp_enqueue_script('hz_banner_360_script', get_stylesheet_directory_uri() . '/shortcodes/banner360/banner-360.js', array('jquery'), '1.0', true);

        // JavaScript para manipular el DOM del single product para cuando se trata de un Plan
        wp_enqueue_script('ha__single_product', get_stylesheet_directory_uri() . '/inc/js/single-produc.js', array('jquery'), '1.0', true);
    }
}
add_action('wp_enqueue_scripts', 'hz_custom_scripts');


/**
 * Agregando hoja de estilos personalizados
 */
function hz_enqueue_styles()
{
    if (is_product()) {
        // Estilos para el carousel del single product
        wp_enqueue_style(
            'hz_carousel_style',
            get_stylesheet_directory_uri() . '/shortcodes/carousel/carousel-features-styles.css',
            array(),
            wp_get_theme()->get('1.0')
        );

        // Estilos para el banner 360 del single product
        wp_enqueue_style(
            'hz_banner360_style',
            get_stylesheet_directory_uri() . '/shortcodes/banner360/cover-360.css',
            array(),
            wp_get_theme()->get('1.0')
        );
    }
    if (is_single() || is_product()) {
        // Estilos para el grid del single product
        wp_enqueue_style(
            'hz_grid_style',
            get_stylesheet_directory_uri() . '/shortcodes/grid/grid-features-styles.css',
            array(),
            wp_get_theme()->get('1.0')
        );
    }
}
add_action('wp_enqueue_scripts', 'hz_enqueue_styles');

/**
 * Reemplaza el script wc-bookings-booking-form del plugin WooCommerce Bookings por uno personalizado
 */
function ha_dequeue_wc_bookings_script()
{
    wp_dequeue_script('wc-bookings-booking-form');

    wp_enqueue_script('wc-bookings-booking-form', get_stylesheet_directory_uri() . '/woocommerce-bookings/dist/frontend.js', array('jquery', 'jquery-blockui', 'jquery-ui-datepicker', 'underscore'), WC_BOOKINGS_VERSION, true);
}
add_action('wp_enqueue_scripts', 'ha_dequeue_wc_bookings_script', 100);

////////////////////////////////////////////////////////////////////////////
//////////// Short Codes
////////////////////////////////////////////////////////////////////////////
require get_stylesheet_directory() . '/shortcodes/carousel/hz-carousel-features.php'; //shortcode para el carousel
require get_stylesheet_directory() . '/shortcodes/banner360/banner-360.php'; //shortcode para la vista 360
require get_stylesheet_directory() . '/shortcodes/grid/grid-features.php'; //shortcode para el grid
require get_stylesheet_directory() . '/shortcodes/price/ha__price.php'; //Imprime el precio de habitaciones según bloque de horas
require get_stylesheet_directory() . '/shortcodes/rooms/ha__rooms.php'; //Imprime listado de habitaciones asignadas al plan

////////////////////////////////////////////////////////////////////////////
//////////// Custom Posts Type
////////////////////////////////////////////////////////////////////////////
require get_stylesheet_directory() . '/cpt/ha__planes_paquetes.php'; //Custom Post Type para agregar los paquetes y planes
require get_stylesheet_directory() . '/cpt/hs__hotel_settings.php'; //Custom Post Type para agregar configuraciones generales

////////////////////////////////////////////////////////////////////////////
//////////// Custom Fields
////////////////////////////////////////////////////////////////////////////
require get_stylesheet_directory() . '/custom-fields/ha__caracteristicas_habitaciones.php'; //Agrega campos para las características de las habitaciones
require get_stylesheet_directory() . '/custom-fields/ha__costo_bloque_horas.php'; //Agrega los bloques de horas para anotar su precio
require get_stylesheet_directory() . '/custom-fields/ha__tooltip_decoracion.php'; //Agrega campo para dejar la descripción tooltip para la decoración
require get_stylesheet_directory() . '/custom-fields/ha__vista_360.php'; //Agrega campo para insertar el shortcode de la vista 360°

////////////////////////////////////////////////////////////////////////////
//////////// SNIPPETS
////////////////////////////////////////////////////////////////////////////
require get_stylesheet_directory() . '/snippets/custom-button-add-to-cart.php'; //Agrega un botón personalizado al carrito
require get_stylesheet_directory() . '/snippets/decoration-single-product.php'; //Modifica la sección de decoración en el formulario de reservas
require get_stylesheet_directory() . '/snippets/room-discounts.php'; //Aplica un descuento a las habitaciones seleccionadas en las configuraciones generales
require get_stylesheet_directory() . '/snippets/custom-hours-additional-field.php'; //Agrega un campo de horas adicionales al formulario de reservas

/**
 * Removiendo los hooks que no son necesarios
 */
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20); // Remover hook de las migas de pan (breadcrumb)
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5); // Remover title
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10); // Remover rating
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10); // Remover price
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20); // Remover excerpt
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40); // Remover meta descripción - categorías
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20); // Remover imagen destacada
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10); // Remover tabs
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20); // Remover productos relacionados

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

/**
 * Una función para reordenar la visualización predeterminada 
 * de los campos en el formulario de reservas de WooCommerce.
 */
function custom_order_booking_fields($fields)
{

    $reorder  = array();
    $reorder[] = $fields['wc_bookings_field_start_date'];  // Calendar or Start Date
    $reorder[] = $fields['wc_bookings_field_duration'];  // Duration
    $reorder[] = $fields['wc_bookings_field_persons'];  // Persons

    return $reorder;
}
add_filter('booking_form_fields', 'custom_order_booking_fields');

/**
 * Hook para solucionar un bug al recibir la información de la habitación a reservar
 * Lo que hace es remover el hook original y agregar uno personalizado
 * para que se pueda obtener la información de la habitación a reservar
 * en el formulario de reservas
 */
function hz_custom_uxper_check_update_cart()
{
    if (is_cart() || is_checkout()) {
        global $woocommerce, $product;
        $i = $total_quantity = 0;

        // Loop de todos los productos en el carrito
        foreach ($woocommerce->cart->cart_contents as $product) {
            $reservation_data = get_transient("uxper_ux_room_product_reservation_data_{$product['product_id']}");
            if (!empty($reservation_data)) {
                $room_id   = $reservation_data['room_id'];
                $check_in  = $reservation_data['check_in'];
                $check_out = $reservation_data['check_out'];
            }

            $num_name       = get_post_meta($product['product_id'], 'uxper_room_num_name_json', true);
            $num_name_index = array();
            if (is_array($num_name) || is_object($num_name)) {
                foreach ($num_name as $key => $value) {
                    $num_name_index[] = $key;
                }
            }

            if (!empty($check_in) && !empty($check_out)) {
                $unavailable_num_name = Uxper_Room::get_unavailable_num_name($room_id, $check_in, $check_out);
                $available_num_name   = array_values(array_diff($num_name_index, $unavailable_num_name));
            }

            // Set minimum product cart total
            $minimum_cart_product_total = 0;

            if (!empty($available_num_name)) {
                // See if any product is from the bagel category or not
                if (has_term(count($available_num_name), 'product_cat', $product['product_id'])) {
                    $total_quantity += $product['quantity'];
                }
            }
        }

        foreach ($woocommerce->cart->cart_contents as $product) {
            if (!empty($available_num_name)) {
                if (has_term(intval($available_num_name), 'product_cat', $product['product_id'])) {
                    if ($total_quantity < $minimum_cart_product_total && $i == 0) {
                        // Display our error message
                        wc_add_notice(
                            sprintf(
                                '<strong>A Minimum of %s products is required from bagel category before checking out.</strong>'
                                    . '<br />Current number of items in the cart: %s.',
                                $minimum_cart_product_total,
                                $total_quantity
                            ),
                            'error'
                        );
                    }
                    $i++;
                }
            }
        }
    }
}
remove_action('woocommerce_check_cart_items', 'uxper_check_update_cart');
add_action('woocommerce_check_cart_items', 'hz_custom_uxper_check_update_cart');

/**
 * Redirigir al checkout después de agregar un producto al carrito
 */
add_filter('add_to_cart_redirect', 'hz_add_to_cart_redirect');
function hz_add_to_cart_redirect()
{
    global $woocommerce;
    $redirect_checkout = $woocommerce->cart->get_checkout_url();
    return $redirect_checkout;
}

/**
 * Modifica la hora final, para dejar un espacio disponible de una hora entre las reservas
 */
function my_custom_checkout_order_review()
{
    ?>
    <script>
        jQuery(document).ready(function($) {
            setTimeout(function() {
                const duracionElement = $("dd.variation-Duracin p");
                // Obtener el texto actual y convertirlo a un número de horas
                const duracionActual = duracionElement.text().trim(); // "9 horas"
                const horas = parseInt(duracionActual); // Convertir "9 horas" a 9
                // Restar una hora al valor actual
                const horasRestadas = horas - 1;
                // Actualizar el contenido del elemento <dd> con el nuevo valor
                duracionElement.text(horasRestadas + " horas"); // Actualizar el texto a la nueva duración
            }, 5000);
        });
    </script>
<?php
}
add_action('woocommerce_checkout_order_review', 'my_custom_checkout_order_review');

/**
 * Agrega un campo nuevo al booking form para almacenar el valor 'type' obtenido desde el parámetro de la url
 * Este campo se utiliza para identificar si el producto es un plan o un paquete
 * Este campo es tipo hidden por lo que no se visualiza en el formulario, 
 * pero se envía en el POST al agregar el producto al carrito
 * de modo que en el hook 'woocommerce_bookings_calculated_booking_cost' se pueda obtener su valor
 * y de esta forma aplicar el precio correspondiente al producto cuando se trata de un plan y 
 * según el tipo de plan seleccionado
 */
add_action('woocommerce_before_booking_form', 'ha_add_booking_form_type_plan_field');
function ha_add_booking_form_type_plan_field()
{
    $typePlan = obtener_parametro_url('type');
    if ($typePlan) echo '<input type="hidden" class="input-text" name="ha_type_plan_field" id="ha_type_plan_field" value="' . $typePlan . '"/>';
    return;
}

/**
 * Obtiene el valor de un parámetro de la url
 * Se envía el nombre del parámetro y retorna su valor
 * Si el parámetro no está presente, retorna false
 */
function obtener_parametro_url($parametro)
{
    // Verificar si el parámetro está presente en la URL actual
    if (isset($_GET[$parametro])) {
        return sanitize_text_field($_GET[$parametro]);
    }
    return false;
}