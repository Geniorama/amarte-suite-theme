<?php
function mdw_tabla_precios() {
  if (is_product()) {
    global $product;

    if ($product) {
      $paquete4 = get_field('costo_de_4_horas');
      $paquete8 = get_field('costo_de_6_horas');
      $paquete12 = get_field('costo_de_12_horas');
      $paquete24 = get_field('costo_de_24_horas');
      
      if(!$paquete4) mdw_add_class_to_table_price('dn-pack4');
      if(!$paquete8) mdw_add_class_to_table_price('dn-pack8');
      if(!$paquete12) mdw_add_class_to_table_price('dn-pack12');
      if(!$paquete24) mdw_add_class_to_table_price('dn-pack24');
    }
  }

      
}
add_action('wp_footer', 'mdw_tabla_precios');

function mdw_add_class_to_table_price($class) {

  echo "
    <script>
      window.addEventListener('load', function() {
        var elements = document.querySelectorAll('.{$class}');
       // Iterar sobre los elementos y eliminarlos
        elements.forEach(function(element) {
          element.remove();
        });
      });
    </script>
  ";
}