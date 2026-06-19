<?php
$lottie_url = get_stylesheet_directory_uri() . '/assets/lottie/momo-animated.json';
$momo_wave  = get_stylesheet_directory_uri() . '/assets/images/momo/momo-wave.svg';
?>
<section class="cph-hero">
  <!-- Decorative dots -->
  <div class="cph-hero__decor-dot" style="top:60px;left:6%;width:16px;height:16px;background:#FFD23F;"></div>
  <div class="cph-hero__decor-dot" style="top:140px;right:10%;width:22px;height:22px;background:#4D9DE0;opacity:.6;animation-delay:.5s;animation-duration:6s;"></div>
  <div class="cph-hero__decor-dot" style="bottom:80px;left:14%;width:13px;height:13px;background:#38D39F;animation-delay:.8s;animation-duration:4.5s;"></div>
  <svg style="position:absolute;top:90px;left:42%;animation:cphSpin 7s ease-in-out infinite;" width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true">
    <path d="M12 2 L14.6 9.4 L22 12 L14.6 14.6 L12 22 L9.4 14.6 L2 12 L9.4 9.4 Z" fill="#FF9E2C"/>
  </svg>

  <div class="cph-wrap">
    <div class="cph-hero__grid">

      <!-- Left: Text -->
      <div>
        <div class="cph-hero__badge">
          <span class="cph-hero__badge-dots">
            <span class="cph-hero__badge-dot" style="background:#FF4D6D;"></span>
            <span class="cph-hero__badge-dot" style="background:#FFD23F;"></span>
            <span class="cph-hero__badge-dot" style="background:#38D39F;"></span>
            <span class="cph-hero__badge-dot" style="background:#4D9DE0;"></span>
          </span>
          <span class="cph-hero__badge-text"><?php echo esc_html( sprintf( '%s+ free printable pages', number_format( wp_count_posts('product')->publish ) ) ); ?></span>
        </div>

        <h1><?php esc_html_e( 'Free coloring pages for every', 'flatsome-child' ); ?> <span><?php esc_html_e( 'little artist', 'flatsome-child' ); ?></span></h1>

        <p class="cph-hero__lead"><?php esc_html_e( 'Thousands of printable pages, hand-picked and difficulty-rated by a parent of three. Free to print, forever — for kids of every age and stage.', 'flatsome-child' ); ?></p>

        <div class="cph-hero__ctas">
          <a href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>" class="cph-btn-primary">
            <?php esc_html_e( 'Browse coloring pages', 'flatsome-child' ); ?>
            <svg width="17" height="17" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M3 9h11M10 4l5 5-5 5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
          <a href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>" class="cph-btn-secondary">
            <?php esc_html_e( 'Browse by category', 'flatsome-child' ); ?>
          </a>
        </div>

        <div class="cph-hero__social-proof">
          <span class="cph-avatar-stack">
            <span class="cph-avatar" style="background:#FFD23F;"></span>
            <span class="cph-avatar" style="background:#38D39F;"></span>
            <span class="cph-avatar" style="background:#4D9DE0;"></span>
          </span>
          <?php esc_html_e( 'Free to print · loved by parents & teachers', 'flatsome-child' ); ?>
        </div>
      </div>

      <!-- Right: Momo -->
      <div class="cph-hero__momo">
        <div class="cph-hero__momo-glow"></div>
        <svg style="position:absolute;top:8%;left:8%;animation:cphFloat2 5s ease-in-out infinite;" width="34" height="34" viewBox="0 0 40 14" aria-hidden="true">
          <rect x="1" y="3" width="30" height="9" rx="4.5" fill="#38D39F"/>
          <polygon points="31,1.5 39,7.5 31,13.5" fill="#FFD23F"/>
        </svg>
        <svg style="position:absolute;bottom:10%;right:6%;animation:cphFloat 5.5s ease-in-out infinite .4s;" width="30" height="30" viewBox="0 0 40 14" aria-hidden="true">
          <rect x="1" y="3" width="30" height="9" rx="4.5" fill="#4D9DE0"/>
          <polygon points="31,1.5 39,7.5 31,13.5" fill="#FF4D6D"/>
        </svg>
        <img id="cph-momo-static" src="<?php echo esc_url( $momo_wave ); ?>" width="250" height="312" alt="Momo mascot" style="position:relative;z-index:1;">
        <div id="cph-momo-lottie" data-lottie="<?php echo esc_url( $lottie_url ); ?>" aria-hidden="true"></div>
      </div>

    </div>
  </div>
</section>
