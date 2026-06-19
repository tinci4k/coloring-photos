<?php
// ── CPH Theme Assets ─────────────────────────────────────────────────────────
function cph_theme_assets() {
    // Google Fonts
    wp_enqueue_style(
        'cph-fonts',
        'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito:wght@400;500;600;700;800&display=swap',
        [],
        null
    );

    // Main CPH stylesheet
    wp_enqueue_style(
        'cph-main',
        get_stylesheet_directory_uri() . '/assets/css/cph-main.css',
        [ 'cph-fonts' ],
        wp_get_theme()->get('Version')
    );

    // Lottie (only on front page)
    if ( is_front_page() ) {
        wp_enqueue_script(
            'lottie',
            'https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.12.2/lottie.min.js',
            [],
            '5.12.2',
            true
        );
    }

    // Nav JS
    wp_enqueue_script(
        'cph-nav',
        get_stylesheet_directory_uri() . '/assets/js/cph-nav.js',
        [],
        wp_get_theme()->get('Version'),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'cph_theme_assets' );

// Add custom Theme Functions here
add_action( 'feed_links_show_posts_feed', '__return_false', - 1 );
add_action( 'feed_links_show_comments_feed', '__return_false', - 1 );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );

//Google Ads after certain number of products
/*add_action( 'woocommerce_shop_loop', 'action_woocommerce_shop_loop', 100 );
function action_woocommerce_shop_loop() {

    if ( is_product_category() ) :
        
    global $wp_query;
    
    $columns = esc_attr( wc_get_loop_prop( 'columns' ) );
    
    $current_post = $wp_query->current_post;
    
    if ( ( $current_post % $columns ) == 0  && (in_array($current_post, array (6)))) :
    
    ?>
    <ul class="columns-1" style="list-style:none;">
        <li style=""><div class="banner"> 
            
        <!--Insert Ads here -->
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6399445658762603"
             crossorigin="anonymous"></script>
        <!-- After #products -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-6399445658762603"
             data-ad-slot="7143369835"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
        <script>
             (adsbygoogle = window.adsbygoogle || []).push({});
        </script>

        </div></li>
    </ul>
    
        <?php
    endif;endif;
}*/

add_shortcode( 'print-button', 'print_button_shortcode' );
function print_button_shortcode( $atts ) {
	return '<div class="print-button-container"></div>';
}

add_shortcode( 'download-button', 'download_button_shortcode' );
function download_button_shortcode( $atts ) {
	return '<div class="download-button-container"></div>';
}

add_shortcode( 'coloring-image', 'coloring_image_shortcode' );
function coloring_image_shortcode( $atts ) {
	return '<div class="coloring-image-container"></div>';
}

