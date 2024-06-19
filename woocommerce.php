<?php get_header(); ?>
<div class="gdlr-content">
    <!-- 
        Elemento creado con AE Templates 
        Trae datos dinámicos del producto
     -->
    <?php echo do_shortcode('[INSERT_ELEMENTOR id="7685"]'); ?>
    
    <div class="with-sidebar-wrapper">
        <div class="with-sidebar-container container">
            <div class="with-sidebar-left twelve columns">
                <div class="with-sidebar-content twelve columns gdlr-item-start-content">
                    <div class="gdlr-item woocommerce-content-item">
                        <div class="woocommerce-content">
                            <?php woocommerce_content(); ?>
                        </div>
                    </div>
                </div>
                <?php get_sidebar('left'); ?>
                <div class="clear"></div>
            </div>
            <?php get_sidebar('right'); ?>
            <div class="clear"></div>
        </div>
    </div>
</div><!-- gdlr-content -->
<?php get_footer(); ?>
