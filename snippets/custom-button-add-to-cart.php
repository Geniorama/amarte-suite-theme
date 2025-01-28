<?php
/**
 * Agregar botón personalizado en el single product para reemplazar el botón de compra
 * Este botón tiene como funcionalidad abrir un modal con los datos de la reserva
 */
function agregar_boton_personalizado_al_carrito()
{
    global $product;

    if (!$product->is_in_stock()) {
        return;
    }
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
            });
        });
    </script>
<?php
}
add_action('woocommerce_single_product_summary', 'hz_modal_popup_confirmar_reserva', 50);