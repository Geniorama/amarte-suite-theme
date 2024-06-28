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
    // wp_register_script('hz_custom_script', get_stylesheet_directory_uri() . '/inc/js/main.js', array('jquery'), '1.0', true);
    wp_enqueue_script('hz_custom_script', get_stylesheet_directory_uri() . '/inc/js/main.js', array('jquery'), '1.0', true);

    if (is_product()) {
        // JavaScript para el carousel
        wp_enqueue_script('hz_carousel_script', get_stylesheet_directory_uri() . '/shortcodes/carousel/carousel-features-slick.js', array('jquery'), '1.0', true);

        // JavaScript para banner 360
        wp_enqueue_script('hz_banner_360_script', get_stylesheet_directory_uri() . '/shortcodes/banner360/banner-360.js', array('jquery'), '1.0', true);
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
require get_stylesheet_directory() . '/custom-fields/hs__fields_planes.php'; //Agrega campos personalizados al post type de planes
require get_stylesheet_directory() . '/custom-fields/hs__configuraciones_generales.php'; //Agrega campos personalizados para las configuraciones del hotel


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
// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30); // Remover Botón de agregar al carrito

// Agregar botón personalizado en el lugar del botón original
function agregar_boton_personalizado_al_carrito()
{
    global $product;

    if (!$product->is_in_stock()) {
        return;
    }

    // Personaliza aquí tu botón de compra. Puede ser un enlace, un botón, etc.
?>
    <div class="btn-woocommerce-booking">
        <button type="button" role="button" class="hz-button hz-book-pay disabled" id="hz-open-modal">
            <span>Reservar</span>
            <i class="fa-solid fa-arrow-right"></i>
        </button>
    </div>
<?php
}
add_action('woocommerce_single_product_summary', 'agregar_boton_personalizado_al_carrito', 30);


/**
 * Ventana modal en el single product
 * Este pop up es cargado con jQuery, captando los valores del DOM para imprimirlos en un pop up
 */
function hz_modal_popup_confirmar_reserva()
{
?>
    <!-- Modal window -->
    <script>
        // Script para obtener el valor seleccionado y mostrarlo en el span
        jQuery(document).ready(function($) {
            // Función para obtener el valor seleccionado y mostrarlo en el span
            function agregarModalEnForm() {

                const htmlModal = '<div class="hz-modal-booking" id="hz-modal-booking"><div class="hz-modal-booking__content"><div class="hz-modal-header"><h4 class="hz-modal-title">Confirma tu Reserva</h4><div class="hz-modal-close"><span class="hz-line"></span><span class="hz-line"></span></div></div><div class="hz-modal-body"><h5 class="hz-modal-package"><span id="package__name" class="hz-modal-package__name"></span></h5><ul class="hz-modal-details-booking"><li><b>Suite: </b><span id="nombre__habitacion"></span></li><li><b>Fecha reservada:</b> <span id="fecha__modal"></span></li><li><b>Hora inicio:</b> <span id="hora__inicio"></span></li><li><b>Hora fin:</b> <span id="hora__final"></span></li><li><b>Servicios adicionales: </b><span id="decoracion__modal"></span></li><li><b>Personas adicionales: </b><span id="personas__modal"></span></li></ul><p class="hz-modal-total">Total: <span id="costo__modal" class="hz-modal-total__price"></span></p></div><div class="hz-modal-actions"><div class="hz-button hz-book-cancel">Cancelar</div><button type="submit" class="hz-button hz-book-pay alt">Confirmar</button></div></div></div>';
                const elementoHTML = $('#wc-bookings-booking-form');
                elementoHTML.append(htmlModal);
            }

            agregarModalEnForm();

            function imprimirValoresEnModal() {

                // Obtener el valor de la hora de finalización
                const horaFinal = $('#wc-bookings-form-end-time').find('option:selected').data('value');


                const formatoHoraFinal = new Date(horaFinal);

                // Obtener el valor seleccionado del select de hora de inicio
                const horaSeleccionada = $('#wc-bookings-form-start-time').val();

                // Obetener el nombre del paquete seleccionado
                const package = $('#wc-bookings-form-end-time');
                const packElementModal = $('#package__name');
                const paqueteSeleccionado = package[0].options[package[0].selectedIndex].textContent;
                packElementModal.text(paqueteSeleccionado);

                // Obetener el título de la habitación
                const titulo = $('.hz-product-banner-top__title').text();
                const tituloElementModal = $('#nombre__habitacion');
                tituloElementModal.text(titulo);

                // Obetener el tipo de decoracion de la habitación
                const decoracion = $('#wapo-total-options-price').text();
                const decoracionElementModal = $('#decoracion__modal');
                decoracionElementModal.text(decoracion);

                // Obtener los valores de las etiquetas del total y decoración para sumarlas y obtener el precio de la reservación
                const totaldecoracion = $("#wapo-total-options-price").text().replace("$", "").replace(".", "");
                const totalreserva = $(".woocommerce-Price-amount bdi").text().replace("$", "").replace(".", "");
                const numerodecoracion = parseFloat(totaldecoracion);
                const numerototal = parseFloat(totalreserva);
                const resultadosuma = numerodecoracion + numerototal;
                const resultadoFinal = parseInt(resultadosuma).toLocaleString('es');

                // Obetener el precio de la reservación
                const costo = resultadoFinal + ' <small class="hz-text-small">(impuestos incluidos)</small>';
                const costoElementModal = $('#costo__modal');
                costoElementModal.html("$" + costo);

                // Obetener el número de personas adicionales
                const personas = $('#wc_bookings_field_persons').val();
                const personasElementModal = $('#personas__modal');
                personasElementModal.html(personas);


                // Formato 1: 6 febrero de 2023
                const formatoFecha = new Date(horaSeleccionada).toLocaleDateString('es-ES', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
                // Formato 2: 8:00 a.m.
                const formatoHora = new Date(horaSeleccionada).toLocaleTimeString('en-US', {
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: true
                });
                // Mostrar el valor en los dos formatos
                const fechaElementModal = $('#fecha__modal');
                const horaInicioElementModal = $('#hora__inicio');
                fechaElementModal.text(formatoFecha);
                horaInicioElementModal.text(formatoHora);



                // Restar una hora (3600000 milisegundos)
                const horasExtras = $('#wc_bookings_field_hour').val();
                const milisegundosExtras = horasExtras * 3600000;
                const horaRestada = new Date(formatoHoraFinal.getTime() - 3600000 + milisegundosExtras);
                const formatoHoraRestada = horaRestada.toLocaleTimeString('en-US', {
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: true
                });
                const horaFinalElementModal = $('#hora__final');
                horaFinalElementModal.text(formatoHoraRestada);
            }

            // Evento change en el select
            $('#wc-bookings-form-end-time').on('change', function() {
                imprimirValoresEnModal();
            });

            // Evento click en el botón
            $('#hz-open-modal').on('click', function() {
                imprimirValoresEnModal();
                // Agregar aquí el código que deseas ejecutar cuando se hace clic en el botón
                // Por ejemplo, podrías abrir un modal o realizar una acción específica
            });
        });
    </script>
<?php
}
add_action('woocommerce_single_product_summary', 'hz_modal_popup_confirmar_reserva', 50);

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
    if ($booking_day_of_week === 'Saturday' || $booking_day_of_week === 'Friday') {
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

    $total_booking_cost = $cost_by_duration + $cost_total_aditional_person + $cost_decoration;

    if (isset($_POST['ha_type_plan_field'])) {
        $custom_plan = sanitize_text_field($_POST['ha_type_plan_field']);

        // Verificar el valor y ajustar el costo
        if ($custom_plan === 'plan1') {
            $total_booking_cost = 98765;
        }
    }

    return $total_booking_cost;
}


/**
 * Filtra las horas de salida para los paquetes de 4, 6, 12 y día hotelero
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
        foreach ($data as $booking_data) {
            $display  = $booking_data['display'];
            $end_time = $booking_data['end_time'];
            $duration = $booking_data['duration'];

            if ($duration === 5 || $duration === 9 || $duration === 13 ||  (strpos(get_time_as_iso8601($end_time), 'T13:00:00') !== false && $duration >= 14)) {
                $package_name = '';
                $package_price = '';

                // Asignar el nombre del paquete
                if ($duration === 5) {
                    $package_name = 'Pack 4 horas';
                } elseif ($duration === 9) {
                    $package_name = 'Pack 8 horas';
                } elseif ($duration === 13) {
                    $package_name = 'Pack 12 horas';
                } elseif (strpos(get_time_as_iso8601($end_time), 'T13:00:00') !== false && $duration >= 14) {
                    $package_name = 'Día hotelero';
                }

                if (validarBloquePrecio($duration,  $product->id) || $duration === 9 || $duration === 13 || (strpos(get_time_as_iso8601($end_time), 'T13:00:00') !== false && $duration >= 14)) {
                    $block_html .= '<option data-duration-display="' . esc_attr($display) . '" data-value="' . get_time_as_iso8601($end_time) . '" value="' . esc_attr($duration) . '">' . $package_name . '</option>';
                }
            }
        }
        $block_html .= '</select>';
    }

    $block_html .= '</div>';

    return $block_html;
}

function validarBloquePrecio($duration, $productID)
{
    if (get_field('costo_de_4_horas', $productID) && get_field('costo_de_4_horas_fs', $productID)) {
        return $duration === 5;
    }
    return false;
}
/**
 * Agregar input de horas adicionales con sus funcionalidades
 */
add_action('wp_footer', 'hz_custom_script');
function hz_custom_script()
{
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var initialPackageValue = $('#wc-bookings-form-end-time').val();
            var initialExtraHoursValue = $('#wc_bookings_field_hour').val();
            var extraHoursFieldVisible = initialPackageValue === '5' || initialPackageValue === '7' || initialPackageValue === '13';
            var programmaticChange = false;
            var package5Text = 'Pack 4 horas';
            var package7Text = 'Pack 8 horas';
            var package13Text = 'Pack 12 horas';

            $('.woocommerce-content form.cart').keypress(function(event) {
                if (event.key === 'Enter' || event.code === 'Enter') {
                    event.preventDefault();
                }
            });

            var htmlhorasExtras = '<div class="hz-form-field hz-extra-hour" style="display: none;" id="extra_hours_field"><p class="hz-additional__hour"><label class="hz-label" for="wc_bookings_field_hour"><strong>Hora(s) adicinal(es) </strong><i style="font-weight: 500;">(opcional)</i>:</label><input type="number" class="hz-input wc_bookings_field_hour" value="0" step="1" min="0" max="8" name="wc_bookings_field_hour" id="wc_bookings_field_hour"></p></div>';
            $('.woocommerce-content form.cart').append(htmlhorasExtras);

            $(document).on('change', '#wc-bookings-form-end-time', function() {
                if (programmaticChange) {
                    programmaticChange = false;
                    return;
                }

                var selectedOption = $(this).find('option:selected');
                var duration = selectedOption.val();
                if (duration === '5' || duration === '7' || duration === '13') {
                    $('#extra_hours_field').show();
                    extraHoursFieldVisible = true;
                    if ($(window).width() > 1024) {
                        $('.hz-number-person').css('margin-top', '-6.6rem');
                        $('#yith-wapo-container').css('margin-top', '-29.1rem');
                    } else {
                        $('.hz-number-person').css('margin-top', '7rem');
                        $('#yith-wapo-container').css('margin-top', '0rem');
                    }
                } else {
                    $('#extra_hours_field').hide();
                    extraHoursFieldVisible = false;
                    if ($(window).width() > 1024) {
                        $('.hz-number-person').css('margin-top', '-12rem');
                        $('#yith-wapo-container').css('margin-top', '-23.7rem');
                    } else {
                        $('.hz-number-person').css('margin-top', '0');
                    }
                }

                // Reset the value of the package of 5 hours
                $('#wc-bookings-form-end-time option').filter(function() {
                    return $(this).text() === package5Text;
                }).val('5');

                // Reset the value of the package of 7 hours
                $('#wc-bookings-form-end-time option').filter(function() {
                    return $(this).text() === package7Text;
                }).val('7');

                // Reset the value of the package of 13 hours
                $('#wc-bookings-form-end-time option').filter(function() {
                    return $(this).text() === package13Text;
                }).val('13');

                // Reset the extra hours field to its initial value
                // $('#wc_bookings_field_hour').val(initialExtraHoursValue);
                $('#wc_bookings_field_hour').val(0);
            });

            $(document).on('change', '#wc_bookings_field_hour', function() {
                var extraHours = $(this).val();
                var selectedOption = $('#wc-bookings-form-end-time').find('option:selected');
                var duration = parseInt(selectedOption.val());
                selectedOption.val(duration + parseInt(extraHours));
                programmaticChange = true;
                $('#wc-bookings-form-end-time').trigger('change');

                // Reset the value of the package of 5 hours
                $('#wc-bookings-form-end-time option').filter(function() {
                    return $(this).text() === package5Text;
                }).val('5');

                // Reset the value of the package of 7 hours
                $('#wc-bookings-form-end-time option').filter(function() {
                    return $(this).text() === package7Text;
                }).val('7');

                // Reset the value of the package of 13 hours
                $('#wc-bookings-form-end-time option').filter(function() {
                    return $(this).text() === package13Text;
                }).val('13');
            });

            // Reset the initial values when the page loads
            $('#wc-bookings-form-end-time').val(initialPackageValue).trigger('change');
        });
    </script>
    <?php
}


