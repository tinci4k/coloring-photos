<?php
/**
 * Template Name: Difficulty Guide
 */
if ( ! defined('ABSPATH') ) exit;

$levels = [
    1 => [ 'name'=>'Tiny Artist',    'ages'=>'Ages 2–4',  'color'=>'#FF4D6D','deep'=>'#E11D48','tint'=>'#FFEEF1','border'=>'#FAD9E0','glow'=>'rgba(255,77,109,.35)',
           'tagline'=>'The very first colouring pages — built for fists, not fingers.',
           'bullets'=>['Holds a crayon in a full fist and makes big, happy marks','Just starting to keep colour roughly on the page','One or two huge shapes, extra-thick outlines','Impossible to get "wrong" — all about the joy'],
           'example'=>'Big Friendly Balloon','row_bg'=>'#ffffff' ],
    2 => [ 'name'=>'Little Explorer', 'ages'=>'Ages 4–6',  'color'=>'#FF9E2C','deep'=>'#D9650A','tint'=>'#FFF3E3','border'=>'#F7E4C6','glow'=>'rgba(255,158,44,.35)',
           'tagline'=>'Friendly pictures with room to roam and big bold lines.',
           'bullets'=>['Steadier grip, beginning to aim for the lines','Can happily focus for 10–15 minutes at a time','Friendly characters with a handful of large zones','Bold outlines and simple, uncluttered backgrounds'],
           'example'=>'Sunny Street Houses','row_bg'=>'#FFF3E3' ],
    3 => [ 'name'=>'Junior Artist',  'ages'=>'Ages 6–9',  'color'=>'#FFD23F','deep'=>'#B7791F','tint'=>'#FFF9E0','border'=>'#F3EAC0','glow'=>'rgba(255,210,63,.4)',
           'tagline'=>'Real scenes to fill in, with a little more to think about.',
           'bullets'=>['Confident pencil control, colours inside the lines','Loves choosing their own colour combinations','More zones, thinner lines, a bit of background','Recognisable scenes with several elements'],
           'example'=>'Garden Butterfly Patch','row_bg'=>'#ffffff' ],
    4 => [ 'name'=>'Creative Pro',   'ages'=>'Ages 9–12', 'color'=>'#38D39F','deep'=>'#0E9F6E','tint'=>'#E8F8F1','border'=>'#D5EFE3','glow'=>'rgba(56,211,159,.35)',
           'tagline'=>'Detailed pages that reward patience and a steady hand.',
           'bullets'=>['Patient and precise with smaller areas','Happy to spend 30+ minutes on a single page','Detailed scenes, fine lines, lots of little zones','Patterns and texture start to appear'],
           'example'=>'Jungle Vehicle Chase','row_bg'=>'#E8F8F1' ],
    5 => [ 'name'=>'Master Colorist','ages'=>'Ages 12+',  'color'=>'#4D9DE0','deep'=>'#2563C9','tint'=>'#EAF3FC','border'=>'#D7E8F8','glow'=>'rgba(77,157,224,.35)',
           'tagline'=>'Intricate, near-adult artwork for confident colourists.',
           'bullets'=>['Steady, careful hands and real staying power','Enjoys shading, blending and tiny detail','Intricate line work — mandalas and dense scenes','Near-adult complexity, dozens of zones'],
           'example'=>'Enchanted Mandala','row_bg'=>'#ffffff' ],
];

$steps = [
    [ 'n'=>1,'color'=>'#FF4D6D','deep'=>'#E11D48','tint'=>'#FFEEF1','glow'=>'rgba(255,77,109,.35)',
      'title'=>'Look at the detail','desc'=>'Count the zones and check the line thickness. Fewer, bigger shapes mean an easier page.','icon'=>'detail' ],
    [ 'n'=>2,'color'=>'#38D39F','deep'=>'#0E9F6E','tint'=>'#E8F8F1','glow'=>'rgba(56,211,159,.35)',
      'title'=>'Think about focus time','desc'=>'Match the level to how long your child happily sits and colours in one go.','icon'=>'clock' ],
    [ 'n'=>3,'color'=>'#FF9E2C','deep'=>'#D9650A','tint'=>'#FFF3E3','glow'=>'rgba(255,158,44,.35)',
      'title'=>'When in doubt, go lower','desc'=>'A slightly easy page builds confidence — and you can always move up next time.','icon'=>'down' ],
    [ 'n'=>4,'color'=>'#4D9DE0','deep'=>'#2563C9','tint'=>'#EAF3FC','glow'=>'rgba(77,157,224,.35)',
      'title'=>'Let older kids choose','desc'=>'From about eight, they usually know exactly what they are in the mood for.','icon'=>'star' ],
];

