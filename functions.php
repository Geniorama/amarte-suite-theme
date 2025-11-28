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
require get_stylesheet_directory() . '/shortcodes/carousel/hz-carousel-features.php'; //shortcode para el carousel single product
require get_stylesheet_directory() . '/shortcodes/banner360/banner-360.php'; //shortcode para la vista 360 single product
require get_stylesheet_directory() . '/shortcodes/grid/grid-features.php'; //shortcode para el grid single product
require get_stylesheet_directory() . '/shortcodes/price/ha__price.php'; //Imprime el precio de habitaciones según bloque de horas en el single product
require get_stylesheet_directory() . '/shortcodes/rooms/ha__rooms.php'; //Imprime listado de habitaciones asignadas al plan Planes y Paquetes

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
require get_stylesheet_directory() . '/snippets/list-hours-blocks-booking-form.php'; //Lista los bloques de horas en el formulario de reservas
require get_stylesheet_directory() . '/snippets/calculate-booking-cost.php'; //Calcula el costo de la reserva en función de la cantidad de horas seleccionadas
require get_stylesheet_directory() . '/snippets/mdw_tabla_precios.php'; //Snippet para mostrar u ocultar tabla de precios

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

/**
 * Agrega la clase hz-light-product en el body cuando en la url se encutra el att light
 */
add_filter( 'body_class', function( $classes ){
    if ( isset($_GET['light']) && $_GET['light'] == '1' && is_product() ) {
        $classes[] = 'hz-light-product';
    }
    return $classes;
});

/**
 * Add "Copy Product URLs" meta box in WooCommerce product editor.
 */
add_action('edit_form_after_editor', function($post) {
    if ($post->post_type !== 'product') return;

    $product_url       = get_permalink($post->ID);
    $product_light_url = $product_url . ( strpos($product_url, '?') === false ? '?light=1' : '&light=1' );

    ?>
    <div id="copy-urls-box" style="display:none; margin-top:15px; padding:12px; border:1px solid #ddd; background:#fafafa; border-radius:4px;">
        <strong>URL versión LIGHT:</strong>
        <input type="text" value="<?php echo esc_url($product_light_url); ?>" id="copy-product-light-url" readonly style="width:100%; margin-top:5px;">

        <button type="button" class="button copy-url-btn" data-target="copy-product-light-url" style="margin-top:5px; width:100%;">
            Copiar URL Light
        </button>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Mover el bloque justo debajo del permalink
        const permalinkRow = document.querySelector('#edit-slug-box');
        const box = document.querySelector('#copy-urls-box');

        if (permalinkRow && box) {
            permalinkRow.insertAdjacentElement('afterend', box);
            box.style.display = 'block';
        }

        // Función copiar
        document.querySelectorAll(".copy-url-btn").forEach(function(btn) {
            btn.addEventListener("click", function() {
                var input = document.getElementById(this.dataset.target);

                var temp = document.createElement("input");
                temp.type = "text";
                temp.value = input.value;

                document.body.appendChild(temp);
                temp.select();
                temp.setSelectionRange(0, 99999);

                document.execCommand("copy");
                temp.remove();
            });
        });
    });
    </script>
    <?php
});