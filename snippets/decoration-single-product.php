<?php
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