$reviews = [
    [ 'n'=>1,'parent'=>'Lucija','child'=>'mum of Stjepan (3)','initial'=>'L','quote'=>'Stjepan can actually finish a Tiny Artist page on his own — those big chunky shapes are perfect for his little fists.' ],
    [ 'n'=>2,'parent'=>'Marko', 'child'=>'dad of Erika (4)',  'initial'=>'M','quote'=>'Level 2 hit the sweet spot. Erika feels grown-up colouring "real" pictures but never gets frustrated.' ],
    [ 'n'=>3,'parent'=>'Filip', 'child'=>'dad of Katarina (6)','initial'=>'F','quote'=>'Junior Artist pages keep Katarina busy for a whole afternoon — and she is so proud of the result.' ],
    [ 'n'=>4,'parent'=>'Nikolina','child'=>'mum of Ruta (7)', 'initial'=>'N','quote'=>'Ruta is advanced for seven, so Creative Pro is exactly her challenge. The detail really keeps her focused.' ],
    [ 'n'=>5,'parent'=>'Ivana', 'child'=>'mum of Lea (8)',   'initial'=>'I','quote'=>'Lea loves the Master Colorist mandalas. It has become our quiet wind-down together before bed.' ],
];

$faqs = [
    [ 'q'=>'My child is 5 but very advanced — which level?',
      'a'=>'Start at Level 2 (their age) and print one Level 3 page alongside it. If they breeze through, move up. The levels are guides, not rules — children happily jump around.' ],
    [ 'q'=>'Can adults use the higher difficulty pages?',
      'a'=>'Absolutely. Levels 4 and 5 — especially the mandalas and patterns — are hugely popular with grown-ups for relaxing, mindful colouring.' ],
    [ 'q'=>'How is the difficulty level determined?',
      'a'=>'We look at three things: line thickness, the number of separate zones, and the amount of fine detail. Every page is reviewed by hand, not by an algorithm.' ],
    [ 'q'=>'What if my child finds a page too hard?',
      'a'=>'Totally normal. Drop down a level, finish a page together, and try again in a few weeks. Colouring should feel like fun, never like homework.' ],
    [ 'q'=>'Do all pages have a difficulty rating?',
      'a'=>'Yes — every one of our 4,500+ pages carries a level from 1 to 5, shown as a coloured badge on both the thumbnail and the product page.' ],
];

$shop_url    = wc_get_page_permalink('shop');
$child_uri   = get_stylesheet_directory_uri();

// Step icons SVG
$step_icons = [
    'detail' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none"><rect x="4" y="3" width="16" height="18" rx="2" stroke="%s" stroke-width="2"/><path d="M8 8h8M8 12h8M8 16h5" stroke="%s" stroke-width="2" stroke-linecap="round"/></svg>',
    'clock'  => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="%s" stroke-width="2"/><path d="M12 7v5l3.5 2" stroke="%s" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    'down'   => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M12 5v13M6 12l6 6 6-6" stroke="%s" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    'star'   => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M12 2l2.7 5.5L21 8.6l-4.5 4.4 1.1 6.2L12 16.2l-5.6 3 1.1-6.2L3 8.6l6.3-.9L12 2z" stroke="%s" stroke-width="2" stroke-linejoin="round"/></svg>',
];
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php wp_title('—', true, 'right'); ?> coloring.photos</title>
<?php wp_head(); ?>
</head>
<body <?php body_class('cph-page'); ?>>
<?php wp_body_open(); ?>

