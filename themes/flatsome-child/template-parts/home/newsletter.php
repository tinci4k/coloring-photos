<?php
$momo_celebrate = get_stylesheet_directory_uri() . '/assets/images/momo/momo-celebrate.svg';
?>
<section class="cph-section" id="cph-newsletter">
  <div class="cph-wrap">
    <div class="cph-nl" style="background:#FFF0F9;border:1px solid #F5D6EC;">
      <div class="cph-nl__grid">
        <div class="cph-nl__momo">
          <img src="<?php echo esc_url( $momo_celebrate ); ?>" width="172" height="215" alt="" aria-hidden="true" style="animation:cphFloat 4.5s ease-in-out infinite;">
        </div>
        <div>
          <h2><?php esc_html_e( 'Fresh coloring pages, straight to your inbox', 'flatsome-child' ); ?></h2>
          <p><?php esc_html_e( 'Free new pages every week — hand-picked, print-ready, and rated by difficulty. No spam, ever.', 'flatsome-child' ); ?></p>
          <?php
          // Newsletter plugin shortcode if available, otherwise plain form
          if ( shortcode_exists('newsletter') ) :
            echo do_shortcode('[newsletter]');
          else : ?>
            <form class="cph-nl__form" method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
              <input type="hidden" name="action" value="cph_newsletter_sub">
              <?php wp_nonce_field('cph_newsletter', 'cph_nl_nonce'); ?>
              <input class="cph-nl__input" type="email" name="email" placeholder="<?php esc_attr_e( 'you@email.com', 'flatsome-child' ); ?>" required style="border-color:#F5D6EC;">
              <button type="submit" class="cph-nl__btn"><?php esc_html_e( 'Subscribe', 'flatsome-child' ); ?></button>
            </form>
          <?php endif; ?>
          <div class="cph-nl__social-proof">
            <span class="cph-avatar-stack">
              <span class="cph-avatar" style="background:#FF4D6D;"></span>
              <span class="cph-avatar" style="background:#38D39F;"></span>
              <span class="cph-avatar" style="background:#4D9DE0;"></span>
            </span>
            <?php esc_html_e( '6,000+ parents & teachers already subscribed', 'flatsome-child' ); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
