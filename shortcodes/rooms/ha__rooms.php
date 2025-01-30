<?php
if (!function_exists('ha_rooms_func')) {
  add_shortcode('ha_rooms', 'ha_rooms_func');

  function ha_rooms_func()
  {
    wp_enqueue_style('ha-rooms-shortcode', get_stylesheet_directory_uri() . '/shortcodes/rooms/ha_rooms.css', array(), '1.0');

    $rooms = get_field('habitaciones');
    $planType = get_field('tipo_de_plan');
    $slug = get_post_field('post_name');
    $html = "";
    $html .= "<div class=ha__rooms>";
    foreach ($rooms as $room) {
      $roomID = $room->ID;
      $title = $room->post_title;
      $img = get_the_post_thumbnail($roomID, 'large', array('class' => 'ha__room-image'));
      $url = get_the_permalink($roomID);
      $html .= "
        <div class=ha__room>
          <a class='ha__room-imaage-link' href='$url?plan=$slug&type=$planType'>
            $img
          </a>
          <div class=ha__room-title><a class='ha__room-link' href='$url?plan=$slug&type=$planType'>$title</a></div>
        </div>
      ";
    }
    $html .= "</div>";
    return $html;
  }
}