<!-- Momo SVG symbols -->
<svg width="0" height="0" style="position:absolute;" aria-hidden="true"><defs>
  <symbol id="g-color" viewBox="0 0 120 150">
    <rect x="35.5" y="30" width="9" height="26" rx="4.5" fill="#FF4D6D"/>
    <rect x="45.5" y="22" width="9" height="34" rx="4.5" fill="#FF9E2C"/>
    <rect x="55.5" y="16" width="9" height="40" rx="4.5" fill="#FFD23F"/>
    <rect x="65.5" y="22" width="9" height="34" rx="4.5" fill="#38D39F"/>
    <rect x="75.5" y="30" width="9" height="26" rx="4.5" fill="#4D9DE0"/>
    <ellipse cx="47" cy="129" rx="12" ry="7.5" fill="#C9197A"/>
    <ellipse cx="73" cy="129" rx="12" ry="7.5" fill="#C9197A"/>
    <path d="M90 86 Q107 82 108 96" fill="none" stroke="#ED2C92" stroke-width="12" stroke-linecap="round"/>
    <rect x="26" y="46" width="68" height="80" rx="32" fill="#ED2C92"/>
    <ellipse cx="60" cy="104" rx="20" ry="16" fill="#F792C2"/>
    <circle cx="37" cy="88" r="5.5" fill="#FF7FB0"/>
    <circle cx="83" cy="88" r="5.5" fill="#FF7FB0"/>
    <circle cx="48" cy="74" r="13" fill="#fff"/>
    <circle cx="72" cy="74" r="13" fill="#fff"/>
    <circle cx="50" cy="79" r="5" fill="#2A2030"/>
    <circle cx="74" cy="79" r="5" fill="#2A2030"/>
    <circle cx="52" cy="77" r="1.6" fill="#fff"/>
    <circle cx="76" cy="77" r="1.6" fill="#fff"/>
    <ellipse cx="64" cy="101" rx="4" ry="4.5" fill="#3A2233"/>
    <g transform="rotate(-20 64 110)">
      <rect x="44" y="103" width="38" height="13" rx="3.5" fill="#4D9DE0"/>
      <rect x="58" y="103" width="6" height="13" fill="#fff" opacity="0.55"/>
      <polygon points="82,103 95,109.5 82,116" fill="#FFD23F"/>
    </g>
    <path d="M30 92 Q28 110 56 108" fill="none" stroke="#ED2C92" stroke-width="12" stroke-linecap="round"/>
  </symbol>
  <symbol id="g-think" viewBox="0 0 120 150">
    <circle cx="92" cy="52" r="2.6" fill="#F0B9D6"/>
    <circle cx="100" cy="43" r="3.6" fill="#F0A6CC"/>
    <circle cx="110" cy="31" r="5.2" fill="#ED8FBE"/>
    <rect x="35.5" y="30" width="9" height="26" rx="4.5" fill="#FF4D6D"/>
    <rect x="45.5" y="22" width="9" height="34" rx="4.5" fill="#FF9E2C"/>
    <rect x="55.5" y="16" width="9" height="40" rx="4.5" fill="#FFD23F"/>
    <rect x="65.5" y="22" width="9" height="34" rx="4.5" fill="#38D39F"/>
    <rect x="75.5" y="30" width="9" height="26" rx="4.5" fill="#4D9DE0"/>
    <ellipse cx="47" cy="129" rx="12" ry="7.5" fill="#C9197A"/>
    <ellipse cx="73" cy="129" rx="12" ry="7.5" fill="#C9197A"/>
    <path d="M90 86 Q107 82 108 96" fill="none" stroke="#ED2C92" stroke-width="12" stroke-linecap="round"/>
    <path d="M30 84 Q12 70 16 50" fill="none" stroke="#ED2C92" stroke-width="12" stroke-linecap="round"/>
    <rect x="26" y="46" width="68" height="80" rx="32" fill="#ED2C92"/>
    <ellipse cx="60" cy="104" rx="20" ry="16" fill="#F792C2"/>
    <circle cx="37" cy="88" r="5.5" fill="#FF7FB0"/>
    <circle cx="83" cy="88" r="5.5" fill="#FF7FB0"/>
    <circle cx="48" cy="74" r="13" fill="#fff"/>
    <circle cx="72" cy="74" r="13" fill="#fff"/>
    <circle cx="50" cy="76" r="5.5" fill="#2A2030"/>
    <circle cx="74" cy="76" r="5.5" fill="#2A2030"/>
    <path d="M47 90 Q60 83 73 90" fill="none" stroke="#2A2030" stroke-width="3" stroke-linecap="round"/>
    <path d="M48 82 Q52 79 56 82M64 82 Q68 79 72 82" fill="none" stroke="#2A2030" stroke-width="2.5" stroke-linecap="round"/>
  </symbol>
</defs></svg>

<?php get_template_part('template-parts/cph-header'); ?>