/**
 * Una función para reordenar la visualización predeterminada 
 * de los campos en el formulario de reservas de WooCommerce.
 * Ponga esta función en el archivo functions.php de su tema
 */
function custom_order_booking_fields($fields)
{

    $reorder  = array();
    $reorder[] = $fields['wc_bookings_field_start_date'];  // Calendar or Start Date
    $reorder[] = $fields['wc_bookings_field_duration'];  // Duration
    $reorder[] = $fields['wc_bookings_field_persons'];  // Persons
    //$reorder[] = $fields['wc_bookings_field_resource'];  // Resource

    return $reorder;
}
add_filter('booking_form_fields', 'custom_order_booking_fields');

/**
 * Hook para solucionar un bug al recibir la información de la habitación a reservar
 */
function hz_custom_uxper_check_update_cart()
{
    // Only run in the Cart or Checkout pages
    if (is_cart() || is_checkout()) {
        global $woocommerce, $product;
        $i = $total_quantity = 0;

        // Loop through all cart products
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

// Redirigir al checkout después de agregar un producto al carrito
add_filter('add_to_cart_redirect', 'hz_add_to_cart_redirect');
function hz_add_to_cart_redirect()
{
    global $woocommerce;
    $redirect_checkout = $woocommerce->cart->get_checkout_url();
    return $redirect_checkout;
}


/**
 * Shortcode para el banner del single product
 */
if (!function_exists('hz_product_banner_top_func')) {

    add_shortcode('hz_product_banner_top', 'hz_product_banner_top_func');
    function hz_product_banner_top_func()
    {
        global $product;

        $product = wc_get_product(get_the_ID());

        if (is_product()) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'single-post-thumbnail');
            $title = get_the_title($product->get_id());
            $description = $product->get_short_description();

            ob_start(); ?>
            <div class="hz-product-banner-top">
                <img src="<?php echo $image[0]; ?>" alt="<?php echo $title; ?>" class="hz-product-banner-top__cover">
                <div class="container">
                    <div class="hz-product-banner-top__content">
                        <h3 class="hz-product-banner-top__title"><?php echo $title; ?></h3>
                        <p class="hz-product-banner-top__subtitle"><?php echo $description; ?></p>
                    </div>
                </div>
            </div>
    <?php return ob_get_clean();
        }
    }
}

