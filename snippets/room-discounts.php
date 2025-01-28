<?php
/**
 * Aplica un porcentaje de descuento en el carrito y en el check out
 */
function hz_aplicar_descuento_a_precios($cart)
{
    if (is_admin() && !defined('DOING_AJAX')) return;

    // Obtener configuraciones del descuento
    $hotelSettings = get_page_by_path('configuraciones-generales', OBJECT, 'hotel_settings');
    if (!$hotelSettings) return;

    $hotelSettingsID = $hotelSettings->ID;
    $config_descuento = get_field('configuraciones_descuentos', $hotelSettingsID);
    if (!$config_descuento || !$config_descuento['activar_descuento']) return;

    $fecha_inicio_descuento = $config_descuento['fecha_inicio_descuento'];
    $fecha_fin_descuento = $config_descuento['fecha_fin_descuento'];
    $porcentaje_descuento = $config_descuento['porcentaje_descuento'];
    $habitaciones_que_aplica = $config_descuento['habitaciones_que_aplica'];

    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        $fecha_de_reserva = isset($cart_item['booking']['_date']) ? $cart_item['booking']['_date'] : null;
        if (!$fecha_de_reserva) continue;

        $fecha_de_reserva = date('d-m-Y', strtotime($fecha_de_reserva));
        $product_id = $cart_item['product_id'];

        // Verificar si el producto aplica para el descuento
        if (mdw_habitacion_tiene_descuento($habitaciones_que_aplica, $product_id)) {
            // Al validar que si tiene descuento, se agrega el action que muestra el resumen de la compra con descuentos
            add_action('woocommerce_review_order_before_order_total', 'hz_mostrar_resumen_descuento');
            $valorPorcentajeDescuento = hz_aplicar_descuento_en_rango_fecha(
                $fecha_inicio_descuento,
                $fecha_fin_descuento,
                $fecha_de_reserva,
                $porcentaje_descuento
            );

            if ($valorPorcentajeDescuento) {
                // Calcular el nuevo precio
                $precio_actual = $cart_item['data']->get_price();
                $nuevo_precio = $precio_actual * (1 - $valorPorcentajeDescuento / 100);

                // Aplicar el nuevo precio al producto en el carrito
                $cart_item['data']->set_price($nuevo_precio);
            }
        }
    }
}
add_action('woocommerce_before_calculate_totals', 'hz_aplicar_descuento_a_precios');

/**
 * Muestra el resumen con descuentos cuando hay descuento
 */
function hz_mostrar_resumen_descuento()
{
    if (!WC()->cart) return; // Asegurarnos de que el carrito existe

    $cart = WC()->cart;
    $subtotal_original = 0;
    $descuento_total = 0;

    // Obtener configuraciones del descuento
    $hotelSettings = get_page_by_path('configuraciones-generales', OBJECT, 'hotel_settings');
    if (!$hotelSettings) return;

    $hotelSettingsID = $hotelSettings->ID;
    $config_descuento = get_field('configuraciones_descuentos', $hotelSettingsID);

    // Verificar si el descuento está activo
    if (!$config_descuento || !$config_descuento['activar_descuento']) return;

    // Obtener nombre y porcentaje del descuento
    $nombre_descuento = isset($config_descuento['nombre_descuento']) ? $config_descuento['nombre_descuento'] : __('Descuento aplicado', 'woocommerce');
    $porcentaje_descuento = isset($config_descuento['porcentaje_descuento']) ? $config_descuento['porcentaje_descuento'] : 0;

    foreach ($cart->get_cart() as $cart_item) {
        $precio_descuento = $cart_item['data']->get_price();
        $cantidad = $cart_item['quantity'];

        $subtotal_original += ($precio_descuento / (1 - $porcentaje_descuento / 100)) * $cantidad;
        $descuento_total += ($subtotal_original - $cart_item['data']->get_price()) * $cantidad;
    }

    // Mostrar subtotal original
    echo '<tr class="cart-subtotal-original">';
    echo '<th>' . __('Subtotal', 'woocommerce') . '</th>';
    echo '<td>' . wc_price($subtotal_original) . '</td>';
    echo '</tr>';

    // Mostrar mensaje en el checkout
    if ($porcentaje_descuento > 0) {
        $mensaje_descuento = sprintf('%s%%', $porcentaje_descuento);

        echo '<tr class="cart-discount custom-discount-row">';
        echo '<th>' . esc_html($nombre_descuento) . '</th>';
        echo '<td colspan="2">-' . wc_price($descuento_total) . '</td>';
        echo '</tr>';
    }
}

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
 * Valida si es una habitación tipo Delux,
 * esto con la intencón de aplicar un descuento para este tipo de habitaciones
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
 * Valida si la habitación aplica porcentaje de descuento,
 * se aplica el descuento solo para las habitaciones agregadas en las configuraciones
 */
function mdw_habitacion_tiene_descuento($habitaciones_que_aplica, $product_id)
{
    foreach ($habitaciones_que_aplica as $room_id) {
        if ($room_id == $product_id) return true;
    }
    return false;
}