<!-- ═══ HERO ════════════════════════════════════════════════════════ -->
<section style="background:linear-gradient(160deg,#FFF6FB 0%,#fff 65%);padding:72px 0 60px;overflow:hidden;">
  <div class="gd-wrap">
    <div class="gd-hero">
      <div>
        <!-- Badge -->
        <div style="display:inline-flex;align-items:center;gap:9px;background:#fff;border:1px solid #F2DCEA;border-radius:999px;padding:6px 14px;margin-bottom:20px;box-shadow:0 4px 14px rgba(232,53,172,.08);">
          <span style="display:flex;gap:4px;">
            <span style="width:9px;height:9px;border-radius:50%;background:#FF4D6D;display:inline-block;"></span>
            <span style="width:9px;height:9px;border-radius:50%;background:#FF9E2C;display:inline-block;"></span>
            <span style="width:9px;height:9px;border-radius:50%;background:#FFD23F;display:inline-block;"></span>
            <span style="width:9px;height:9px;border-radius:50%;background:#38D39F;display:inline-block;"></span>
            <span style="width:9px;height:9px;border-radius:50%;background:#4D9DE0;display:inline-block;"></span>
          </span>
          <span style="font-weight:700;font-size:12.5px;letter-spacing:.3px;color:#A23C7C;">Five levels · ages 2 to 12+</span>
        </div>
        <h1 style="font-family:'Fredoka',sans-serif;font-weight:600;font-size:clamp(34px,5vw,56px);line-height:1.03;letter-spacing:-1.5px;color:#1A1A2E;margin:0 0 18px;">Find the right page for every <span style="color:#E835AC;">little artist</span></h1>
        <p style="font-size:18px;line-height:1.6;color:#5C5866;margin:0 0 28px;max-width:480px;">Every page on coloring.photos is rated from 1 to 5 by how tricky it is to colour. It takes the guesswork out of choosing — so the page always fits the hands holding the crayon.</p>
        <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
          <a href="#gd-levels" style="display:inline-flex;align-items:center;gap:9px;background:#E835AC;color:#fff;font-family:'Fredoka',sans-serif;font-weight:600;font-size:16px;padding:14px 24px;border-radius:14px;box-shadow:0 8px 22px rgba(232,53,172,.3);text-decoration:none;">
            Explore the levels
            <svg width="16" height="16" viewBox="0 0 18 18" fill="none"><path d="M9 3v11M4 9l5 5 5-5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
          <a href="#gd-table" style="font-family:'Fredoka',sans-serif;font-weight:600;font-size:15.5px;color:#A23C7C;text-decoration:none;">Jump to the quick table →</a>
        </div>
      </div>
      <!-- Momo -->
      <div style="position:relative;display:flex;align-items:center;justify-content:center;min-height:320px;">
        <div style="position:absolute;width:300px;height:300px;border-radius:50%;background:radial-gradient(circle,#FCE3F1 0%,rgba(252,227,241,0) 68%);"></div>
        <span style="position:absolute;top:10%;left:4%;width:40px;height:40px;animation:cphFloat2 5s ease-in-out infinite;">
          <img src="<?php echo esc_url( $child_uri . '/assets/difficulty/difficulty-level-1.svg' ); ?>" width="40" height="40" alt="">
        </span>
        <span style="position:absolute;bottom:8%;right:4%;width:44px;height:44px;animation:cphFloat 5.5s ease-in-out infinite .4s;">
          <img src="<?php echo esc_url( $child_uri . '/assets/difficulty/difficulty-level-5.svg' ); ?>" width="44" height="44" alt="">
        </span>
        <svg viewBox="0 0 120 150" width="246" height="308" style="position:relative;z-index:1;animation:cphFloat 4.6s ease-in-out infinite;" aria-hidden="true"><use href="#g-color"/></svg>
      </div>
    </div>
  </div>
</section>

<!-- ═══ QUICK REFERENCE TABLE ════════════════════════════════════════ -->
<section style="padding:58px 0 8px;" id="gd-table">
  <div class="gd-wrap" style="max-width:740px;">
    <div style="text-align:center;max-width:600px;margin:0 auto 30px;">
      <span style="font-family:'Fredoka',sans-serif;font-weight:600;font-size:13px;letter-spacing:1.6px;color:#E835AC;text-transform:uppercase;">Quick reference</span>
      <h2 style="font-family:'Fredoka',sans-serif;font-weight:600;font-size:clamp(26px,3.4vw,38px);line-height:1.08;letter-spacing:-0.8px;color:#1A1A2E;margin:8px 0 10px;">The whole system, at a glance</h2>
      <p style="font-size:15px;color:#6A6573;margin:0;">In a hurry? Match your child's age to a level and you're ready to print.</p>
    </div>
    <div style="border:1.5px solid #EEE9EC;border-radius:18px;overflow:hidden;background:#fff;">
      <!-- Table header -->
      <div style="display:grid;grid-template-columns:1.4fr 1fr 1.4fr;padding:12px 24px;background:#F8F4F7;border-bottom:1px solid #EEE9EC;gap:16px;">
        <?php foreach ( ['Age','Level','Name'] as $h ) : ?>
          <span style="font-family:'Fredoka',sans-serif;font-size:12px;font-weight:700;letter-spacing:.9px;color:#9A8FA0;text-transform:uppercase;"><?php echo $h; ?></span>
        <?php endforeach; ?>
      </div>
      <?php foreach ( $levels as $n => $lvl ) : ?>
      <div style="display:grid;grid-template-columns:1.4fr 1fr 1.4fr;padding:16px 24px;border-bottom:1px solid #F3EEF2;background:#fff;gap:16px;align-items:center;">
        <span style="font-size:15px;font-weight:700;color:#1A1A2E;"><?php echo esc_html($lvl['ages']); ?></span>
        <span style="display:inline-flex;align-items:center;gap:8px;font-family:'Fredoka',sans-serif;font-weight:700;font-size:15px;color:#1A1A2E;">
          <span style="flex:none;width:28px;height:28px;border-radius:50%;background:<?php echo esc_attr($lvl['color']); ?>;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;font-family:'Fredoka',sans-serif;"><?php echo $n; ?></span>
        </span>
        <span style="font-family:'Fredoka',sans-serif;font-weight:500;font-size:15px;color:#1A1A2E;"><?php echo esc_html($lvl['name']); ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══ FIVE LEVELS ══════════════════════════════════════════════════ -->
