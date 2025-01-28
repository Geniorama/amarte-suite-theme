<?php 
/**
 * Agregar input de horas adicionales con sus funcionalidades
 * Este campo se muestra solo cuando se selecciona un paquete de 4, 8 o 12 horas
 * y permite al usuario agregar horas adicionales a su reserva
 * También se encarga de actualizar el valor del select de horas finales
 * para que refleje el valor total de horas seleccionadas
 * Además, se encarga de ocultar el campo de horas adicionales cuando no es necesario
 * y de reajustar el margen de los elementos en el formulario de reservas
 * para que no se superpongan
 * También se encarga de resetear los valores de los paquetes de 5, 9 y 13 horas
 * cuando se selecciona un paquete de 4, 8 o 12 horas
 * y de resetear el valor del campo de horas adicionales cuando se cambia el paquete
 */
add_action('wp_footer', 'hz_custom_script');
function hz_custom_script()
{
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var initialPackageValue = $('#wc-bookings-form-end-time').val();
            var initialExtraHoursValue = $('#wc_bookings_field_hour').val();
            var extraHoursFieldVisible = initialPackageValue === '5' || initialPackageValue === '9' || initialPackageValue === '13';
            var programmaticChange = false;
            var package5Text = 'Pack 4 horas';
            var package9Text = 'Pack 8 horas';
            var package13Text = 'Pack 12 horas';

            $('.woocommerce-content form.cart').keypress(function(event) {
                if (event.key === 'Enter' || event.code === 'Enter') {
                    event.preventDefault();
                }
            });

            var htmlhorasExtras = '<div class="hz-form-field hz-extra-hour" style="display: none;" id="extra_hours_field"><p class="hz-additional__hour"><label class="hz-label" for="wc_bookings_field_hour"><strong>Hora(s) adicional(es) </strong><i style="font-weight: 500;">(opcional)</i>:</label><input type="number" class="hz-input wc_bookings_field_hour" value="0" step="1" min="0" max="8" name="wc_bookings_field_hour" id="wc_bookings_field_hour"></p></div>';
            $('.woocommerce-content form.cart').append(htmlhorasExtras);

            $(document).on('change', '#wc-bookings-form-end-time', function() {
                if (programmaticChange) {
                    programmaticChange = false;
                    return;
                }

                var selectedOption = $(this).find('option:selected');
                var duration = selectedOption.val();
                if (duration === '5' || duration === '9' || duration === '13') {
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

                // Reset the value of the package of 9 hours
                $('#wc-bookings-form-end-time option').filter(function() {
                    return $(this).text() === package9Text;
                }).val('9');

                // Reset the value of the package of 13 hours
                $('#wc-bookings-form-end-time option').filter(function() {
                    return $(this).text() === package13Text;
                }).val('13');

                // Reset the extra hours field to its initial value
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

                // Reset the value of the package of 9 hours
                $('#wc-bookings-form-end-time option').filter(function() {
                    return $(this).text() === package9Text;
                }).val('9');

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