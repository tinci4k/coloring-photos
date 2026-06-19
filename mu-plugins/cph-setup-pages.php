<?php
/**
 * CPH Setup Pages
 * Auto-creates required pages if they don't exist.
 * Runs once on every WP load — cheap because it checks a transient first.
 */

add_action( 'init', 'cph_setup_required_pages' );

function cph_setup_required_pages() {
    // Check only once per hour to keep it fast
    if ( get_transient( 'cph_pages_checked' ) ) {
        return;
    }

    $pages = [
        [
            'title'    => 'Difficulty Guide',
            'slug'     => 'difficulty-guide',
            'template' => 'page-difficulty-guide.php',
        ],
        // Add more pages here as needed:
        // [ 'title' => 'Guide to Printing', 'slug' => 'guide-printing', 'template' => '' ],
    ];

    foreach ( $pages as $page ) {
        $existing = get_page_by_path( $page['slug'], OBJECT, 'page' );

        if ( ! $existing ) {
            $page_id = wp_insert_post( [
                'post_type'   => 'page',
                'post_title'  => $page['title'],
                'post_name'   => $page['slug'],
                'post_status' => 'publish',
                'post_content'=> '',
            ] );

            if ( $page_id && ! is_wp_error( $page_id ) && ! empty( $page['template'] ) ) {
                update_post_meta( $page_id, '_wp_page_template', $page['template'] );
            }
        } elseif ( ! empty( $page['template'] ) ) {
            // Page exists but make sure template is set correctly
            $current_template = get_post_meta( $existing->ID, '_wp_page_template', true );
            if ( $current_template !== $page['template'] ) {
                update_post_meta( $existing->ID, '_wp_page_template', $page['template'] );
            }
        }
    }

    set_transient( 'cph_pages_checked', true, HOUR_IN_SECONDS );
}