<section style="padding:56px 0 10px;" id="gd-levels">
  <div style="text-align:center;max-width:640px;margin:0 auto 12px;padding:0 28px;">
    <span style="font-family:'Fredoka',sans-serif;font-weight:600;font-size:13px;letter-spacing:1.6px;color:#E835AC;text-transform:uppercase;">The five levels</span>
    <h2 style="font-family:'Fredoka',sans-serif;font-weight:600;font-size:clamp(28px,3.8vw,42px);line-height:1.06;letter-spacing:-1px;color:#1A1A2E;margin:10px 0 0;">From chunky shapes to intricate detail</h2>
  </div>
  <?php foreach ( $levels as $n => $lvl ) :
    $rev      = ( $n % 2 === 0 ) ? 'flex-direction:row-reverse;' : '';
    $cat_link = add_query_arg(['meta_key'=>'_cph_difficulty','meta_value'=>$n], $shop_url);
  ?>
  <div style="background:<?php echo esc_attr($lvl['row_bg']); ?>;" id="gd-level-<?php echo $n; ?>">
    <div class="gd-wrap" style="padding:46px 28px;">
      <div class="gd-lvl" style="<?php echo $rev; ?>max-width:900px;margin:0 auto;">
        <!-- Text -->
        <div class="gd-lvl-text">
          <div style="display:flex;align-items:center;gap:13px;margin-bottom:14px;">
            <span style="font-family:'Fredoka',sans-serif;font-weight:600;font-size:20px;color:#fff;background:<?php echo esc_attr($lvl['color']); ?>;width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex:none;box-shadow:0 6px 16px <?php echo esc_attr($lvl['glow']); ?>;"><?php echo $n; ?></span>
            <span style="display:inline-flex;align-items:center;padding:5px 13px;border-radius:999px;background:<?php echo esc_attr($lvl['tint']); ?>;font-weight:800;font-size:13px;color:<?php echo esc_attr($lvl['deep']); ?>;"><?php echo esc_html($lvl['ages']); ?></span>
          </div>
          <h3 style="font-family:'Fredoka',sans-serif;font-weight:600;font-size:clamp(24px,3vw,32px);line-height:1.05;letter-spacing:-0.6px;color:#1A1A2E;margin:0 0 6px;"><?php echo esc_html($lvl['name']); ?></h3>
          <p style="font-size:16px;line-height:1.55;color:#6A6573;margin:0 0 18px;max-width:440px;"><?php echo esc_html($lvl['tagline']); ?></p>
          <div style="display:flex;flex-direction:column;gap:11px;margin-bottom:22px;">
            <?php foreach ( $lvl['bullets'] as $b ) : ?>
            <div style="display:flex;align-items:flex-start;gap:11px;">
              <span style="flex:none;width:22px;height:22px;border-radius:50%;background:<?php echo esc_attr($lvl['tint']); ?>;display:flex;align-items:center;justify-content:center;margin-top:1px;">
                <svg width="12" height="12" viewBox="0 0 14 14" fill="none"><path d="M3 7.4l2.6 2.6L11 4.2" stroke="<?php echo esc_attr($lvl['deep']); ?>" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </span>
              <span style="font-size:15px;line-height:1.5;color:#4A4654;font-weight:600;"><?php echo esc_html($b); ?></span>
            </div>
            <?php endforeach; ?>
          </div>
          <a href="<?php echo esc_url($cat_link); ?>" style="display:inline-flex;align-items:center;gap:8px;font-family:'Fredoka',sans-serif;font-weight:600;font-size:15.5px;color:<?php echo esc_attr($lvl['deep']); ?>;background:<?php echo esc_attr($lvl['tint']); ?>;padding:11px 18px;border-radius:12px;text-decoration:none;transition:transform .15s;">
            Browse <?php echo esc_html($lvl['name']); ?> pages
            <svg width="16" height="16" viewBox="0 0 18 18" fill="none"><path d="M3 9h11M10 4l5 5-5 5" stroke="<?php echo esc_attr($lvl['deep']); ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
        </div>
        <!-- Visual card -->
        <div class="gd-lvl-vis">
          <div style="background:#fff;border:1px solid #EEE9EC;border-top:4px solid <?php echo esc_attr($lvl['color']); ?>;border-radius:22px;padding:24px;box-shadow:0 16px 40px rgba(40,20,40,.08);">
            <!-- Icon display -->
            <div style="display:flex;align-items:center;justify-content:center;background:<?php echo esc_attr($lvl['tint']); ?>;border-radius:16px;padding:22px;margin-bottom:16px;">
              <img src="<?php echo esc_url( $child_uri . '/assets/difficulty/difficulty-level-' . $n . '.svg' ); ?>" width="120" height="120" alt="Level <?php echo $n; ?>">
            </div>
            <!-- Example page row -->
            <div style="display:flex;align-items:center;gap:13px;">
              <div style="flex:none;width:96px;aspect-ratio:210/297;border-radius:11px;border:1.5px dashed #DDD8E0;background:<?php echo esc_attr($lvl['tint']); ?>;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;padding:8px;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="18" height="18" rx="3" stroke="<?php echo esc_attr($lvl['color']); ?>" stroke-width="1.6"/><path d="M3 14l4-4 4 3 3.5-2.5L21 15" stroke="<?php echo esc_attr($lvl['color']); ?>" stroke-width="1.5" stroke-linejoin="round"/></svg>
                <span style="font-size:10px;font-weight:700;color:<?php echo esc_attr($lvl['deep']); ?>;text-align:center;line-height:1.3;">Example<br>page</span>
              </div>
              <div style="min-width:0;">
                <div style="font-weight:800;font-size:10.5px;letter-spacing:.8px;text-transform:uppercase;color:<?php echo esc_attr($lvl['deep']); ?>;margin-bottom:4px;">Example page</div>
                <div style="font-family:'Fredoka',sans-serif;font-weight:500;font-size:16px;color:#1A1A2E;line-height:1.2;margin-bottom:8px;"><?php echo esc_html($lvl['example']); ?></div>
                <a href="<?php echo esc_url($cat_link); ?>" style="font-family:'Fredoka',sans-serif;font-weight:600;font-size:13.5px;color:<?php echo esc_attr($lvl['deep']); ?>;text-decoration:none;">Open this page →</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</section>

