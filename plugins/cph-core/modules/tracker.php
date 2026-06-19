<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// ── Aktivacija: kreiraj tablicu ───────────────────────────────────────────────

function cph_tracker_activate() {
    global $wpdb;
    $table   = $wpdb->prefix . 'cph_page_events';
    $charset = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table (
        id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        product_id  BIGINT UNSIGNED NOT NULL,
        event_type  VARCHAR(10)     NOT NULL COMMENT 'print ili download',
        recorded_at DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY idx_product  (product_id),
        KEY idx_recorded (recorded_at)
    ) $charset;";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
}

// ── Helper: product ID iz URL-a slike ─────────────────────────────────────────

function cph_get_product_id_from_image_url( $image_url ) {
    global $wpdb;
    if ( empty($image_url) ) return 0;
    $image_url = esc_url_raw( urldecode($image_url) );

    $attachment_id = $wpdb->get_var( $wpdb->prepare(
        "SELECT ID FROM {$wpdb->posts} WHERE post_type='attachment' AND guid=%s LIMIT 1", $image_url
    ));
    if ( ! $attachment_id ) {
        $filename_no_ext = pathinfo( basename(parse_url($image_url, PHP_URL_PATH)), PATHINFO_FILENAME );
        $attachment_id = $wpdb->get_var( $wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE post_type='attachment' AND post_name LIKE %s LIMIT 1",
            '%' . $wpdb->esc_like($filename_no_ext) . '%'
        ));
    }
    if ( ! $attachment_id ) return 0;

    $product_id = $wpdb->get_var( $wpdb->prepare(
        "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_thumbnail_id' AND meta_value=%d LIMIT 1",
        $attachment_id
    ));
    if ( ! $product_id ) {
        $product_id = $wpdb->get_var( $wpdb->prepare(
            "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_product_image_gallery' AND meta_value LIKE %s LIMIT 1",
            '%' . $wpdb->esc_like((string)$attachment_id) . '%'
        ));
    }
    return (int) $product_id;
}

// ── Helper: zapiši event ──────────────────────────────────────────────────────

function cph_record_event( $product_id, $event_type ) {
    global $wpdb;
    if ( !$product_id || $product_id <= 0 ) return;
    if ( !in_array($event_type, ['print','download'], true) ) return;
    $wpdb->insert(
        $wpdb->prefix . 'cph_page_events',
        ['product_id'=>(int)$product_id,'event_type'=>$event_type,'recorded_at'=>current_time('mysql')],
        ['%d','%s','%s']
    );
}

// ── Tracking: Download ────────────────────────────────────────────────────────

function cph_track_download() {
    if ( !is_page() ) return;
    global $post;
    if ( !$post || strpos($post->post_name, 'download-page') === false ) return;
    $product_id = isset($_GET['somdn_product']) ? intval($_GET['somdn_product']) : 0;
    if ( !$product_id && isset($_GET['image']) ) {
        $product_id = cph_get_product_id_from_image_url($_GET['image']);
    }
    if ( $product_id ) cph_record_event($product_id, 'download');
}
add_action('template_redirect', 'cph_track_download');

// ── Tracking: Print ───────────────────────────────────────────────────────────

function cph_track_print() {
    if ( !is_page() ) return;
    global $post;
    if ( !$post || strpos($post->post_name, 'print-page') === false ) return;
    $image_url  = isset($_GET['print_image']) ? $_GET['print_image'] : '';
    $product_id = cph_get_product_id_from_image_url($image_url);
    if ( $product_id ) cph_record_event($product_id, 'print');
}
add_action('template_redirect', 'cph_track_print');

// ── Čišćenje starih zapisa ────────────────────────────────────────────────────

function cph_cleanup_old_events() {
    global $wpdb;
    $wpdb->query("DELETE FROM {$wpdb->prefix}cph_page_events WHERE recorded_at < DATE_SUB(NOW(), INTERVAL 90 DAY)");
}
if ( !wp_next_scheduled('cph_weekly_cleanup') ) {
    wp_schedule_event(time(), 'weekly', 'cph_weekly_cleanup');
}
add_action('cph_weekly_cleanup', 'cph_cleanup_old_events');

// ── Public helper: najpopularnije bojanke ────────────────────────────────────

function cph_get_popular_products( $days=30, $limit=8 ) {
    global $wpdb;
    $results = $wpdb->get_col( $wpdb->prepare(
        "SELECT product_id FROM {$wpdb->prefix}cph_page_events
         WHERE recorded_at > DATE_SUB(NOW(), INTERVAL %d DAY)
         GROUP BY product_id ORDER BY COUNT(*) DESC LIMIT %d",
        $days, $limit
    ));
    return array_map('intval', $results);
}

