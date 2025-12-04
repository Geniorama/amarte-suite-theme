<?php
/**
 * Admin new booking email
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Lógica original de introducción
if ( wc_booking_order_requires_confirmation( $booking->get_order() ) && $booking->get_status() === 'pending-confirmation' ) {
    $opening_paragraph = __( 'A booking has been made by %s and is awaiting your approval. The details of this booking are as follows:', 'woocommerce-bookings' );
} else {
    $opening_paragraph = __( 'A new booking has been made by %s. The details of this booking are as follows:', 'woocommerce-bookings' );
}

do_action( 'woocommerce_email_header', $email_heading, $email );

// --- 1. PREPARACIÓN Y CÁLCULOS MATEMÁTICOS ---
$order = $booking->get_order();
$first_name = '';
$last_name  = '';

// Inicializamos variables
$documento = 'No registrado';
$total_pagado = '';
$info_pago = '';
$mensaje_cliente = '';
$decoracion_html = '';

// Variables de tiempo calculadas
$fecha_checkin_real = '';
$fecha_checkout_real = '';
$texto_pack_calculado = 'Estándar';

if ( $order ) {
    $first_name = $order->get_billing_first_name();
    $last_name  = $order->get_billing_last_name();
    
    // Cédula
    $doc_temp = $order->get_meta( 'billing_cedula', true );
    if ( empty( $doc_temp ) ) $doc_temp = $order->get_meta( '_billing_cedula', true );
    if ( !empty($doc_temp) ) $documento = $doc_temp;

    // Financiero
    $total_pagado = $order->get_formatted_order_total();
    $id_pasarela  = $order->get_transaction_id();
    $metodo_pago  = $order->get_payment_method_title();
    $info_pago    = $id_pasarela ? $id_pasarela . ' (' . $metodo_pago . ')' : $metodo_pago;
    $mensaje_cliente = $order->get_customer_note();

    // --- LÓGICA DE TIEMPO Y PACKS ---
    
    // 1. Obtener Timestamps originales
    $ts_inicio = $booking->get_start(); // Timestamp entrada
    $ts_fin_sistema = $booking->get_end(); // Timestamp salida (con hora de limpieza incluida)

    // 2. APLICAR REGLA: Restar 1 hora (3600 segundos) por mantenimiento
    $ts_fin_real = $ts_fin_sistema - 3600;

    // 3. Formatear Fechas visuales (usando el formato de WP)
    $formato_fecha = wc_date_format() . ' ' . wc_time_format();
    $fecha_checkin_real = date_i18n( $formato_fecha, $ts_inicio );
    $fecha_checkout_real = date_i18n( $formato_fecha, $ts_fin_real ); // Mostramos la hora restada

    // 4. Calcular diferencia de horas REALES (sin la hora de limpieza)
    $segundos_diferencia = $ts_fin_real - $ts_inicio;
    $horas_totales = floor( $segundos_diferencia / 3600 ); // Redondear hacia abajo a horas enteras

    // 5. Determinar qué Pack es
    // Lógica: 4h, 8h, 12h, Día Hotelero (>18h aprox)
    
    $nombre_pack = '';
    $horas_base = 0;
    $horas_extra = 0;

    if ( $horas_totales >= 18 ) {
        // Asumimos que si pasa de 18 horas reales es un Día Hotelero (Pernocte)
        $nombre_pack = 'Día Hotelero';
        // Si necesitas calcular extras sobre el día hotelero, ajusta aquí.
    } elseif ( $horas_totales >= 12 ) {
        $nombre_pack = 'Pack 12 horas';
        $horas_base = 12;
    } elseif ( $horas_totales >= 8 ) {
        $nombre_pack = 'Pack 8 horas';
        $horas_base = 8;
    } else {
        // Por defecto Pack 4 horas (incluso si es menos, es el mínimo)
        $nombre_pack = 'Pack 4 horas';
        $horas_base = 4;
    }

    // 6. Calcular Extras
    if ( $nombre_pack !== 'Día Hotelero' && $horas_totales > $horas_base ) {
        $horas_extra = $horas_totales - $horas_base;
        $texto_pack_calculado = $nombre_pack . ' + ' . $horas_extra . ' hora(s) extra';
    } else {
        $texto_pack_calculado = $nombre_pack;
    }

    // --- LÓGICA DE DECORACIÓN (Adicionales) ---
    $detalles_temp = [];
    $items = $order->get_items();
    foreach ( $items as $item ) {
        if ( $item->get_product_id() == $booking->get_product_id() ) {
            $meta_data = $item->get_formatted_meta_data( '' );
            foreach ( $meta_data as $meta ) {
                if ( substr( $meta->key, 0, 1 ) !== '_' && $meta->key !== 'Booking ID' ) {
                    $detalles_temp[] = '<strong>' . $meta->display_key . ':</strong> ' . $meta->display_value;
                }
            }
            break;
        }
    }
    if (!empty($detalles_temp)) {
        $decoracion_html = implode('<br>', $detalles_temp);
    } else {
        $decoracion_html = 'Sin decoración / Estándar';
    }
}
?>

<?php if ( ! empty( $first_name ) && ! empty( $last_name ) ) : ?>
    <p><?php echo esc_html( sprintf( $opening_paragraph, $first_name . ' ' . $last_name ) ); ?></p>
<?php endif; ?>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee; font-family: sans-serif;" border="1" bordercolor="#eee">
    <tbody>
        
        <tr>
            <th scope="row" style="text-align:left; border: 1px solid #eee; background-color: #f7f7f7; width: 35%;">ID de reserva</th>
            <td style="text-align:left; border: 1px solid #eee;">#<?php echo esc_html( $booking->get_id() ); ?></td>
        </tr>

        <tr>
            <th scope="row" style="text-align:left; border: 1px solid #eee; background-color: #f7f7f7;">Nombre</th>
            <td style="text-align:left; border: 1px solid #eee;"><?php echo esc_html( $first_name . ' ' . $last_name ); ?></td>
        </tr>

        <tr>
            <th scope="row" style="text-align:left; border: 1px solid #eee; background-color: #f7f7f7;">Documento</th>
            <td style="text-align:left; border: 1px solid #eee;"><strong><?php echo esc_html( $documento ); ?></strong></td>
        </tr>

        <tr>
            <th scope="row" style="text-align:left; border: 1px solid #eee; background-color: #f7f7f7;">Email</th>
            <td style="text-align:left; border: 1px solid #eee;">
                <a href="mailto:<?php echo esc_attr( $order->get_billing_email() ); ?>"><?php echo esc_html( $order->get_billing_email() ); ?></a>
            </td>
        </tr>

        <tr>
            <th scope="row" style="text-align:left; border: 1px solid #eee; background-color: #f7f7f7;">Habitación</th>
            <td style="text-align:left; border: 1px solid #eee;">
                <?php wc_get_template( 'order/admin/booking-display.php', array( 'booking_ids' => [ $booking->get_id() ], 'only_title' => true ), 'woocommerce-bookings', WC_BOOKINGS_TEMPLATE_PATH ); ?>
            </td>
        </tr>

        <tr>
            <th scope="row" style="text-align:left; border: 1px solid #eee; background-color: #f7f7f7;">Check-in</th>
            <td style="text-align:left; border: 1px solid #eee;"><?php echo $fecha_checkin_real; ?></td>
        </tr>
        <tr>
            <th scope="row" style="text-align:left; border: 1px solid #eee; background-color: #f7f7f7;">Check-out</th>
            <td style="text-align:left; border: 1px solid #eee;">
                <?php echo $fecha_checkout_real; ?> 
            </td>
        </tr>

        <tr>
            <th scope="row" style="text-align:left; border: 1px solid #eee; background-color: #f7f7f7;">Pack de tiempo</th>
            <td style="text-align:left; border: 1px solid #eee; color: #d63384; font-weight: bold;">
                <?php echo esc_html( $texto_pack_calculado ); ?>
            </td>
        </tr>

        <tr>
            <th scope="row" style="text-align:left; border: 1px solid #eee; background-color: #f7f7f7;">Decoración</th>
            <td style="text-align:left; border: 1px solid #eee;">
                <?php echo $decoracion_html; ?>
            </td>
        </tr>

        <tr>
            <th scope="row" style="text-align:left; border: 1px solid #eee; background-color: #f7f7f7;">Valor pagado</th>
            <td style="text-align:left; border: 1px solid #eee;"><?php echo $total_pagado; ?></td>
        </tr>

        <tr>
            <th scope="row" style="text-align:left; border: 1px solid #eee; background-color: #f7f7f7;">ID pasarela</th>
            <td style="text-align:left; border: 1px solid #eee;"><?php echo esc_html( $info_pago ); ?></td>
        </tr>

        <tr>
            <th scope="row" style="text-align:left; border: 1px solid #eee; background-color: #f7f7f7;">Mensaje del cliente</th>
            <td style="text-align:left; border: 1px solid #eee;"><?php echo $mensaje_cliente ? esc_html( $mensaje_cliente ) : '-'; ?></td>
        </tr>
        
        <tr>
            <th scope="row" style="text-align:left; border: 1px solid #eee; background-color: #f7f7f7;">Teléfono</th>
            <td style="text-align:left; border: 1px solid #eee;">
                <a href="tel:<?php echo esc_attr( $order->get_billing_phone() ); ?>"><?php echo esc_html( $order->get_billing_phone() ); ?></a>
            </td>
        </tr>

    </tbody>
</table>

<?php if ( wc_booking_order_requires_confirmation( $booking->get_order() ) && $booking->get_status() === 'pending-confirmation' ) : ?>
<p><?php esc_html_e( 'This booking is awaiting your approval. Please check it and inform the customer if the date is available or not.', 'woocommerce-bookings' ); ?></p>
<?php endif; ?>

<p>
<?php
echo wp_kses_post( make_clickable( sprintf( __( 'You can view and edit this booking in the dashboard here: %s', 'woocommerce-bookings' ), admin_url( 'post.php?post=' . $booking->get_id() . '&action=edit' ) ) ) );
?>
</p>

<?php do_action( 'woocommerce_email_footer', $email ); ?>