<!-- ═══ AD SLOT ══════════════════════════════════════════════════════ -->
<div class="gd-wrap" style="padding:26px 28px 6px;">
  <div style="max-width:728px;height:90px;margin:0 auto;background:#F5F5F5;border:1px dashed #DCDCE2;border-radius:10px;display:flex;align-items:center;justify-content:center;">
    <span style="font-size:11px;letter-spacing:1.5px;color:#B4B4BE;text-transform:uppercase;font-weight:700;">Advertisement · 728 × 90</span>
  </div>
</div>

<!-- ═══ HOW TO CHOOSE ════════════════════════════════════════════════ -->
<section style="background:#FBF7FA;margin-top:30px;">
  <div class="gd-wrap" style="padding:56px 28px;">
    <div style="display:flex;align-items:center;gap:16px;justify-content:center;text-align:center;flex-direction:column;max-width:640px;margin:0 auto 38px;">
      <div style="display:flex;align-items:center;gap:14px;">
        <svg viewBox="0 0 120 150" width="56" height="70" style="flex:none;animation:cphFloat 4.5s ease-in-out infinite;" aria-hidden="true"><use href="#g-think"/></svg>
        <div style="text-align:left;">
          <span style="font-family:'Fredoka',sans-serif;font-weight:600;font-size:13px;letter-spacing:1.6px;color:#E835AC;text-transform:uppercase;">How to choose</span>
          <h2 style="font-family:'Fredoka',sans-serif;font-weight:600;font-size:clamp(25px,3.3vw,36px);line-height:1.05;letter-spacing:-0.8px;color:#1A1A2E;margin:6px 0 0;">Not sure where to start?</h2>
        </div>
      </div>
      <p style="font-size:16px;line-height:1.6;color:#6A6573;margin:0;">Here's how we think about it — four quick checks and you'll land on the right level every time.</p>
    </div>
    <div class="gd-steps">
      <?php foreach ( $steps as $s ) :
        $icon_tpl = $step_icons[ $s['icon'] ];
        $icon_svg = sprintf( $icon_tpl, $s['deep'], $s['deep'] );
      ?>
      <div style="background:#fff;border:1px solid #EEE9EC;border-radius:20px;padding:24px 22px;display:flex;flex-direction:column;height:100%;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
          <span style="font-family:'Fredoka',sans-serif;font-weight:600;font-size:22px;color:#fff;background:<?php echo esc_attr($s['color']); ?>;width:46px;height:46px;border-radius:14px;display:flex;align-items:center;justify-content:center;box-shadow:0 6px 16px <?php echo esc_attr($s['glow']); ?>;"><?php echo $s['n']; ?></span>
          <span style="width:42px;height:42px;border-radius:12px;background:<?php echo esc_attr($s['tint']); ?>;display:flex;align-items:center;justify-content:center;">
            <?php echo $icon_svg; ?>
          </span>
        </div>
        <h3 style="font-family:'Fredoka',sans-serif;font-weight:600;font-size:17px;color:<?php echo esc_attr($s['deep']); ?>;margin:0 0 8px;"><?php echo esc_html($s['title']); ?></h3>
        <p style="font-size:14px;line-height:1.6;color:#6A6573;margin:0;"><?php echo esc_html($s['desc']); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══ REVIEWS ══════════════════════════════════════════════════════ -->
