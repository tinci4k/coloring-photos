<?php
$momo_color = get_stylesheet_directory_uri() . '/assets/images/momo/momo-color.svg';
$levels     = function_exists('cph_difficulty_levels') ? cph_difficulty_levels() : [];
?>
<section class="cph-section" id="cph-difficulty">
  <div class="cph-wrap">
    <div class="cph-section-head">
      <div class="cph-diff-head" style="justify-content:center;">
        <img src="<?php echo esc_url( $momo_color ); ?>" width="58" height="72" alt="" aria-hidden="true" style="flex:none;">
        <div style="text-align:left;">
          <div class="cph-label"><?php esc_html_e( 'Difficulty system', 'flatsome-child' ); ?></div>
          <h2 class="cph-section-title" style="margin-top:6px;"><?php esc_html_e( 'Find the right page for your child', 'flatsome-child' ); ?></h2>
        </div>
      </div>
    </div>

    <div class="cph-g5">
      <?php foreach ( $levels as $n => $lvl ) :
        $icon_url = get_stylesheet_directory_uri() . '/assets/difficulty/difficulty-level-' . $n . '.svg';
      ?>
        <div class="cph-diff-card" style="border-top-color:<?php echo esc_attr( $lvl['color'] ); ?>;">
          <div class="cph-diff-card__icon-wrap">
            <img src="<?php echo esc_url( $icon_url ); ?>" width="64" height="64" alt="Level <?php echo esc_attr( $n ); ?>">
            <span class="cph-diff-card__num" style="background:<?php echo esc_attr( $lvl['color'] ); ?>;"><?php echo esc_html( $n ); ?></span>
          </div>
          <div class="cph-diff-card__name"><?php echo esc_html( $lvl['name'] ); ?></div>
          <div class="cph-diff-card__ages" style="background:<?php echo esc_attr( $lvl['tint'] ); ?>;color:<?php echo esc_attr( $lvl['deep'] ); ?>;"><?php echo esc_html( $lvl['ages'] ); ?></div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="cph-diff-desc">
      <p><?php esc_html_e( 'Not all coloring pages are created equal. Our five levels go from chunky toddler shapes to intricate detail — so every page meets little hands exactly where they are.', 'flatsome-child' ); ?></p>
      <a href="<?php echo esc_url( get_permalink( get_page_by_path('difficulty-guide') ) ?: '#' ); ?>"><?php esc_html_e( 'See difficulty explained →', 'flatsome-child' ); ?></a>
    </div>
  </div>
</section>