// ── Admin stranica ────────────────────────────────────────────────────────────

function cph_tracker_page() {
    global $wpdb;
    $table  = $wpdb->prefix . 'cph_page_events';
    $period = isset($_GET['period']) ? intval($_GET['period']) : 30;
    if ( !in_array($period, [7,30,90], true) ) $period = 30;
    $period_labels = [7=>'Last 7 days', 30=>'Last 30 days', 90=>'Last 90 days'];
    $base_url = admin_url('admin.php?page=cph-tracker');

    // Stat cards
    $total = $wpdb->get_row( $wpdb->prepare(
        "SELECT COUNT(*) as total,
                SUM(event_type='print') as prints,
                SUM(event_type='download') as downloads,
                COUNT(DISTINCT product_id) as unique_pages
         FROM $table WHERE recorded_at > DATE_SUB(NOW(), INTERVAL %d DAY)", $period
    ));
    $today_count = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE DATE(recorded_at)=CURDATE()");

    // Top bojanke
    $top_products = $wpdb->get_results( $wpdb->prepare(
        "SELECT product_id,
                COUNT(*) as total,
                SUM(event_type='print') as prints,
                SUM(event_type='download') as downloads
         FROM $table WHERE recorded_at > DATE_SUB(NOW(), INTERVAL %d DAY)
         GROUP BY product_id ORDER BY total DESC LIMIT 20", $period
    ));

    // Top kategorije
    $top_categories = $wpdb->get_results( $wpdb->prepare(
        "SELECT t.name as cat_name, t.slug as cat_slug,
                COUNT(*) as total,
                SUM(e.event_type='print') as prints,
                SUM(e.event_type='download') as downloads
         FROM {$wpdb->prefix}cph_page_events e
         JOIN {$wpdb->prefix}term_relationships tr ON e.product_id = tr.object_id
         JOIN {$wpdb->prefix}term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy = 'product_cat'
         JOIN {$wpdb->prefix}terms t ON tt.term_id = t.term_id
         WHERE e.recorded_at > DATE_SUB(NOW(), INTERVAL %d DAY)
         GROUP BY t.term_id ORDER BY total DESC LIMIT 10", $period
    ));

    $max_products   = !empty($top_products)   ? (int)$top_products[0]->total   : 1;
    $max_categories = !empty($top_categories) ? (int)$top_categories[0]->total : 1;
    ?>
    <div id="cph-app">
        <h1 class="cph-page-title">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            Print &amp; Download Stats
        </h1>

        <!-- Period tabs -->
        <div class="cph-tabs">
            <?php foreach([7=>'Last 7 days',30=>'Last 30 days',90=>'Last 90 days'] as $d=>$lbl): ?>
            <a href="<?php echo esc_url($base_url.'&period='.$d); ?>"
               class="cph-tab <?php echo $period===$d?'active':''; ?>">
                <?php echo esc_html($lbl); ?>
            </a>
            <?php endforeach; ?>
        </div>

        <!-- Stat cards -->
        <div class="cph-stats-row">
            <div class="cph-stat-card cph-stat-pink">
                <div class="cph-stat-label">Total Events</div>
                <div class="cph-stat-value"><?php echo number_format((int)($total->total??0)); ?></div>
                <div class="cph-stat-sub">prints + downloads</div>
            </div>
            <div class="cph-stat-card cph-stat-blue">
                <div class="cph-stat-label">Downloads</div>
                <div class="cph-stat-value"><?php echo number_format((int)($total->downloads??0)); ?></div>
                <div class="cph-stat-sub"><?php echo esc_html($period_labels[$period]); ?></div>
            </div>
            <div class="cph-stat-card cph-stat-green">
                <div class="cph-stat-label">Prints</div>
                <div class="cph-stat-value"><?php echo number_format((int)($total->prints??0)); ?></div>
                <div class="cph-stat-sub"><?php echo esc_html($period_labels[$period]); ?></div>
            </div>
            <div class="cph-stat-card cph-stat-orange">
                <div class="cph-stat-label">Today</div>
                <div class="cph-stat-value"><?php echo number_format((int)$today_count); ?></div>
                <div class="cph-stat-sub">events so far</div>
            </div>
        </div>

        <!-- Top bojanke -->
        <div class="cph-table-card" style="margin-bottom:20px;">
            <div class="cph-table-header">
                <div class="cph-table-title">
                    Top Coloring Pages
                    <span class="cph-count-badge"><?php echo esc_html($period_labels[$period]); ?></span>
                </div>
            </div>
            <?php if (empty($top_products)): ?>
            <div class="cph-empty">
                <div class="cph-empty-icon">📊</div>
                <p>No data yet for this period.</p>
                <small>Stats will appear once visitors start printing and downloading.</small>
            </div>
            <?php else: ?>
            <table class="cph-table">
                <thead><tr>
                    <th style="width:40px;">#</th>
                    <th>Coloring Page</th>
                    <th class="cph-right" style="width:110px;">Downloads</th>
                    <th class="cph-right" style="width:110px;">Prints</th>
                    <th style="width:200px;">Total</th>
                </tr></thead>
                <tbody>
                <?php foreach($top_products as $i=>$row):
                    $product = wc_get_product($row->product_id);
                    if (!$product) continue;
                    $rank    = $i+1;
                    $rcls    = $rank===1?'cph-rank-1':($rank===2?'cph-rank-2':($rank===3?'cph-rank-3':'cph-rank-n'));
                    $thumb   = get_the_post_thumbnail_url($row->product_id,'thumbnail');
                    $cats    = wc_get_product_category_list($row->product_id,', ');
                    $pct     = $max_products>0?round(($row->total/$max_products)*100):0;
                ?>
                <tr>
                    <td><span class="cph-rank <?php echo $rcls;?>"><?php echo $rank;?></span></td>
                    <td>
                        <div class="cph-product-cell">
                            <?php if($thumb):?><img src="<?php echo esc_url($thumb);?>" class="cph-product-thumb" alt=""><?php endif;?>
                            <div>
                                <a href="<?php echo esc_url(get_edit_post_link($row->product_id));?>" class="cph-product-name"><?php echo esc_html($product->get_name());?></a>
                                <?php if($cats):?><div class="cph-product-cat"><?php echo wp_strip_all_tags($cats);?></div><?php endif;?>
                            </div>
                        </div>
                    </td>
                    <td class="cph-right"><span class="cph-num"><?php echo number_format((int)$row->downloads);?></span></td>
                    <td class="cph-right"><span class="cph-num"><?php echo number_format((int)$row->prints);?></span></td>
                    <td>
                        <div class="cph-bar-wrap">
                            <div class="cph-bar-bg"><div class="cph-bar-fill cph-bar-purple" style="width:<?php echo $pct;?>%"></div></div>
                            <span class="cph-bar-num"><?php echo number_format((int)$row->total);?></span>
                        </div>
                    </td>
                </tr>
                <?php endforeach;?>
                </tbody>
            </table>
            <?php endif;?>
        </div>

        <!-- Top kategorije -->
        <div class="cph-table-card">
            <div class="cph-table-header">
                <div class="cph-table-title">
                    Top Categories
                    <span class="cph-count-badge"><?php echo esc_html($period_labels[$period]); ?></span>
                </div>
            </div>
            <?php if (empty($top_categories)): ?>
            <div class="cph-empty">
                <div class="cph-empty-icon">📂</div>
                <p>No category data yet.</p>
            </div>
            <?php else: ?>
            <table class="cph-table">
                <thead><tr>
                    <th style="width:40px;">#</th>
                    <th>Category</th>
                    <th class="cph-right" style="width:110px;">Downloads</th>
                    <th class="cph-right" style="width:110px;">Prints</th>
                    <th style="width:200px;">Total</th>
                </tr></thead>
                <tbody>
                <?php foreach($top_categories as $i=>$row):
                    $rank = $i+1;
                    $rcls = $rank===1?'cph-rank-1':($rank===2?'cph-rank-2':($rank===3?'cph-rank-3':'cph-rank-n'));
                    $pct  = $max_categories>0?round(($row->total/$max_categories)*100):0;
                    $cat_url = admin_url('term.php?taxonomy=product_cat&tag_ID='.urlencode($row->cat_slug));
                ?>
                <tr>
                    <td><span class="cph-rank <?php echo $rcls;?>"><?php echo $rank;?></span></td>
                    <td>
                        <a href="<?php echo esc_url(get_term_link($row->cat_slug,'product_cat'));?>" class="cph-product-name" target="_blank"><?php echo esc_html($row->cat_name);?></a>
                    </td>
                    <td class="cph-right"><span class="cph-num"><?php echo number_format((int)$row->downloads);?></span></td>
                    <td class="cph-right"><span class="cph-num"><?php echo number_format((int)$row->prints);?></span></td>
                    <td>
                        <div class="cph-bar-wrap">
                            <div class="cph-bar-bg"><div class="cph-bar-fill cph-bar-pink" style="width:<?php echo $pct;?>%"></div></div>
                            <span class="cph-bar-num"><?php echo number_format((int)$row->total);?></span>
                        </div>
                    </td>
                </tr>
                <?php endforeach;?>
                </tbody>
            </table>
            <?php endif;?>
        </div>
    </div>
    <?php
}
