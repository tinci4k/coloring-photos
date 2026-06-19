<?php
/**
 * Homepage template — custom CPH design.
 * Bypasses Flatsome's header/footer completely.
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class('cph-page'); ?>>
<?php wp_body_open(); ?>

<?php get_template_part('template-parts/cph-header'); ?>

<main id="cph-main" role="main">

  <?php get_template_part('template-parts/home/hero'); ?>

  <!-- Ad zone 1 -->
  <div class="cph-wrap cph-ad-wrap">
    <div class="cph-ad"><span>Advertisement · 728 × 90</span></div>
  </div>

  <?php get_template_part('template-parts/home/who-for'); ?>
  <?php get_template_part('template-parts/home/popular'); ?>
  <?php get_template_part('template-parts/home/categories'); ?>
  <?php get_template_part('template-parts/home/story'); ?>
  <?php get_template_part('template-parts/home/difficulty'); ?>

  <!-- Ad zone 2 -->
  <div class="cph-wrap cph-ad-wrap" style="padding-top:34px;">
    <div class="cph-ad"><span>Advertisement · 728 × 90</span></div>
  </div>

  <?php get_template_part('template-parts/home/guides'); ?>
  <?php get_template_part('template-parts/home/newsletter'); ?>

  <!-- SEO text -->
  <section class="cph-seo">
    <div class="cph-seo__inner">
      <h2><?php esc_html_e( 'Free printable coloring pages for kids and adults', 'flatsome-child' ); ?></h2>
      <p><?php esc_html_e( 'coloring.photos is a free library of printable coloring pages spanning more than 20 categories — from animals, fantasy and nature to animated movies, cartoon characters, holidays and educational worksheets. Every page is hand-picked and rated by difficulty so parents, teachers and caregivers can match the right page to the right child in seconds.', 'flatsome-child' ); ?></p>
      <p><?php esc_html_e( 'Coloring helps children develop fine motor skills, focus and color recognition while giving them a calm, screen-free way to be creative. Our difficulty system ranges from chunky toddler-friendly shapes for ages two and up to intricate mandalas and patterns for older kids and adults.', 'flatsome-child' ); ?></p>
      <p><?php esc_html_e( 'Browse by category or character, print at home on standard A4 or US Letter paper, and start coloring in minutes. Everything on coloring.photos is free to print and use at home, in the classroom, or in daycare and preschool settings — no sign-up required.', 'flatsome-child' ); ?></p>
    </div>
  </section>

</main>

<?php get_template_part('template-parts/cph-footer'); ?>

<?php wp_footer(); ?>
</body>
</html>
