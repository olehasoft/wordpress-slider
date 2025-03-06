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
    ?>
        <div class="swiper swiper-slider-gallery">
            <div class="swiper-wrapper">
                <?php foreach ( $posts as $post ) { ?>
                    <div class="swiper-slide">
                        <?php echo get_the_post_thumbnail( $post->ID, 'medium' ) ?>
                        <span><?php echo get_the_title( $post ) ?></span>
                    </div>
                <?php } ?>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    <?php
    }
}
