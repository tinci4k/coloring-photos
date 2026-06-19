<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Difficulty level definitions
function cph_difficulty_levels() {
    return [
        1 => [ 'name' => 'Tiny Artist',  'ages' => '2–4',  'color' => '#38D39F', 'deep' => '#0E9F6E', 'tint' => '#E8F8F1' ],
        2 => [ 'name' => 'Little Artist', 'ages' => '4–6',  'color' => '#4D9DE0', 'deep' => '#2563C9', 'tint' => '#EAF3FC' ],
        3 => [ 'name' => 'Creative Kid', 'ages' => '6–9',  'color' => '#FFD23F', 'deep' => '#B7791F', 'tint' => '#FFF9E0' ],
        4 => [ 'name' => 'Art Explorer', 'ages' => '9–12', 'color' => '#FF9E2C', 'deep' => '#D9650A', 'tint' => '#FFF3E3' ],
        5 => [ 'name' => 'Master Artist','ages' => '12+',  'color' => '#FF4D6D', 'deep' => '#E11D48', 'tint' => '#FFEEF1' ],
    ];
}

// Get difficulty level (1-5) for a product
function cph_get_difficulty( $product_id ) {
    $val = get_post_meta( $product_id, '_cph_difficulty', true );
    if ( $val === '' || $val === false ) return null;
    $level = intval( $val );
    return ( $level >= 1 && $level <= 5 ) ? $level : null;
}

// Get difficulty data array for a level
function cph_get_difficulty_data( $level ) {
    $levels = cph_difficulty_levels();
    return isset( $levels[ $level ] ) ? $levels[ $level ] : null;
}

// Get average difficulty for a WooCommerce category (term_id)
function cph_get_category_avg_difficulty( $term_id ) {
    $args = [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'tax_query'      => [[
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => $term_id,
        ]],
        'meta_query'     => [[
            'key'     => '_cph_difficulty',
            'compare' => 'EXISTS',
        ]],
    ];

    $ids = get_posts( $args );
    if ( empty( $ids ) ) return null;

    $total = 0;
    $count = 0;
    foreach ( $ids as $id ) {
        $d = cph_get_difficulty( $id );
        if ( $d !== null ) {
            $total += $d;
            $count++;
        }
    }
    return $count > 0 ? round( $total / $count, 1 ) : null;
}

// Return the URL for a difficulty icon SVG
function cph_difficulty_icon_url( $level ) {
    return get_stylesheet_directory_uri() . '/assets/difficulty/difficulty-level-' . intval( $level ) . '.svg';
}
