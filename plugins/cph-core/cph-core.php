<?php
/**
 * Plugin Name: Coloring Photos Core
 * Description: Centralni plugin za sve custom funkcionalnosti na coloring.photos — Countdown Timeri, Print & Download Stats, i više.
 * Version: 1.0.0
 * Author: coloring.photos
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'CPH_VERSION',  '1.0.0' );
define( 'CPH_URL',      plugin_dir_url( __FILE__ ) );
define( 'CPH_PATH',     plugin_dir_path( __FILE__ ) );

// ── Učitaj module ─────────────────────────────────────────────────────────────
require_once CPH_PATH . 'modules/countdown.php';
require_once CPH_PATH . 'modules/tracker.php';
require_once CPH_PATH . 'modules/difficulty.php';

// ── Aktivacija — pokretanje svih migracija i setup-a ─────────────────────────
function cph_activate() {
    cph_countdown_activate();
    cph_tracker_activate();
    cph_migrate_from_old_countdown();
}
register_activation_hook( __FILE__, 'cph_activate' );

// ── Admin assets — jedan enqueue za cijeli plugin ────────────────────────────
function cph_admin_assets( $hook ) {
    $cph_pages = [
        'toplevel_page_cph-countdown',
        'coloring-photos_page_cph-tracker',
    ];
    if ( ! in_array( $hook, $cph_pages, true ) ) return;
    wp_enqueue_style( 'cph-admin', CPH_URL . 'css/admin.css', [], CPH_VERSION );
    wp_enqueue_style( 'cph-inter', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap', [], null );
}
add_action( 'admin_enqueue_scripts', 'cph_admin_assets' );

// ── Glavni admin izbornik ────────────────────────────────────────────────────
function cph_admin_menu() {
    add_menu_page(
        'Coloring Photos',
        'Coloring Photos',
        'manage_options',
        'cph-countdown',
        'cph_countdown_page',
        'dashicons-art',
        30
    );
    add_submenu_page(
        'cph-countdown',
        'Countdown Timeri',
        'Countdown Timeri',
        'manage_options',
        'cph-countdown',
        'cph_countdown_page'
    );
    add_submenu_page(
        'cph-countdown',
        'Print & Download Stats',
        'Stats',
        'manage_options',
        'cph-tracker',
        'cph_tracker_page'
    );
}
add_action( 'admin_menu', 'cph_admin_menu' );

// ── Migracija iz starog coloring-countdown plugina ───────────────────────────
function cph_migrate_from_old_countdown() {
    global $wpdb;

    // Provjeri postoji li stara tablica
    $old_table = $wpdb->prefix . 'cpc_timers';
    $new_table = $wpdb->prefix . 'cph_timers';

    if ( $wpdb->get_var("SHOW TABLES LIKE '$old_table'") !== $old_table ) return;
    if ( get_option('cph_migrated_countdown') ) return;

    // Kopiraj sve timere iz stare tablice u novu
    $old_timers = $wpdb->get_results("SELECT * FROM $old_table");
    foreach ( $old_timers as $t ) {
        $exists = $wpdb->get_var( $wpdb->prepare(
            "SELECT id FROM $new_table WHERE shortcode_slug = %s LIMIT 1",
            $t->shortcode_slug
        ));
        if ( $exists ) continue;

        $wpdb->insert( $new_table, [
            'film_name'       => $t->film_name,
            'release_date'    => $t->release_date,
            'subtext'         => $t->subtext ?? '',
            'after_text'      => $t->after_text ?? 'Now in cinemas!',
            'hide_after_days' => $t->hide_after_days ?? 180,
            'accent_color'    => $t->accent_color ?? '#e835ac',
            'shortcode_slug'  => $t->shortcode_slug,
            'created_at'      => $t->created_at ?? current_time('mysql'),
        ]);
    }

    update_option( 'cph_migrated_countdown', '1.0' );
}
