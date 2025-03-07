<?php

add_action( 'after_setup_theme', function() {
    /**
     * Featured image support for slider post type.
     */
    add_theme_support( 'post-thumbnails', [ 'slider' ] );
} );

add_action( 'init', function() {
    /**
     * Register the slider post type.
     */
    register_post_type( 'slider', [
        'labels' => [
            'name'          => 'Slides',
            'singular_name' => 'Slide',
            'add_new_item'  => 'Add New Slide',
            'edit_item'     => 'Edit Slide',
            'new_item'      => 'New Slide',
            'search_items'  => 'Search Slides',
            'not_found'     => 'No slides found.',
        ],
        'supports' => [
            'title',
            'excerpt',
            'thumbnail',
        ],
        'public'        => false,
        'has_archive'   => false,
        'show_ui'       => true,
        'menu_icon'     => 'dashicons-format-gallery',
    ] );
} );

add_action( 'add_meta_boxes', function( $post_type ) {
    if ( $post_type === 'slider' ) {
        /**
         * Add custom "excerpt" meta box as "description".
         */
        add_meta_box( 'postexcerpt', __( 'Description' ), function( $post ) {
            ?>
                <label class="screen-reader-text" for="excerpt">
                    <?php _e( 'Description' ) ?>
                </label><textarea rows="1" cols="40" name="excerpt" id="excerpt"><?php echo $post->post_excerpt ?></textarea>
            <?php
        }, null, 'normal', 'high' );
        /**
         * Add custom "thumbnail" meta box as "image".
         */
        add_meta_box( 'postimagediv', __( 'Image' ), 'post_thumbnail_meta_box', null, 'normal', 'high' );
    }
} );

if ( ! function_exists( 'slider_gallery' ) ) {
    /**
     * Slider gallery block.
     */
    function slider_gallery() {
        $args = [
            'posts_per_page'    => 10,
            'post_type'         => 'slider',
            'meta_query'        => [
                'key'           => '_thumbnail_id',
                'compare'       => 'EXISTS',
            ],
        ];

        $posts = get_posts( $args );

        if ( empty( $posts ) ) return;

        $ajax_url = admin_url( 'admin-ajax.php' ) . '?action=slider-description&id=';
    ?>
        <div class="swiper swiper-slider-gallery">
            <div class="swiper-wrapper">
                <?php foreach ( $posts as $post ) { ?>
                    <a class="swiper-slide slider-popup-ajax" href="<?php echo $ajax_url . $post->ID ?>">
                        <?php echo get_the_post_thumbnail( $post->ID, 'medium' ) ?>
                        <span><?php echo get_the_title( $post ) ?></span>
                    </a>
                <?php } ?>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next">
                <svg viewBox="0 0 24 25">
                    <path d="M15.41 17.2538L10.83 12.6738L15.41 8.08383L14 6.67383L8 12.6738L14 18.6738L15.41 17.2538Z" fill="#222222"/>
                </svg>
            </div>
            <div class="swiper-button-prev">
                <svg viewBox="0 0 24 25">
                    <path d="M15.41 17.2538L10.83 12.6738L15.41 8.08383L14 6.67383L8 12.6738L14 18.6738L15.41 17.2538Z" fill="#222222"/>
                </svg>
            </div>
        </div>
    <?php
    }
}

add_action( 'wp_ajax_slider-description', 'slider_description_handler' );
add_action( 'wp_ajax_nopriv_slider-description', 'slider_description_handler' );

if ( ! function_exists( 'slider_description_handler' ) ) {
    /**
     * Slider description ajax handler.
     */
    function slider_description_handler() {
        $content = '';

        if ( isset( $_GET['id'] ) && is_numeric( $_GET['id'] ) ) {
            $post = get_post( $_GET['id'] );

            if ( $post && $post->post_type === 'slider' ) {
                $content = $post->post_excerpt;
            }
        }
    ?>
        <div class="slider-popup-description">
            <div class="header"><?php _e( 'Description' ) ?></div>
            <div class="content"><?php echo $content ?></div>
        </div>
    <?php
        wp_die();
    }
}
