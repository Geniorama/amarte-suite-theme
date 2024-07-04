<?php

/**
 * Shortcode para mostrar el precio del plan en función del tipo de plan y del atributo que corresponde al bloque de hora
 * shortcode de referencia [ha_price_plan bloque_horas='costo_4horas']
 */
if (!function_exists('ha_price_plan_func')) {
  add_shortcode('ha_price_plan', 'ha_price_plan_func');

  function ha_price_plan_func($atts)
  {
    // Definir los atributos aceptados y sus valores predeterminados
    $attributes = shortcode_atts(
      array(
        'bloque_horas'  => 'costo_4horas',
      ),
      $atts
    );
    $hotelSettings = get_page_by_path('configuraciones-generales', OBJECT, 'hotel_settings');
    $hotelSettingsID = $hotelSettings->ID;
    $planType = get_field('tipo_de_plan')['value'];
    $planes = get_field('configuraciones_planes', $hotelSettingsID)['planes'];
    foreach ($planes as $plan) {
      if ($plan['tipo_de_plan'] === $planType) {
        return $plan[$attributes['bloque_horas']];
      }
    }
    return;
  }
}
