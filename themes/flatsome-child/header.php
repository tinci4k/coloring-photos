<?php
/**
 * Header template.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php flatsome_html_classes(); ?>">
<head>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-H806Y9E2Z8"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-H806Y9E2Z8');
</script>

	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php wp_head(); ?>

	</head>

<body <?php body_class(); ?>>

<?php do_action( 'flatsome_after_body_open' ); ?>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'flatsome' ); ?></a>

<div id="wrapper">

	<?php do_action( 'flatsome_before_header' ); ?>

	<header id="header" class="header <?php flatsome_header_classes(); ?>">
		<div class="header-wrapper">
			<?php get_template_part( 'template-parts/header/header', 'wrapper' ); ?>
		</div>
	</header>

	<?php do_action( 'flatsome_after_header' ); ?>

	<main id="main" class="<?php flatsome_main_classes(); ?>">

	<script type="text/javascript">
		jQuery( document ).ready(function () {
			let searchParams = new URLSearchParams(window.location.search)
			
			setTimeout(function() {
				var images = jQuery('.woocommerce-product-gallery__image a')
				var img_number = images.length

				if(img_number > 1) {

					//Add print all button
					jQuery('.somdn-download-wrap .somdn-download-form').append('<button type="button" id="print_all" class="print-button somdn-download-button single_add_to_cart_button button">Print All</button>');

					//Print all logic
					jQuery('.somdn-download-wrap .somdn-download-form').append('<iframe src="" style="visibility: hidden; position: absolute;" class="printallview"></iframe>');
					var iframe_html = '<html><head><meta name="viewport" content="width=device-width; height=device-height;"><title>polar-bear-animal-coloring-page.jpg (JPEG Image, 1240&nbsp;×&nbsp;1754 pixels)</title><style>@media print {  @page { margin: 0 }  body { margin: 0; }} img { height: auto;  width: 100%; } @-moz-document url-prefix() { img { height: 100%; } }</style></head><body>';
					images.each(function () {
						var image_url = jQuery(this).attr('href')
						iframe_html += '<img src="'+image_url+'" alt="'+image_url+'">'
					})
					iframe_html += '</body></html>'
					jQuery('iframe.printallview').get(0).contentWindow.document.open();
					jQuery('iframe.printallview').get(0).contentWindow.document.write(iframe_html);
					jQuery('iframe.printallview').get(0).contentWindow.document.close();

					//What happens on print all button click
					jQuery('#print_all.print-button').on('click', function(e) {
						e.preventDefault();
						jQuery('iframe.printallview').get(0).contentWindow.print();
					});

					//Add print selected button
					jQuery('.somdn-download-wrap .somdn-download-form').append('<button type="button" id="print_selected" class="print-button somdn-download-button single_add_to_cart_button button">Print Selected</button>');

					//What happens on print all button click
					jQuery('#print_selected.print-button').on('click', function(e) {
						e.preventDefault();
						//Print selected logic
						jQuery('.somdn-download-wrap .somdn-download-form').append('<iframe src="" style="visibility: hidden; position: absolute;" class="printselectedview"></iframe>');
						var iframe_selected_html = '<html><head><meta name="viewport" content="width=device-width; height=device-height;"><title>polar-bear-animal-coloring-page.jpg (JPEG Image, 1240&nbsp;×&nbsp;1754 pixels)</title><style>@media print {  @page { margin: 0 }  body { margin: 0; }} img { height: auto;  width: 100%; } @-moz-document url-prefix() { img { height: 100%; } }</style></head><body>';
						var image_selected_url = jQuery('.woocommerce-product-gallery__image.is-selected a').attr('href');

						iframe_selected_html += '<img src="'+image_selected_url+'" alt="'+image_selected_url+'">'
						iframe_selected_html += '</body></html>'
						jQuery('iframe.printselectedview').get(0).contentWindow.document.open();
						jQuery('iframe.printselectedview').get(0).contentWindow.document.write(iframe_selected_html);
						jQuery('iframe.printselectedview').get(0).contentWindow.document.close();
						jQuery('iframe.printselectedview').get(0).contentWindow.print();
						jQuery('iframe.printselectedview').remove();
					});

				} else {
					
					//Print page button
					jQuery('.print-button-container').append('<button type="button" class="print-button somdn-download-button single_add_to_cart_button button">Print</button>')
					
					//Image iframe
					const image_url = searchParams.get('print_image')
					
					//Image button
					const image_btn = jQuery('.woocommerce-product-gallery__image a').attr('href');
					
					//Single product page print button
					jQuery('.somdn-download-wrap .somdn-download-form').append('<a href="<?php echo get_permalink(9418); ?>?print_image='+encodeURIComponent(image_btn)+'" class="print-button somdn-download-button single_add_to_cart_button button">Print</a>');
					
					if(searchParams.has('print_image')) {
						//Prepare hidden iframe with image
						jQuery('.print-button-container').append('<iframe src="" style="visibility: hidden; position: absolute;" class="printview"></iframe>');
						const iframe_html = '<html><head><meta name="viewport" content="width=device-width; height=device-height;"><title>polar-bear-animal-coloring-page.jpg (JPEG Image, 1240&nbsp;×&nbsp;1754 pixels)</title><style>@media print {  @page { margin: 0 }  body { margin: 0; }} img { height: auto;  width: 100%; } @-moz-document url-prefix() { img { height: 100%; } }</style></head><body><img src="'+image_url+'" alt="'+image_url+'"></body></html>';
						jQuery('iframe.printview').get(0).contentWindow.document.open();
						jQuery('iframe.printview').get(0).contentWindow.document.write(iframe_html);
						jQuery('iframe.printview').get(0).contentWindow.document.close();

						//What happens on print button click
						jQuery('.print-button-container .print-button').on('click', function(e) {
							e.preventDefault();
							jQuery('iframe.printview').get(0).contentWindow.print();
						});
					}					
					//Get download parameters
					let download_key = jQuery('input[name=somdn_download_key]').val()
					let action = jQuery('input[name=action]').val()
					let somdn_product = jQuery('input[name=somdn_product]').val()
					let page = jQuery('.somdn-download-wrap form.somdn-download-form').attr('action')
					let image = jQuery('.woocommerce-product-gallery__image a').attr('href')

					//Create download button
					jQuery('.somdn-download-wrap .somdn-download-form').append('<a href="<?php echo get_permalink(9437); ?>?download_key='+encodeURIComponent(download_key)+'&action='+encodeURIComponent(action)+'&somdn_product='+encodeURIComponent(somdn_product)+'&page='+encodeURIComponent(page)+'&image='+encodeURIComponent(image)+'" class="download-button somdn-download-button single_add_to_cart_button button">Download now</a>');
					
					//Remove download now original button
					jQuery('button#somdn-form-submit-button').remove()
				}
				
				//Create download button on page
				if(searchParams.has('download_key')) {
					jQuery('.download-button-container').append('<form class="somdn-download-form" action="'+searchParams.get('page')+'" method="post"> <input type="hidden" name="somdn_download_key" value="'+searchParams.get('download_key')+'"> <input type="hidden" name="action" value="'+searchParams.get('action')+'"> <input type="hidden" name="somdn_product" value="'+searchParams.get('somdn_product')+'"><button style="" type="submit" id="somdn-form-submit-button" class="somdn-download-button single_add_to_cart_button button">Download Now</button></form>')
				}
				
				//Add image
				if(searchParams.has('image')) {
					jQuery('.coloring-image-container').append('<img src="'+searchParams.get('image')+'" class="coloring-image" style="width:500px;height:500px;object-fit: contain;" />')
				}
				if(searchParams.has('print_image')) {
					jQuery('.coloring-image-container').append('<img src="'+searchParams.get('print_image')+'" class="coloring-image" style="width:500px;height:500px;object-fit: contain;" />')
				}
				
			}, 200);
		})
	</script>