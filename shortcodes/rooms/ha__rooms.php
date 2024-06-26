<?php
if (!function_exists('ha_rooms_func')) {
  add_shortcode('ha_rooms', 'ha_rooms_func');

  function ha_rooms_func()
  {
    $rooms = get_field('habitaciones');
    $planType = get_field('tipo_de_plan')['value'];
    $slug = get_post_field('post_name');
    $html = "";
    foreach ($rooms as $room) {
      $roomID = $room->ID;
      $title = $room->post_title;
      $img = get_the_post_thumbnail($roomID, 'medium', array('class' => 'ha__room-image'));
      $url = get_the_permalink($roomID);
      $html .= "
        <div class=ha__rooms>
          <div class=ha__room>
            $img
            <div class=ha__room-title><a class='ha__room-link' href='$url?plan=$slug&type=$planType'>$title</div>
          </div>
        </div>
      ";
    }
    return $html;
  }
}
