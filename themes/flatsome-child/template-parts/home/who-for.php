<?php
$momo_happy     = get_stylesheet_directory_uri() . '/assets/images/momo/momo-happy.svg';
$momo_celebrate = get_stylesheet_directory_uri() . '/assets/images/momo/momo-celebrate.svg';
$momo_love      = get_stylesheet_directory_uri() . '/assets/images/momo/momo-love.svg';

$audience = [
    [
        'title'      => __( 'Parents', 'flatsome-child' ),
        'momo'       => $momo_happy,
        'color'      => '#FF4D6D', 'deep' => '#E11D48', 'tint' => '#FFF4F6', 'border' => '#FCE0E6',
        'desc'       => __( "Rainy day? Quiet time? We've got you covered with hundreds of pages your kids will actually want to color.", 'flatsome-child' ),
        'stat'       => __( '4,500+ pages across 50+ categories', 'flatsome-child' ),
        'levels'     => [1,2,3,4,5],
        'levelLabel' => __( 'All levels · 1–5', 'flatsome-child' ),
    ],
    [
        'title'      => __( 'Teachers & Educators', 'flatsome-child' ),
        'momo'       => $momo_celebrate,
        'color'      => '#38D39F', 'deep' => '#0E9F6E', 'tint' => '#EDF9F4', 'border' => '#D5F0E6',
        'desc'       => __( 'Printable pages that fit every lesson — themed by topic, season, or subject and ready to hand out in minutes.', 'flatsome-child' ),
        'stat'       => __( 'Educational & themed collections', 'flatsome-child' ),
        'levels'     => [2,3,4],
        'levelLabel' => __( 'Levels 2–4 · ages 4–12', 'flatsome-child' ),
    ],
    [
        'title'      => __( 'Caregivers', 'flatsome-child' ),
        'momo'       => $momo_love,
        'color'      => '#4D9DE0', 'deep' => '#2563C9', 'tint' => '#EAF3FC', 'border' => '#CFE5F6',
        'desc'       => __( 'Easy to find the right page for every child in your care — sorted by age and difficulty so no one gets frustrated.', 'flatsome-child' ),
        'stat'       => __( '5 difficulty levels, ages 2 to adult', 'flatsome-child' ),
        'levels'     => [1,2,3],
        'levelLabel' => __( 'Levels 1–3 · ages 2–9', 'flatsome-child' ),
    ],
];
?>
<section class="cph-section cph-section--first" id="cph-who-for">
  <div class="cph-wrap">
    <div class="cph-section-head">
      <div class="cph-label"><?php esc_html_e( "Who it's for", 'flatsome-child' ); ?></div>
      <h2 class="cph-section-title"><?php esc_html_e( 'Made for families, teachers & caregivers', 'flatsome-child' ); ?></h2>
    </div>

    <div class="cph-g3">
      <?php foreach ( $audience as $a ) : ?>
        <div class="cph-audience-card" style="background:<?php echo esc_attr( $a['tint'] ); ?>;border-color:<?php echo esc_attr( $a['border'] ); ?>;">
          <div class="cph-audience-card__icon">
            <img src="<?php echo esc_url( $a['momo'] ); ?>" width="44" height="55" alt="" aria-hidden="true">
          </div>
          <h3><?php echo esc_html( $a['title'] ); ?></h3>
          <p><?php echo esc_html( $a['desc'] ); ?></p>
          <div class="cph-audience-card__stat">
            <span class="cph-audience-card__stat-dot" style="background:<?php echo esc_attr( $a['color'] ); ?>;">
              <svg width="11" height="11" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M3 7.4l2.6 2.6L11 4.2" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <span class="cph-audience-card__stat-text" style="color:<?php echo esc_attr( $a['deep'] ); ?>;"><?php echo esc_html( $a['stat'] ); ?></span>
          </div>
          <div class="cph-audience-card__diff">
            <div class="cph-audience-card__diff-inner" style="border-color:<?php echo esc_attr( $a['border'] ); ?>;">
              <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:10px;">
                <span class="cph-audience-card__diff-label" style="color:<?php echo esc_attr( $a['color'] ); ?>;"><?php esc_html_e( 'Best difficulty', 'flatsome-child' ); ?></span>
                <span class="cph-audience-card__diff-level"><?php echo esc_html( $a['levelLabel'] ); ?></span>
              </div>
              <div class="cph-diff-icons">
                <?php foreach ( $a['levels'] as $lv ) : ?>
                  <img class="cph-diff-icon" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/difficulty/difficulty-level-' . $lv . '.svg' ); ?>" width="30" height="30" alt="Level <?php echo esc_attr( $lv ); ?>">
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
