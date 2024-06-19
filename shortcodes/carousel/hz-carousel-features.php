<?php

// Include slick carousel
function incluir_slick_carousel_producto()
{
    if (is_product()) {
        // Enqueue los estilos CSS y scripts JS de Slick Carousel
        wp_enqueue_style('slick-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css');
        wp_enqueue_style('slick-theme', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css');
        wp_enqueue_script('slick-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', array('jquery'), '', true);
    }
}
add_action('wp_enqueue_scripts', 'incluir_slick_carousel_producto');
if (!function_exists('hz_carousel_features_func')) {
    add_shortcode('hz_carousel_features', 'hz_carousel_features_func');
    function hz_carousel_features_func()
    {
        if (is_product()) {
            $features = get_field('caracteristicas');
            ob_start();
            if ($features) :
?>
                <div class="hz-carousel-features slick-theme">
                    <?php foreach ($features as $feature) : ?>
                        <div>
                            <div class="hz-carousel-features__item">
                                <?php echo wp_get_attachment_image($feature['imagen']['ID'], 'thumbnail', false, array('class' => 'hz-carousel-features__img')); ?>
                                <p class="hz-carousel-features__name"><?php echo $feature['nombre'] ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
<?php
            endif;
            return ob_get_clean();
        }
    }
}
