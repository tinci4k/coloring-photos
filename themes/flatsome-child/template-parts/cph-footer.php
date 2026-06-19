<?php
$momo_happy = get_stylesheet_directory_uri() . '/assets/images/momo/momo-happy.svg';
$colors     = [ '#FF4D6D','#FF9E2C','#FFD23F','#38D39F','#4D9DE0' ];

// Popular categories for footer
$foot_cats = get_terms([
    'taxonomy'   => 'product_cat',
    'hide_empty' => true,
    'parent'     => 0,
    'exclude'    => [ get_option('default_product_cat') ],
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 5,
]);
?>
<footer class="cph-footer" role="contentinfo">
  <div class="cph-wrap">
    <div class="cph-footer__grid">

      <!-- Col 1: Brand -->
      <div>
        <a href="<?php echo esc_url( home_url('/') ); ?>" class="cph-footer__logo">
          <img src="<?php echo esc_url( $momo_happy ); ?>" width="34" height="42" alt="">
          <span class="cph-logo__text">coloring<span>.photos</span></span>
        </a>
        <p class="cph-footer__desc"><?php esc_html_e( 'Free printable coloring pages, hand-picked and difficulty-rated by a parent of three. Free forever — for every little artist.', 'flatsome-child' ); ?></p>
        <a href="https://pinterest.com/coloringphotos" class="cph-footer__pinterest" target="_blank" rel="noopener">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="#E60023" aria-hidden="true"><path d="M12 2C6.48 2 2 6.48 2 12c0 4.08 2.45 7.59 5.96 9.13-.08-.78-.16-1.97.03-2.82.18-.76 1.15-4.85 1.15-4.85s-.29-.59-.29-1.46c0-1.37.79-2.39 1.78-2.39.84 0 1.25.63 1.25 1.39 0 .85-.54 2.11-.82 3.28-.23.98.49 1.78 1.46 1.78 1.75 0 3.1-1.85 3.1-4.52 0-2.36-1.7-4.01-4.12-4.01-2.81 0-4.46 2.11-4.46 4.29 0 .85.33 1.76.74 2.25.08.1.09.18.07.29l-.27 1.1c-.04.18-.14.22-.33.13-1.23-.57-2-2.37-2-3.81 0-3.1 2.25-5.95 6.5-5.95 3.41 0 6.07 2.43 6.07 5.68 0 3.39-2.14 6.12-5.1 6.12-1 0-1.93-.52-2.25-1.13l-.61 2.33c-.22.85-.82 1.92-1.22 2.57.92.28 1.89.43 2.91.43 5.52 0 10-4.48 10-10S17.52 2 12 2z"/></svg>
          <?php esc_html_e( 'Follow on Pinterest', 'flatsome-child' ); ?>
        </a>
      </div>

      <!-- Col 2: Explore -->
      <div>
        <div class="cph-footer__col-title"><?php esc_html_e( 'Explore', 'flatsome-child' ); ?></div>
        <div class="cph-footer__links">
          <a href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>"><?php esc_html_e( 'Coloring Pages', 'flatsome-child' ); ?></a>
          <a href="<?php echo esc_url( get_post_type_archive_link('post') ?: '#' ); ?>"><?php esc_html_e( 'Blog', 'flatsome-child' ); ?></a>
          <a href="<?php echo esc_url( get_permalink( get_page_by_path('about') ) ?: get_permalink( get_page_by_path('about-us') ) ?: '#' ); ?>"><?php esc_html_e( 'About Us', 'flatsome-child' ); ?></a>
          <a href="<?php echo esc_url( get_permalink( get_page_by_path('difficulty-guide') ) ?: '#' ); ?>"><?php esc_html_e( 'Difficulty Guide', 'flatsome-child' ); ?></a>
          <a href="<?php echo esc_url( get_permalink( get_page_by_path('guide-printing') ) ?: '#' ); ?>"><?php esc_html_e( 'Guide to Printing', 'flatsome-child' ); ?></a>
          <a href="<?php echo esc_url( get_permalink( get_page_by_path('privacy-policy') ) ?: get_privacy_policy_url() ); ?>"><?php esc_html_e( 'Privacy Policy', 'flatsome-child' ); ?></a>
          <a href="<?php echo esc_url( get_permalink( get_page_by_path('cookie-policy') ) ?: '#' ); ?>"><?php esc_html_e( 'Cookie Policy', 'flatsome-child' ); ?></a>
        </div>
      </div>

      <!-- Col 3: Popular categories -->
      <div>
        <div class="cph-footer__col-title"><?php esc_html_e( 'Popular categories', 'flatsome-child' ); ?></div>
        <div class="cph-footer__links">
          <?php if ( ! is_wp_error( $foot_cats ) ) : foreach ( $foot_cats as $fi => $fcat ) :
            $fc = $colors[ $fi % 5 ];
          ?>
            <a href="<?php echo esc_url( get_term_link($fcat) ); ?>" class="cph-footer__cat-link">
              <span class="cph-footer__cat-dot" style="background:<?php echo esc_attr($fc); ?>;"></span>
              <?php echo esc_html( $fcat->name ); ?>
              <span class="cph-footer__cat-badge" style="background:<?php echo esc_attr($fc); ?>;"><?php esc_html_e( 'Popular', 'flatsome-child' ); ?></span>
            </a>
          <?php endforeach; endif; ?>
        </div>
      </div>

    </div>
  </div>

  <div class="cph-footer__bottom">
    <div class="cph-wrap">
      <div class="cph-footer__bottom-inner">
        <span class="cph-footer__copy"><?php echo esc_html( 'Copyright ' . date('Y') . ' © coloring.photos' ); ?></span>
        <span class="cph-footer__tagline"><?php esc_html_e( 'Made with crayons & love · for every little artist', 'flatsome-child' ); ?></span>
      </div>
    </div>
  </div>
</footer>
