<?php
$colors = [ '#FF4D6D','#FF9E2C','#FFD23F','#38D39F','#4D9DE0' ];
$tints  = [ '#FFEEF1','#FFF3E3','#FFF9E0','#E8F8F1','#EAF3FC' ];

// Dohvati top-level WooCommerce kategorije
$terms = get_terms([
    'taxonomy'   => 'product_cat',
    'hide_empty' => true,
    'parent'     => 0,
    'exclude'    => [ get_option('default_product_cat') ],
    'number'     => 8,
    'orderby'    => 'count',
    'order'      => 'DESC',
]);

if ( empty( $terms ) || is_wp_error( $terms ) ) return;
?>
<section class="cph-section" id="cph-categories">
  <div class="cph-wrap">
    <div class="cph-section-head">
      <div class="cph-label"><?php esc_html_e( 'Browse', 'flatsome-child' ); ?></div>
      <h2 class="cph-section-title"><?php esc_html_e( "Find exactly what you're looking for", 'flatsome-child' ); ?></h2>
    </div>

    <div class="cph-g4">
      <?php foreach ( $terms as $i => $term ) :
        $color    = $colors[ $i % 5 ];
        $tint     = $tints[ $i % 5 ];
        $link     = get_term_link( $term );
        $thumb_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
        $thumb    = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'thumbnail' ) : '';
      ?>
        <a href="<?php echo esc_url( $link ); ?>" class="cph-cat-card" style="border-bottom-color:<?php echo esc_attr( $color ); ?>;">
          <div class="cph-cat-card__thumb" style="background:<?php echo esc_attr( $tint ); ?>;">
            <?php if ( $thumb ) : ?>
              <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $term->name ); ?>" width="64" height="64" loading="lazy">
            <?php else : ?>
              <svg width="64" height="64" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                <rect x="8" y="8" width="48" height="48" rx="8" stroke="<?php echo esc_attr( $color ); ?>" stroke-width="2" stroke-dasharray="4 3" opacity=".5"/>
                <path d="M32 24v16M24 32h16" stroke="<?php echo esc_attr( $color ); ?>" stroke-width="2.5" stroke-linecap="round"/>
              </svg>
            <?php endif; ?>
          </div>
          <div class="cph-cat-card__foot">
            <span class="cph-cat-card__name"><?php echo esc_html( $term->name ); ?></span>
            <span class="cph-cat-card__count" style="color:<?php echo esc_attr( $color ); ?>;background:<?php echo esc_attr( $tint ); ?>;"><?php echo esc_html( $term->count ); ?></span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

    <div style="text-align:center;margin-top:30px;">
      <a href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>" class="cph-btn-dark" style="display:inline-flex;align-items:center;gap:8px;">
        <?php esc_html_e( 'View all categories', 'flatsome-child' ); ?>
        <svg width="16" height="16" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M3 9h11M10 4l5 5-5 5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </div>
  </div>
</section>