/**
 * Modificar la hora final, para dejar un espacio disponible de una hora entre las reservas
 */
function my_custom_checkout_order_review()
{
    ?>
    <script>
        jQuery(document).ready(function($) {
            setTimeout(function() {
                const duracionElement = $("dd.variation-Duracin p");
                // Obtener el texto actual y convertirlo a un número de horas
                const duracionActual = duracionElement.text().trim(); // "7 horas"
                const horas = parseInt(duracionActual); // Convertir "7 horas" a 7
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
 * Modificando la sección de Decoración del formulario de reservas de habitación
 */
function hz_checkbox_decoration()
{
    global $post;
    $customTooltipText = '';
    $activarTooltip = false;

    if (is_product()) {
        $customTooltipText = get_post_meta($post->ID, 'texto_tooltip', true);
        $activarTooltip = get_post_meta($post->ID, 'activar_tooltip', true);
    }
?>
    <script>
        jQuery(document).ready(function($) {

            var customTooltipText = <?php echo json_encode($customTooltipText); ?>;
            var activarTooltip = <?php echo $activarTooltip; ?>;

            //Cambiar el texto del total de decoración
            $("#wapo-total-price-table .wapo-total-options th").html("+ Decoración:");

            //Cambiar de lugar el html de decoración
            const decorationcheckbox = $("#yith-wapo-container");
            const decorationcheckboxmoved = decorationcheckbox.detach();
            decorationcheckboxmoved.appendTo("#wc-bookings-booking-form");

            //Cambiar de lugar el html del total de reserva
            const formresult = $(".wc-bookings-booking-cost.hz-book-resume__totals.price");
            const formresultmoved = formresult.detach();
            $("#yith-wapo-container").after(formresultmoved);

            //Cambiar de lugar el html del total de decoración
            const decorationprice = $("#wapo-total-price-table");
            const decorationpricemoved = decorationprice.detach();
            $(".wc-bookings-booking-cost.hz-book-resume__totals.price").after(decorationpricemoved);

            //Agregar clases y estilos a la sección de Decoración del formulario

            $("#yith-wapo-addon-1").addClass("hz-form-field hz-decoration");

            $(".yith-wapo-option.selection-single .radio-button-container").addClass("hz-radio-option hz-radio-option-decoration");

            $(".yith-wapo-option.selection-single .radiobutton").addClass("hz-name-package");

            $(".yith-wapo-label small.option-price").css("display", "none");

            // Reemplazar imagen de item Sin Decoracion
            var imgSinDecoracion = $('#yith-wapo-option-1-0 .image img');
            var imgBannerHabitacion = $('.hz-product-banner-top .hz-product-banner-top__cover');
            var newSrc = imgBannerHabitacion.attr('src');
            imgSinDecoracion.attr('src', newSrc);

            // 	Agregar frase "(precio pareja)" al Coste de la reserva
            $('.wc_bookings_field_persons, .radiobutton').on('change', function() {
                var inputValuePersons = $('.wc_bookings_field_persons').val();
                setTimeout(() => {
                    if (inputValuePersons == 0) {
                        $('.hz-book-resume__totals strong').before('<span style="font-size: 16px;font-weight: 500;margin-left: -4px;margin-right: 6px;">(precio pareja)</span>');
                    }
                }, "1000");
            });

            //reemplazar el html del titulo de Personas Adicionales
            const personshtml = $(".hz-additional__person .hz-label");
            const personshtmlnew = `<div class="addon-header hz-label"><strong>Persona(s) adicional(es)</strong><i style="font-weight: 500;"> (opcional):</i><div class="hz-tooltip"><div class="hz-tooltip__info" style="display: none;"><p>El valor mostrado corresponde a la reserva para una pareja. Si deseas incluir más personas, agrégalas en este campo</p></div><i class="fa-solid fa-circle-question"></i></div></div>`;
            personshtml.replaceWith(personshtmlnew);

            //reemplazar el html del titulo de decoración
            const decorationhtml = $("#yith-wapo-addon-1 .addon-header");
            const destinationlabel = $("#yith-wapo-addon-1");

            if (activarTooltip) {
                const decorationhtmlnew = `<div class="addon-header hz-label"><strong>Decoración</strong><i style="font-weight: 500;"> (opcional)</i><div class="hz-tooltip"><div class="hz-tooltip__info" style="display: none;"><p>${customTooltipText}</p></div><i class="fa-solid fa-circle-question"></i></div></div>`;
                decorationhtml.replaceWith(decorationhtmlnew);
                decorationhtmlnew.appendTo(destinationlabel);
            } else {
                const decorationhtmlnew = `<div class="addon-header hz-label"><strong>Decoración</strong><i style="font-weight: 500;"> (opcional)</i></div>`;
                decorationhtml.replaceWith(decorationhtmlnew);
                decorationhtmlnew.appendTo(destinationlabel);
            }

        });
    </script>
<?php
}
add_action('woocommerce_single_product_summary', 'hz_checkbox_decoration', 50);


/**
 * Agregando la funcionalidad del tooltip de decoración
 * despues de que se modifica el html
 */
function hz_decoration_tooltips()
{
?>
    <script>
        jQuery(document).ready(function($) {

            // Obtener el tipo de decoracion 1 y agregandola dinamicamente al tooltip
            const decoracion1 = $('#yith-wapo-option-1-1 .yith-wapo-label .woocommerce-Price-amount.amount').text();
            const decoracion1ElementModal = $('.decoracion__1');
            decoracion1ElementModal.text(decoracion1);

            // Obtener el tipo de decoracion 2 y agregandola dinamicamente al tooltip
            const decoracion2 = $('#yith-wapo-option-1-2 .yith-wapo-label .woocommerce-Price-amount.amount').text();
            const decoracion2ElementModal = $('.decoracion__2');
            decoracion2ElementModal.text(decoracion2);

            // Agregando la interacción del tooltip
            const tooltips = document.querySelectorAll(".hz-tooltip");

            tooltips.forEach(function(i) {
                const icon = i.querySelector("i");
                const info = i.querySelector(".hz-tooltip__info");

                icon.addEventListener("click", function() {
                    if (info.style.display == "none") {
                        info.style.display = "block";
                    } else {
                        info.style.display = "none";
                    }
                });
            });

        });
    </script>
<?php
}
add_action('woocommerce_single_product_summary', 'hz_decoration_tooltips', 50);















/**
 * Aplica un porcentaje de descuento en el carrito y en el check out
 */
function hz_aplicar_descuento_total($cart)
{
    if (is_admin() && !defined('DOING_AJAX'))
        return;
    //Iniciando valores de fecha para aplicar descuentos
    $fecha_inicio_descuento = '12-02-2024';
    $fecha_fin_descuento = '16-02-2024';
    $porcentaje_descuento = 20;

    // Suponiendo que $cart es el objeto WC_Cart que proporcionaste

    // Accedemos al array cart_contents dentro del objeto WC_Cart
    $cart_contents = $cart->cart_contents;

    // Iteramos sobre los elementos del array cart_contents
    foreach ($cart_contents as $item) {
        // Accedemos al valor de "_date" dentro de cada elemento
        $fecha_de_reserva = $item['booking']['_date'];
        $fecha_de_reserva = date('d-m-Y', strtotime($fecha_de_reserva));
        $product_id = $item['product_id'];

        // Imprimimos la fecha
    }

    // Verificar si la habitación es tipo Deluxe
    $product_categories = wp_get_post_terms($product_id, 'product_cat');

    if (hz_tipo_habitación($product_categories)) $valorPorcentajeDescuento = hz_aplicar_descuento_en_rango_fecha($fecha_inicio_descuento, $fecha_fin_descuento, $fecha_de_reserva, $porcentaje_descuento);
    else $valorPorcentajeDescuento = 0;

    //Se valida que si exista un porcentaje de descuento para poder aplicarlo, sino lo hay, no se ejecuta nada
    if ($valorPorcentajeDescuento) {
        // Calculamos el descuento
        $descuento = $cart->subtotal * ($valorPorcentajeDescuento / 100);


        // Aplicamos el descuento
        $cart->add_fee(__('Descuento san valentin', 'woocommerce'), -$descuento);
    }
}
add_action('woocommerce_cart_calculate_fees', 'hz_aplicar_descuento_total');


/**
 * Retorna el valor del descuento a aplicar
 * si se cumple o no el rango de fecha
 */
function hz_aplicar_descuento_en_rango_fecha($fecha_inicio_descuento, $fecha_fin_descuento, $fecha_de_reserva, $porcentaje_descuento)
{
    // Convertir las fechas a objetos DateTime para facilitar la comparación
    $inicio_descuento = DateTime::createFromFormat('d-m-Y', $fecha_inicio_descuento);
    $fin_descuento = DateTime::createFromFormat('d-m-Y', $fecha_fin_descuento);
    $fecha_de_reserva = DateTime::createFromFormat('d-m-Y', $fecha_de_reserva);
    $fecha_de_reserva->setTime(0, 0, 0);
    $inicio_descuento->setTime(0, 0, 0);
    $fin_descuento->setTime(0, 0, 0);

    // Verificar si se crearon correctamente los objetos DateTime
    if ($inicio_descuento && $fin_descuento && $fecha_de_reserva) {
        // Comprobar si la fecha actual está dentro del rango de fechas del descuento
        if ($fecha_de_reserva >= $inicio_descuento && $fecha_de_reserva <= $fin_descuento) {
            return $porcentaje_descuento;
        } else {
            return 0;
        }
    }
    return 0;
}

/**
 * Valida si es una habitación tipo Delux
 */
function hz_tipo_habitación($product_categories)
{
    foreach ($product_categories as $category) {
        $category_slug = $category->slug;
        if ($category_slug === 'deluxe') return true;
    }
    return false;
}

/**
 * Obtiene el valor de un parámetro de la url
 */
function obtener_parametro_url($parametro)
{
    // Verificar si el parámetro está presente en la URL actual
    if (isset($_GET[$parametro])) {
        return sanitize_text_field($_GET[$parametro]);
    }
    return false;
}

/**
 * Agrega un campo nuevo al booking form para almacenar el valor 'type' obtenido desde el parámetro de la url
 */
add_action('woocommerce_before_booking_form', 'ha_add_booking_form_type_plan_field');
function ha_add_booking_form_type_plan_field()
{
    $typePlan = obtener_parametro_url('type');
    echo '<input type="hidden" class="input-text" name="ha_type_plan_field" id="ha_type_plan_field" value="' . $typePlan . '"/>';
}