<section style="padding:56px 0;">
  <div class="gd-wrap">
    <div style="text-align:center;max-width:560px;margin:0 auto 36px;">
      <span style="font-family:'Fredoka',sans-serif;font-weight:600;font-size:13px;letter-spacing:1.6px;color:#E835AC;text-transform:uppercase;">From real families</span>
      <h2 style="font-family:'Fredoka',sans-serif;font-weight:600;font-size:clamp(24px,3.2vw,36px);line-height:1.06;letter-spacing:-0.8px;color:#1A1A2E;margin:8px 0 0;">One happy parent per level</h2>
    </div>
    <div class="gd-reviews">
      <?php foreach ( $reviews as $r ) :
        $lvl = $levels[ $r['n'] ];
      ?>
      <div style="background:<?php echo esc_attr($lvl['tint']); ?>;border:1px solid <?php echo esc_attr($lvl['border']); ?>;border-radius:20px;padding:20px 18px;display:flex;flex-direction:column;">
        <!-- Level header -->
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:13px;">
          <img src="<?php echo esc_url( $child_uri . '/assets/difficulty/difficulty-level-' . $r['n'] . '.svg' ); ?>" width="30" height="30" alt="">
          <span style="font-family:'Fredoka',sans-serif;font-weight:600;font-size:13px;color:<?php echo esc_attr($lvl['deep']); ?>;line-height:1.1;">
            Level <?php echo $r['n']; ?><br>
            <span style="font-weight:500;color:#7A7480;font-size:11.5px;"><?php echo esc_html($lvl['name']); ?></span>
          </span>
        </div>
        <!-- Quote mark -->
        <svg width="22" height="18" viewBox="0 0 22 18" fill="none" style="margin-bottom:8px;"><path d="M9 0C4 2 1 6 1 11c0 4 2.5 7 6 7 3 0 5-2 5-5s-2-5-5-5c-.6 0-1.2.1-1.7.3C6 5 7.4 3.2 10 2L9 0zm12 0c-5 2-8 6-8 11 0 4 2.5 7 6 7 3 0 5-2 5-5s-2-5-5-5c-.6 0-1.2.1-1.7.3C18 5 19.4 3.2 22 2L21 0z" fill="<?php echo esc_attr($lvl['color']); ?>" opacity="0.5"/></svg>
        <p style="font-size:14px;line-height:1.55;color:#3F3B49;margin:0 0 16px;font-weight:600;flex:1;"><?php echo esc_html($r['quote']); ?></p>
        <!-- Author -->
        <div style="display:flex;align-items:center;gap:10px;border-top:1px solid <?php echo esc_attr($lvl['border']); ?>;padding-top:13px;">
          <span style="flex:none;width:34px;height:34px;border-radius:50%;background:<?php echo esc_attr($lvl['color']); ?>;color:#fff;display:flex;align-items:center;justify-content:center;font-family:'Fredoka',sans-serif;font-weight:600;font-size:14px;"><?php echo esc_html($r['initial']); ?></span>
          <span>
            <span style="display:block;font-family:'Fredoka',sans-serif;font-weight:600;font-size:14px;color:#1A1A2E;line-height:1.15;"><?php echo esc_html($r['parent']); ?></span>
            <span style="display:block;font-size:12px;color:#86808C;font-weight:600;"><?php echo esc_html($r['child']); ?></span>
          </span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══ FAQ ══════════════════════════════════════════════════════════ -->
<section style="padding:48px 0 20px;">
  <div class="gd-wrap" style="max-width:820px;">
    <div style="text-align:center;margin:0 auto 30px;">
      <span style="font-family:'Fredoka',sans-serif;font-weight:600;font-size:13px;letter-spacing:1.6px;color:#E835AC;text-transform:uppercase;">Good to know</span>
      <h2 style="font-family:'Fredoka',sans-serif;font-weight:600;font-size:clamp(26px,3.4vw,38px);line-height:1.08;letter-spacing:-1px;color:#1A1A2E;margin:10px 0 0;">Questions parents ask</h2>
    </div>
    <div style="display:flex;flex-direction:column;gap:12px;">
      <?php foreach ( $faqs as $i => $faq ) :
        $lvl    = $levels[ $i + 1 ];
        $itemid = 'gd-faq-' . $i;
      ?>
      <div class="gd-faq-item" style="background:#fff;border:1px solid #EEE9EC;border-radius:16px;overflow:hidden;" data-border="<?php echo esc_attr($lvl['border']); ?>" data-tint="<?php echo esc_attr($lvl['tint']); ?>" data-color="<?php echo esc_attr($lvl['color']); ?>">
        <button class="gd-faq-btn" aria-expanded="false"
          style="width:100%;display:flex;align-items:center;justify-content:space-between;gap:16px;background:#fff;border:none;cursor:pointer;text-align:left;padding:10px 22px;font-family:'Fredoka',sans-serif;font-weight:600;font-size:17px;color:#1A1A2E;transition:background .15s;text-transform:none;letter-spacing:normal;line-height:1.3;min-height:0;height:auto;">
          <?php echo esc_html($faq['q']); ?>
          <span class="gd-faq-sign" style="flex:none;width:28px;height:28px;border-radius:9px;background:#F4EFF3;display:flex;align-items:center;justify-content:center;">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" class="gd-faq-plus"><path d="M7 2v10M2 7h10" stroke="#9A8FA0" stroke-width="2" stroke-linecap="round"/></svg>
          </span>
        </button>
        <div class="gd-faq-ans" hidden style="padding:12px 22px 18px;">
          <p style="font-size:15px;line-height:1.62;color:#5C5866;margin:0;"><?php echo esc_html($faq['a']); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══ BROWSE BY LEVEL CTA ══════════════════════════════════════════ -->
