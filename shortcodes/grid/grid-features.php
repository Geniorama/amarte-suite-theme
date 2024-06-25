<?php

if (!function_exists('hz_grid_features_func')) {
    add_shortcode('hz_grid_features', 'hz_grid_features_func');
    function hz_grid_features_func()
    {
        if (is_product() || is_single()) {
            $features = get_field('caracteristicas');
            ob_start();
            if ($features) :
?>
                <div class="ha-hotel-room-info">
                    <?php foreach ($features as $feature) : ?>
                        <div class="ha-room-info">
                            <img class="ha-icon" style="width: 30px;" src="<?php echo get_site_url(); ?>/wp-content/uploads/2021/08/ICONOS-1_ICONO-AMARTE.png">
                            <span class="ha-head"><?php echo $feature['nombre'] ?></span>
                            <span class="ha-tail"><?php echo $feature['informacion_adicional'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
<?php
            endif;
            return ob_get_clean();
        }
    }
}
