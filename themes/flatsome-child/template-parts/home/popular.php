<?php
$colors = [ '#FF4D6D','#FF9E2C','#FFD23F','#38D39F','#4D9DE0' ];
$tints  = [ '#FFEEF1','#FFF3E3','#FFF9E0','#E8F8F1','#EAF3FC' ];

$popular_ids = function_exists('cph_get_popular_products') ? cph_get_popular_products( 30, 7 ) : [];

if ( empty( $popular_ids ) ) {
    $query = new WP_Query([
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => 7,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
} else {
    $query = new WP_Query([
        'post_type'      => 'product',
        'post__in'       => $popular_ids,
        'orderby'        => 'post__in',
        'posts_per_page' => 7,
    ]);
}

if ( ! $query->have_posts() ) return;

$momo_celebrate = get_stylesheet_directory_uri() . '/assets/images/momo/momo-celebrate.svg';
?>
<section class="cph-section" id="cph-popular">
  <div class="cph-wrap">
    <div class="cph-section-row">
      <div style="display:flex;align-items:center;gap:14px;">
        <img src="<?php echo esc_url( $momo_celebrate ); ?>" width="56" height="70" alt="" aria-hidden="true" style="flex:none;animation:cphFloat 4s ease-in-out infinite;">
        <div>
          <div class="cph-label"><?php esc_html_e( 'Trending', 'flatsome-child' ); ?></div>
          <h2 class="cph-section-title"><?php esc_html_e( 'Most popular right now', 'flatsome-child' ); ?></h2>
        </div>
      </div>
      <a href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>" class="cph-see-all"><?php esc_html_e( 'See all →', 'flatsome-child' ); ?></a>
    </div>

    <!-- 2×4 grid: 4 cards + ad + 3 cards = 8 slots -->
    <div class="cph-g4">
      <?php
      $i = 0;
      while ( $query->have_posts() ) :
        $query->the_post();
        $product_id = get_the_ID();
        $color      = $colors[ $i % 5 ];
        $tint       = $tints[ $i % 5 ];
        $thumb_url  = get_the_post_thumbnail_url( $product_id, 'large' ) ?: get_the_post_thumbnail_url( $product_id, 'full' );
        $cats       = get_the_terms( $product_id, 'product_cat' );
        $cat_name   = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0]->name : '';
        $diff_level = function_exists('cph_get_difficulty') ? cph_get_difficulty( $product_id ) : null;
        $diff_data  = ( $diff_level && function_exists('cph_get_difficulty_data') ) ? cph_get_difficulty_data( $diff_level ) : null;
        $diff_icon  = $diff_level ? get_stylesheet_directory_uri() . '/assets/difficulty/difficulty-level-' . $diff_level . '.svg' : null;

        // Insert ad slot after 4th card (index 4 = 5th position)
        if ( $i === 4 ) : ?>
          <div class="cph-ad-card">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2" stroke="#C9C9D2" stroke-width="1.6"/><path d="M3 15l5-4 4 3 3-2 6 4" stroke="#C9C9D2" stroke-width="1.6" stroke-linejoin="round"/></svg>
            <span>Advertisement</span>
            <span style="font-size:11px;color:#C4C4CC;">300 × 250</span>
          </div>
        <?php endif; ?>

        <a href="<?php echo esc_url( get_permalink() ); ?>" class="cph-product-card">
          <div class="cph-product-card__thumb" style="background:<?php echo esc_attr( $tint ); ?>;">
            <?php if ( $thumb_url ) : ?>
              <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
            <?php else : ?>
              <div class="cph-product-card__thumb-placeholder">
                <svg width="60" height="80" viewBox="0 0 60 80" fill="none"><rect x="5" y="5" width="50" height="70" rx="4" stroke="currentColor" stroke-width="2" stroke-dasharray="4 3"/></svg>
              </div>
            <?php endif; ?>
            <?php if ( $cat_name ) : ?>
              <span class="cph-product-card__cat-badge" style="color:<?php echo esc_attr( $color ); ?>;"><?php echo esc_html( $cat_name ); ?></span>
            <?php endif; ?>
            <?php if ( $diff_level && $diff_data ) : ?>
              <span class="cph-product-card__diff-badge" style="border-color:<?php echo esc_attr( $diff_data['color'] ); ?>;">
                <img src="<?php echo esc_url( $diff_icon ); ?>" width="20" height="20" alt="">
                <span style="color:<?php echo esc_attr( $diff_data['deep'] ); ?>;"><?php echo esc_html( $diff_data['name'] ); ?></span>
              </span>
            <?php endif; ?>
          </div>
          <div class="cph-product-card__body">
            <span class="cph-product-card__title"><?php the_title(); ?></span>
            <div class="cph-product-card__meta">
              <span class="cph-product-card__format" style="color:<?php echo esc_attr( $color ); ?>;background:<?php echo esc_attr( $tint ); ?>;">
                <svg width="11" height="11" viewBox="0 0 14 14" fill="none"><rect x="2.5" y="1.5" width="9" height="11" rx="1.4" stroke="currentColor" stroke-width="1.3"/><path d="M4.6 5h4.8M4.6 7.2h4.8M4.6 9.4h3" stroke="currentColor" stroke-width="1.1" stroke-linecap="round"/></svg>
                A4 PDF
              </span>
              <span class="cph-product-card__free"><span class="cph-product-card__free-dot"></span><?php esc_html_e( 'Free', 'flatsome-child' ); ?></span>
            </div>
          </div>
        </a>
      <?php $i++; endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