<section style="background:#E835AC;padding:56px 0;">
  <div class="gd-wrap">
    <div style="text-align:center;max-width:560px;margin:0 auto 36px;">
      <span style="font-family:'Fredoka',sans-serif;font-weight:600;font-size:13px;letter-spacing:1.6px;color:rgba(255,255,255,.7);text-transform:uppercase;">Start colouring</span>
      <h2 style="font-family:'Fredoka',sans-serif;font-weight:600;font-size:clamp(26px,3.4vw,38px);line-height:1.06;letter-spacing:-0.8px;color:#fff;margin:8px 0 0;">Browse by level</h2>
    </div>
    <div class="gd-bottom">
      <?php foreach ( $levels as $n => $lvl ) :
        $cat_link = add_query_arg(['meta_key'=>'_cph_difficulty','meta_value'=>$n], $shop_url);
      ?>
      <a href="<?php echo esc_url($cat_link); ?>" style="display:flex;flex-direction:column;align-items:center;gap:8px;background:<?php echo esc_attr($lvl['tint']); ?>;border:1.5px solid <?php echo esc_attr($lvl['border']); ?>;border-radius:18px;padding:24px 16px;text-align:center;text-decoration:none;transition:transform .15s,box-shadow .15s;">
        <img src="<?php echo esc_url( $child_uri . '/assets/difficulty/difficulty-level-' . $n . '.svg' ); ?>" width="52" height="52" alt="">
        <strong style="font-family:'Fredoka',sans-serif;font-size:15px;display:block;color:<?php echo esc_attr($lvl['deep']); ?>;"><?php echo esc_html($lvl['name']); ?></strong>
        <small style="font-size:12.5px;font-weight:600;color:#8A8490;"><?php echo esc_html($lvl['ages']); ?></small>
        <span style="font-family:'Fredoka',sans-serif;font-size:13.5px;font-weight:700;color:<?php echo esc_attr($lvl['color']); ?>;margin-top:6px;">Browse pages →</span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php get_template_part('template-parts/cph-footer'); ?>

<script>
(function(){
  function closeAll() {
    document.querySelectorAll('.gd-faq-item').forEach(function(it){
      var ans  = it.querySelector('.gd-faq-ans');
      var btn  = it.querySelector('.gd-faq-btn');
      var sign = it.querySelector('.gd-faq-sign');
      var plus = it.querySelector('.gd-faq-plus path');
      ans.setAttribute('hidden','');
      btn.setAttribute('aria-expanded','false');
      btn.style.background = '#fff';
      it.style.borderColor = '#EEE9EC';
      sign.style.background = '#F4EFF3';
      sign.style.transform = 'rotate(0deg)';
      if(plus) plus.setAttribute('stroke','#9A8FA0');
    });
  }

  document.querySelectorAll('.gd-faq-btn').forEach(function(btn){
    btn.addEventListener('click', function(){
      var item  = btn.closest('.gd-faq-item');
      var ans   = item.querySelector('.gd-faq-ans');
      var sign  = item.querySelector('.gd-faq-sign');
      var plus  = item.querySelector('.gd-faq-plus path');
      var open  = ans.hasAttribute('hidden');
      var color = item.dataset.color;
      var tint  = item.dataset.tint;
      var border= item.dataset.border;

      closeAll();

      if(open){
        ans.removeAttribute('hidden');
        btn.setAttribute('aria-expanded','true');
        btn.style.background = tint;
        item.style.borderColor = border;
        sign.style.background = color;
        sign.style.transform = 'rotate(45deg)';
        if(plus) plus.setAttribute('stroke','#fff');
      }
    });
  });

  // Open first FAQ by default (after listeners are attached)
  var firstBtn = document.querySelector('.gd-faq-btn');
  if(firstBtn) firstBtn.click();
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
<?php exit;
