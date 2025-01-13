<?php

/**
 * Customs Fields para agregar el costo de las habitaciones para los bloques de 
 * 6horas, 12horas y 24 horas, tanto para los días entre semana como para los fines de semana
 */
acf_add_local_field_group(array(
  'key' => 'group_64b0096158947',
  'title' => 'Costo por bloques de 4 - 8 - 12 - 24 horas desde Domingos a Jueves',
  'fields' => array(
    array(
      'key' => 'field_64b00a5148329',
      'label' => 'Costo de 4 horas',
      'name' => 'costo_de_4_horas',
      'type' => 'number',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '33',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'placeholder' => 'Paquete 4 horas',
      'prepend' => '$',
      'append' => '',
      'min' => 0,
      'max' => '',
      'step' => '',
    ),
    array(
      'key' => 'field_64b00a5148330',
      'label' => 'Costo de 8 horas',
      'name' => 'costo_de_6_horas',
      'type' => 'number',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '33',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'placeholder' => 'Paquete 8 horas',
      'prepend' => '$',
      'append' => '',
      'min' => 0,
      'max' => '',
      'step' => '',
    ),
    array(
      'key' => 'field_64b00b2248332',
      'label' => 'Costo de 12 horas',
      'name' => 'costo_de_12_horas',
      'type' => 'number',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '33',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'placeholder' => 'Paquete 12 horas',
      'prepend' => '$',
      'append' => '',
      'min' => 0,
      'max' => '',
      'step' => '',
    ),
    array(
      'key' => 'field_64b00b6848333',
      'label' => 'Costo de 24 horas',
      'name' => 'costo_de_24_horas',
      'type' => 'number',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '33',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'placeholder' => 'Paquete 24 horas',
      'prepend' => '$',
      'append' => '',
      'min' => 0,
      'max' => '',
      'step' => '',
    ),
    array(
      'key' => 'field_64b08b2dee2e9',
      'label' => 'Costo por persona adicional',
      'name' => 'costo_por_persona_adicional',
      'type' => 'number',
      'instructions' => '',
      'required' => 1,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '33',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'placeholder' => 'Costo por persona adicional',
      'prepend' => '$',
      'append' => '',
      'min' => 1,
      'max' => '',
      'step' => '',
    ),
    array(
      'key' => 'field_64ecde0bfbf28',
      'label' => 'Costo de hora adicional',
      'name' => 'costo_de_hora_adicional',
      'type' => 'number',
      'instructions' => '',
      'required' => 1,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '33',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'placeholder' => 'Costo por persona adicional',
      'prepend' => '$',
      'append' => '',
      'min' => 1,
      'max' => '',
      'step' => '',
    ),
  ),
  'location' => array(
    array(
      array(
        'param' => 'post_type',
        'operator' => '==',
        'value' => 'product',
      ),
    ),
  ),
  'menu_order' => 0,
  'position' => 'normal',
  'style' => 'default',
  'label_placement' => 'top',
  'instruction_placement' => 'label',
  'hide_on_screen' => '',
  'active' => true,
  'description' => 'Agregar el costo de la habitación por el bloque de horas de 6, 12 y 24 horas',
  'show_in_rest' => 0,
));

acf_add_local_field_group(array(
  'key' => 'group_64b07ce32c4ff',
  'title' => 'Costo por bloques de 4 - 6 - 12 - 24 horas desde Viernes a Sábados',
  'fields' => array(
    array(
      'key' => 'field_64b07ce337bdb',
      'label' => 'Costo de 4 horas',
      'name' => 'costo_de_4_horas_fs',
      'type' => 'number',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '33',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'placeholder' => 'Paquete 4 horas',
      'prepend' => '$',
      'append' => '',
      'min' => 0,
      'max' => '',
      'step' => '',
    ),
    array(
      'key' => 'field_64b07ce337bda',
      'label' => 'Costo de 8 horas',
      'name' => 'costo_de_6_horas_fs',
      'type' => 'number',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '33',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'placeholder' => 'Paquete 8 horas',
      'prepend' => '$',
      'append' => '',
      'min' => 0,
      'max' => '',
      'step' => '',
    ),
    array(
      'key' => 'field_64b07ce33b7c6',
      'label' => 'Costo de 12 horas',
      'name' => 'costo_de_12_horas_fs',
      'type' => 'number',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '33',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'placeholder' => 'Paquete 12 horas',
      'prepend' => '$',
      'append' => '',
      'min' => 0,
      'max' => '',
      'step' => '',
    ),
    array(
      'key' => 'field_64b07ce33f45f',
      'label' => 'Costo de 24 horas',
      'name' => 'costo_de_24_horas_fs',
      'type' => 'number',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '33',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'placeholder' => 'Paquete 24 horas',
      'prepend' => '$',
      'append' => '',
      'min' => 0,
      'max' => '',
      'step' => '',
    ),
    array(
      'key' => 'field_64b0860d30766',
      'label' => 'Costo por persona adicional',
      'name' => 'costo_por_persona_adicional_fs',
      'type' => 'number',
      'instructions' => '',
      'required' => 1,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '33',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'placeholder' => 'Costo por persona adicional',
      'prepend' => '$',
      'append' => '',
      'min' => 0,
      'max' => '',
      'step' => '',
    ),
    array(
      'key' => 'field_64ecde5faf67b',
      'label' => 'Costo de hora adicional',
      'name' => 'costo_de_hora_adicional_fs',
      'type' => 'number',
      'instructions' => '',
      'required' => 1,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '33',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'placeholder' => 'Costo por persona adicional',
      'prepend' => '$',
      'append' => '',
      'min' => 0,
      'max' => '',
      'step' => '',
    ),
  ),
  'location' => array(
    array(
      array(
        'param' => 'post_type',
        'operator' => '==',
        'value' => 'product',
      ),
    ),
  ),
  'menu_order' => 0,
  'position' => 'normal',
  'style' => 'default',
  'label_placement' => 'top',
  'instruction_placement' => 'label',
  'hide_on_screen' => '',
  'active' => true,
  'description' => 'Agregar el costo de la habitación por el bloque de horas de 6, 12 y 24 horas',
  'show_in_rest' => 0,
));
