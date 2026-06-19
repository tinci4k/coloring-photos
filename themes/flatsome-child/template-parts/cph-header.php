<?php
// Mega menu: group WooCommerce categories by parent
$colors = [ '#FF4D6D','#FF9E2C','#FFD23F','#38D39F','#4D9DE0' ];
$tints  = [ '#FFEEF1','#FFF3E3','#FFF9E0','#E8F8F1','#EAF3FC' ];

// Top-level categories become tabs
$top_cats = get_terms([
    'taxonomy'   => 'product_cat',
    'hide_empty' => true,
    'parent'     => 0,
    'exclude'    => [ get_option('default_product_cat') ],
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 4,
]);

// Build tab panels: each top-level cat + its children
$tabs = [];
if ( ! is_wp_error( $top_cats ) ) {
    foreach ( $top_cats as $top ) {
        $children = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
            'parent'     => $top->term_id,
            'orderby'    => 'count',
            'order'      => 'DESC',
            'number'     => 9,
        ]);
        $tabs[] = [
            'term'     => $top,
            'children' => is_wp_error( $children ) ? [] : $children,
        ];
    }
}

$momo_wave = get_stylesheet_directory_uri() . '/assets/images/momo/momo-wave.svg';
$logo_url  = home_url('/');
?>
<header class="cph-nav" role="banner">
  <div class="cph-wrap">
    <div class="cph-nav__inner">

      <!-- Logo -->
      <a href="<?php echo esc_url( $logo_url ); ?>" class="cph-logo" aria-label="coloring.photos home">
        <img src="<?php echo esc_url( $momo_wave ); ?>" width="34" height="42" alt="">
        <span class="cph-logo__text">coloring<span>.photos</span></span>
      </a>

      <!-- Desktop nav links -->
      <nav class="cph-navlinks" aria-label="Primary navigation">

        <?php if ( ! empty( $tabs ) ) : ?>
        <!-- Browse mega menu -->
        <div class="cph-browse-wrap">
          <button class="cph-browse-btn" aria-haspopup="true" aria-expanded="false">
            <?php esc_html_e( 'Browse', 'flatsome-child' ); ?>
            <svg class="cph-browse-caret" width="11" height="11" viewBox="0 0 12 12" fill="none" aria-hidden="true">
              <path d="M2.5 4.5 L6 8 L9.5 4.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>

          <div class="cph-mega" role="dialog" aria-label="Browse categories">
            <!-- Tabs -->
            <div class="cph-mega__tabs" role="tablist">
              <?php foreach ( $tabs as $idx => $tab ) : ?>
                <button class="cph-mega__tab <?php echo $idx === 0 ? 'is-active' : ''; ?>"
                        role="tab" data-tab="tab-<?php echo esc_attr( $idx ); ?>"
                        aria-selected="<?php echo $idx === 0 ? 'true' : 'false'; ?>">
                  <?php echo esc_html( $tab['term']->name ); ?>
                </button>
              <?php endforeach; ?>
            </div>

            <!-- Panels -->
            <?php foreach ( $tabs as $idx => $tab ) : ?>
              <div class="cph-mega__grid cph-mega__panel"
                   data-panel="tab-<?php echo esc_attr( $idx ); ?>"
                   role="tabpanel"
                   style="<?php echo $idx !== 0 ? 'display:none;' : ''; ?>">
                <?php foreach ( $tab['children'] as $ci => $child ) :
                  $c_color = $colors[ $ci % 5 ];
                  $c_tint  = $tints[ $ci % 5 ];
                  $c_link  = get_term_link( $child );
                  // Get grandchildren names as subline
                  $grandkids = get_terms(['taxonomy'=>'product_cat','parent'=>$child->term_id,'hide_empty'=>true,'number'=>3,'fields'=>'names']);
                  if ( ! is_wp_error($grandkids) && ! empty($grandkids) ) {
                      $subline = implode(' · ', $grandkids);
                  } elseif ( $child->description ) {
                      $subline = wp_strip_all_tags( $child->description );
                      $subline = mb_substr( $subline, 0, 60 );
                  } else {
                      $subline = $child->count . ' ' . __('pages', 'flatsome-child');
                  }
                ?>
                  <a href="<?php echo esc_url( $c_link ); ?>" class="cph-mega__item">
                    <span class="cph-mega__icon" style="background:<?php echo esc_attr( $c_tint ); ?>;">
                      <span style="font-size:18px;">🎨</span>
                    </span>
                    <span>
                      <span class="cph-mega__item-name">
                        <?php echo esc_html( $child->name ); ?>
                        <span style="color:<?php echo esc_attr( $c_color ); ?>;font-weight:700;">›</span>
                      </span>
                      <?php if ( $subline ) : ?>
                        <span class="cph-mega__item-sub"><?php echo esc_html( $subline ); ?></span>
                      <?php endif; ?>
                    </span>
                  </a>
                <?php endforeach; ?>
              </div>
            <?php endforeach; ?>

            <div class="cph-mega__footer">
              <a href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>"><?php esc_html_e( 'View all categories →', 'flatsome-child' ); ?></a>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <a href="<?php echo esc_url( add_query_arg( 'orderby', 'date', wc_get_page_permalink('shop') ) ); ?>" class="cph-navlink cph-navlink--new">
          <?php esc_html_e( 'New', 'flatsome-child' ); ?>
        </a>
        <a href="<?php echo esc_url( get_permalink( get_page_by_path('difficulty-guide') ) ?: '#' ); ?>" class="cph-navlink">
          <?php esc_html_e( 'Difficulty Guide', 'flatsome-child' ); ?>
        </a>
        <a href="<?php echo esc_url( get_permalink( get_page_by_path('blog') ) ?: get_post_type_archive_link('post') ); ?>" class="cph-navlink">
          <?php esc_html_e( 'Blog', 'flatsome-child' ); ?>
        </a>
        <a href="<?php echo esc_url( get_permalink( get_page_by_path('about') ) ?: get_permalink( get_page_by_path('about-us') ) ?: '#' ); ?>" class="cph-navlink">
          <?php esc_html_e( 'About Us', 'flatsome-child' ); ?>
        </a>
      </nav>

      <div class="cph-nav__spacer"></div>

      <!-- Search -->
      <form class="cph-search" role="search" method="get" action="<?php echo esc_url( home_url('/') ); ?>" style="margin:0;padding-top:0;padding-bottom:0;">
        <svg width="16" height="16" viewBox="0 0 18 18" fill="none" aria-hidden="true">
          <circle cx="8" cy="8" r="6" stroke="#B6AEB8" stroke-width="1.8"/>
          <path d="M12.5 12.5 L16 16" stroke="#B6AEB8" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
        <input type="search" name="s" placeholder="<?php esc_attr_e( 'Search coloring pages…', 'flatsome-child' ); ?>" aria-label="<?php esc_attr_e( 'Search', 'flatsome-child' ); ?>">
        <input type="hidden" name="post_type" value="product">
      </form>

      <!-- Pinterest -->
      <a href="https://pinterest.com/coloringphotos" class="cph-pinterest" aria-label="Pinterest" target="_blank" rel="noopener">
        <svg width="19" height="19" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M12 2C6.48 2 2 6.48 2 12c0 4.08 2.45 7.59 5.96 9.13-.08-.78-.16-1.97.03-2.82.18-.76 1.15-4.85 1.15-4.85s-.29-.59-.29-1.46c0-1.37.79-2.39 1.78-2.39.84 0 1.25.63 1.25 1.39 0 .85-.54 2.11-.82 3.28-.23.98.49 1.78 1.46 1.78 1.75 0 3.1-1.85 3.1-4.52 0-2.36-1.7-4.01-4.12-4.01-2.81 0-4.46 2.11-4.46 4.29 0 .85.33 1.76.74 2.25.08.1.09.18.07.29l-.27 1.1c-.04.18-.14.22-.33.13-1.23-.57-2-2.37-2-3.81 0-3.1 2.25-5.95 6.5-5.95 3.41 0 6.07 2.43 6.07 5.68 0 3.39-2.14 6.12-5.1 6.12-1 0-1.93-.52-2.25-1.13l-.61 2.33c-.22.85-.82 1.92-1.22 2.57.92.28 1.89.43 2.91.43 5.52 0 10-4.48 10-10S17.52 2 12 2z"/></svg>
      </a>

      <!-- Burger -->
      <button class="cph-burger" id="cph-burger" aria-label="<?php esc_attr_e( 'Open menu', 'flatsome-child' ); ?>" aria-expanded="false" aria-controls="cph-mobile-panel">
        <svg width="20" height="20" viewBox="0 0 22 22" fill="none" aria-hidden="true"><path d="M3 6h16M3 11h16M3 16h16" stroke="#1A1A2E" stroke-width="2" stroke-linecap="round"/></svg>
      </button>

    </div>
  </div>

  <!-- Mobile panel -->
  <div class="cph-mobile-panel" id="cph-mobile-panel" role="navigation" aria-label="Mobile navigation">
    <form class="cph-mobile-search" role="search" method="get" action="<?php echo esc_url( home_url('/') ); ?>">
      <svg width="16" height="16" viewBox="0 0 18 18" fill="none" aria-hidden="true"><circle cx="8" cy="8" r="6" stroke="#B6AEB8" stroke-width="1.8"/><path d="M12.5 12.5 L16 16" stroke="#B6AEB8" stroke-width="1.8" stroke-linecap="round"/></svg>
      <input type="search" name="s" placeholder="<?php esc_attr_e( 'Search coloring pages…', 'flatsome-child' ); ?>">
      <input type="hidden" name="post_type" value="product">
    </form>

    <?php foreach ( $tabs as $idx => $tab ) : ?>
      <div class="cph-mobile-group">
        <button class="cph-mobile-group__btn" style="color:<?php echo esc_attr( $colors[ $idx % 5 ] ); ?>;">
          <?php echo esc_html( $tab['term']->name ); ?>
          <span class="cph-acc-sign" aria-hidden="true">+</span>
        </button>
        <div class="cph-mobile-group__cats">
          <?php foreach ( $tab['children'] as $child ) : ?>
            <a href="<?php echo esc_url( get_term_link($child) ); ?>" class="cph-mobile-group__cat">
              <?php echo esc_html( $child->name ); ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>

    <div class="cph-mobile-links">
      <a href="<?php echo esc_url( add_query_arg( 'orderby', 'date', wc_get_page_permalink('shop') ) ); ?>"><?php esc_html_e( 'New', 'flatsome-child' ); ?></a>
      <a href="<?php echo esc_url( get_post_type_archive_link('post') ?: '#' ); ?>"><?php esc_html_e( 'Blog', 'flatsome-child' ); ?></a>
      <a href="<?php echo esc_url( get_permalink( get_page_by_path('about') ) ?: get_permalink( get_page_by_path('about-us') ) ?: '#' ); ?>"><?php esc_html_e( 'About Us', 'flatsome-child' ); ?></a>
    </div>
  </div>
</header>
