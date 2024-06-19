<?php

if (!function_exists('hz_banner_bottom_360_func')) {
    add_shortcode('hz_banner_bottom_360', 'hz_banner_bottom_360_func');
    function hz_banner_bottom_360_func()
    {
        $banner360 = get_field('shortcode_360');
        $portada360 = get_field('portada_360');

        if ($banner360 && $portada360) {
            ob_start();
?>
            <div class="hz-modal-ipanorama" id="modal">
                <div class="hz-modal-ipanorama__wrap">
                    <button class="hz-modal-ipanorama__close">
                        <span></span>
                        <span></span>
                    </button>
                    <?php echo do_shortcode($banner360) ?>
                </div>
            </div>
            <div class="hz-cover-360" id="open-modal">
                <?php echo wp_get_attachment_image($portada360['ID'], 'full', false, array('class' => 'hz-cover-360__img')) ?>
                <p class="hz-cover-360__text">Ver 360°</p>
            </div>
<?php
            return ob_get_clean();
        }
    }
}
