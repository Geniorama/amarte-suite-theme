<?php
acf_add_local_field_group(array(
  'key' => 'group_650afb1f1c42a',
  'title' => 'Tooltip Decoración',
  'fields' => array(
    array(
      'key' => 'field_650afb472867e',
      'label' => 'Activar Tooltip de decoración',
      'name' => 'activar_tooltip',
      'type' => 'true_false',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'message' => '',
      'default_value' => 0,
      'ui' => 1,
      'ui_on_text' => '',
      'ui_off_text' => '',
    ),
    array(
      'key' => 'field_650afb872867f',
      'label' => 'Texto Tooltip',
      'name' => 'texto_tooltip',
      'type' => 'textarea',
      'instructions' => '',
      'required' => 1,
      'conditional_logic' => array(
        array(
          array(
            'field' => 'field_650afb472867e',
            'operator' => '==',
            'value' => '1',
          ),
        ),
      ),
      'wrapper' => array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'placeholder' => '',
      'maxlength' => '',
      'rows' => '',
      'new_lines' => '',
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
  'description' => '',
  'show_in_rest' => 0,
));
