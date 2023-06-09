<?php


error_reporting(E_ALL ^ E_DEPRECATED );


function base_scripts_styles() {
    $version   = 1;
    $in_footer = true;

    // styles
    wp_enqueue_style('swiper style', 'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css', [] );

	wp_enqueue_style('select 2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css', [] );
    wp_enqueue_style('main style', get_template_directory_uri() . '/css/style.css', [] );
    wp_enqueue_style('theme', get_stylesheet_uri(), [] );

    // scripts
	wp_deregister_script( 'jquery' );
    wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.3.min.js', [], $version, $in_footer );
    wp_enqueue_script('swiper script', 'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js', [], $version, $in_footer );
	wp_enqueue_script('select 2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', [], $version, $in_footer );
    wp_enqueue_script('main script', get_template_directory_uri() . '/js/main.js', [], $version, $in_footer );
}
add_action( 'wp_enqueue_scripts', 'base_scripts_styles' );

// Carbon Fields
add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
	require_once( 'includes/carbon-fields/vendor/autoload.php' );
	\Carbon_Fields\Carbon_Fields::boot();
}

add_action('carbon_fields_register_fields', 'register_carbon_fields');
function register_carbon_fields () {
    require_once('includes/carbon-fields-options/theme-options.php');
    require_once('includes/carbon-fields-options/post-meta.php');
}

function my_theme_setup(){
    add_theme_support('post-thumbnails');
	add_theme_support( 'custom-logo' );
}

add_action('after_setup_theme', 'my_theme_setup');

function siteDefPaging( \WP_Query $wp_query=null, $echo=true, $params=[] ){
    if ( null === $wp_query ) {
        global $wp_query;
    }
    $add_args = [];
		$pages = paginate_links( array_merge( [
            'nol'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'format'       => '?paged=%#%',
            'current'      => max( 1, get_query_var( 'paged' ) ),
            'total'        => $wp_query->max_num_pages,
            'type'         => 'array',
            'show_all'     => false,
            'end_size'     => 3,
            'mid_size'     => 1,
            'prev_next'    => true,
			'prev_text'    => '<svg width="11" height="16" viewBox="0 0 11 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 15L2.0625 9.51235C1.52457 9.14044 1.48252 8.36079 1.97732 7.93318L10 1" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>Previous',
            'next_text'    => 'Next<svg width="11" height="16" viewBox="0 0 11 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L8.9375 6.48765C9.47543 6.85956 9.51748 7.63921 9.02268 8.06682L1 15" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>',
            'add_args'     => $add_args,
            'add_fragment' => ''
        ], $params )
    );

    if( is_array( $pages ) ) {
        $pagination = '<ul class="pagination">';
	
        foreach ( $pages as $page ) {
            $pagination .= '<li class="pagination-item' . ( strpos( $page, 'next') !== false || strpos( $page, 'prev') ? ' arrow' : '') . ( strpos( $page, 'current') !== false ? ' active' : '') . '"> ' . $page . '</li>';
        }
		$pagination .= "</ul>";
        if ( $echo ) {
            echo $pagination;
        } else {
            return $pagination;
        }
    }

    return null;
}

function wp_custom_trim_words( $text, $num_words = 55, $more = null ) {
	if ( null === $more ) {
		$more = __( '&hellip;' );
	}

	$original_text = $text;
	$num_words     = (int) $num_words;

	/*
	 * translators: If your word count is based on single characters (e.g. East Asian characters),
	 * enter 'characters_excluding_spaces' or 'characters_including_spaces'. Otherwise, enter 'words'.
	 * Do not translate into your own language.
	 */
	if ( strpos( _x( 'words', 'Word count type. Do not translate!' ), 'characters' ) === 0 && preg_match( '/^utf\-?8$/i', get_option( 'blog_charset' ) ) ) {
		$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $text ), ' ' );
		preg_match_all( '/./u', $text, $words_array );
		$words_array = array_slice( $words_array[0], 0, $num_words + 1 );
		$sep         = '';
	} else {
		$words_array = preg_split( "/[\n\r\t ]+/", $text, $num_words + 1, PREG_SPLIT_NO_EMPTY );
		$sep         = ' ';
	}

	if ( count( $words_array ) > $num_words ) {
		array_pop( $words_array );
		$text = implode( $sep, $words_array );
		$text = $text . $more;
	} else {
		$text = implode( $sep, $words_array );
	}

	/**
	 * Filters the text content after words have been trimmed.
	 *
	 * @since 3.3.0
	 *
	 * @param string $text          The trimmed text.
	 * @param int    $num_words     The number of words to trim the text to. Default 55.
	 * @param string $more          An optional string to append to the end of the trimmed text, e.g. &hellip;.
	 * @param string $original_text The text before it was trimmed.
	 */
	return apply_filters( 'wp_trim_words', $text, $num_words, $more, $original_text );
}

// function register_menus() { 
// 	register_nav_menus(
//         array(
//             'header-menu' => 'Header Menu',
//             'footer-1' => 'Footer Menu 1',
//             'footer-2' => 'Footer Menu 2',
// 			'footer-3' => 'Footer Menu 3',
//         )
//     ); 
// } 
// add_action('init', 'register_menus');

function mytheme_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
	add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
	add_filter( 'yith_wcwl_is_wishlist_responsive', '__return_false' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );

add_filter( 'single_product_archive_thumbnail_size', function( $size ) {
	return 'small';
} );

// add_filter( 'woocommerce_breadcrumb_defaults', 'jk_woocommerce_breadcrumbs' );
// function jk_woocommerce_breadcrumbs() {
//     return array(
//             'delimiter'   => ' <span class="line"> / </span> ',
//             'wrap_before' => '<nav class="woocommerce-breadcrumb" itemprop="breadcrumb">',
//             'wrap_after'  => '</nav>',
//             'before'      => '',
//             'after'       => '',
//             'home'        => _x( 'Books', 'breadcrumb', 'woocommerce' ),
//         );
// }


add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
  show_admin_bar(false);
}


function popup_ajax_call(){
		setup_postdata($_POST['id']);
		session_start();
		$_SESSION['postID'] = $_POST['id'];
		wc_get_template_part( 'content', 'single-product' );
		echo '<button class="pop-up__close"><svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M17 1L1 17M1 1L17 17" stroke="#9B9B9B" stroke-width="2" stroke-linecap="round"/>
		</svg>
		</button>';

	wp_die();// this is required to terminate immediately and return a proper response
 }
add_action('wp_ajax_popup_ajax_call', 'popup_ajax_call'); // for logged in users only
add_action('wp_ajax_nopriv_popup_ajax_call', 'popup_ajax_call'); // for ALL users

// wooc customize gallery
function wc_get_gallery_image_html_custom( $attachment_id, $main_image = false ) {
	$flexslider        = (bool) apply_filters( 'woocommerce_single_product_flexslider_enabled', get_theme_support( 'wc-product-gallery-slider' ) );
	$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
	$thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
	$image_size        = apply_filters( 'woocommerce_gallery_image_size', $flexslider || $main_image ? 'woocommerce_single' : $thumbnail_size );
	$full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
	$thumbnail_src     = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
	$full_src          = wp_get_attachment_image_src( $attachment_id, $full_size );
	$alt_text          = trim( wp_strip_all_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) );
	$image             = wp_get_attachment_image(
		$attachment_id,
		$image_size,
		false,
		apply_filters(
			'woocommerce_gallery_image_html_attachment_image_params',
			array(
				'title'                   => _wp_specialchars( get_post_field( 'post_title', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
				'data-caption'            => _wp_specialchars( get_post_field( 'post_excerpt', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
				'data-src'                => esc_url( $full_src[0] ),
				'data-large_image'        => esc_url( $full_src[0] ),
				'data-large_image_width'  => esc_attr( $full_src[1] ),
				'data-large_image_height' => esc_attr( $full_src[2] ),
				'class'                   => esc_attr( $main_image ? 'wp-post-image' : '' ),
			),
			$attachment_id,
			$image_size,
			$main_image
		)
	);

	return '<div data-thumb="' . esc_url( $thumbnail_src[0] ) . '" data-thumb-alt="' . esc_attr( $alt_text ) . '"class="swiper-slide woocommerce-product-gallery__image">' . $image . '</div>';
}


remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
 
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title', 10 );
add_action('woocommerce_shop_loop_item_title', 'abChangeProductsTitle', 10 );
function abChangeProductsTitle() {
echo '<h2 class="product-t"><a href="#" data-id="' . get_the_ID(). '" class="product__learn-btn">' . get_the_title() . '</a></h2>';
}

add_filter( 'woocommerce_breadcrumb_defaults', 'jk_woocommerce_breadcrumbs' );
function jk_woocommerce_breadcrumbs() {
    return array(
            'delimiter'   => ' <span class="line"> <svg width="9" height="12" viewBox="0 0 9 12" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M1 1.5L7 5.35714L1 10.5" stroke="#777777" stroke-width="2" stroke-linecap="round"/>
			</svg>
			 </span> ',
            'wrap_before' => '<nav class="woocommerce-breadcrumb" itemprop="breadcrumb">',
            'wrap_after'  => '</nav>',
            'before'      => '',
            'after'       => '',
            'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
        );
}


// add_filter( 'woocommerce_package_rates', 'custom_shipping_cost', 10, 2 );
// function custom_shipping_cost( $rates, $package ) {
//     $new_rates = array();

//     foreach ( $rates as $rate_id => $rate ) {
//         $new_rates[ $rate_id ] = $rate;
//         $new_rates[ $rate_id ]->cost += 5; // add $5 shipping cost to all rates
//     }

//     return $new_rates;
// }

function cart_ajax_call(){
	echo count(YITH_WCWL()->get_products( [ 'wishlist_id' => 'all' ] ));
wp_die();// this is required to terminate immediately and return a proper response
}
add_action('wp_ajax_cart_ajax_call', 'cart_ajax_call'); // for logged in users only
add_action('wp_ajax_nopriv_cart_ajax_call', 'cart_ajax_call'); // for ALL users


function get_wc_cart_totals_order_total_html() {
	$value = WC()->cart->get_total();

	// If prices are tax inclusive, show taxes here.
	if ( wc_tax_enabled() && WC()->cart->display_prices_including_tax() ) {
		$tax_string_array = array();
		$cart_tax_totals  = WC()->cart->get_tax_totals();

		if ( get_option( 'woocommerce_tax_total_display' ) === 'itemized' ) {
			foreach ( $cart_tax_totals as $code => $tax ) {
				$tax_string_array[] = sprintf( '%s %s', $tax->formatted_amount, $tax->label );
			}
		} elseif ( ! empty( $cart_tax_totals ) ) {
			$tax_string_array[] = sprintf( '%s %s', wc_price( WC()->cart->get_taxes_total( true, true ) ), WC()->countries->tax_or_vat() );
		}

		if ( ! empty( $tax_string_array ) ) {
			$taxable_address = WC()->customer->get_taxable_address();
			if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
				$country = WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ];
				/* translators: 1: tax amount 2: country name */
				$tax_text = wp_kses_post( sprintf( __( '(includes %1$s estimated for %2$s)', 'woocommerce' ), implode( ', ', $tax_string_array ), $country ) );
			} else {
				/* translators: %s: tax amount */
				$tax_text = wp_kses_post( sprintf( __( '(includes %s)', 'woocommerce' ), implode( ', ', $tax_string_array ) ) );
			}

			$value .= $tax_text;
		}
	}

return $value; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

add_filter( 'woocommerce_order_button_text', 'wc_custom_order_button_text' ); 

function wc_custom_order_button_text() {
	return __( 'Pay ', 'woocommerce' ) . strip_tags(get_wc_cart_totals_order_total_html());
}

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'custom_loop_product_thumbnail', 10 );
function custom_loop_product_thumbnail() {
    global $product;
    $size = 'full';
	
    $image_size = apply_filters( 'single_product_archive_thumbnail_size', $size );
	echo '<a href="#" data-id="' . $product ->get_id() . '" class="product__learn-btn">';
    echo $product ? $product->get_image( $image_size ) : '';
	echo '</a>';
}

add_action( 'woocommerce_after_checkout_validation', 'misha_one_err', 9999, 2);
 
function misha_one_err( $fields, $errors ){
 
	// if any validation errors
	if( ! empty( $errors->get_error_codes() ) ) {
 
		// remove all of them
		foreach( $errors->get_error_codes() as $code ) {
			$errors->remove( $code );
		}
 
		// add our custom one
		$errors->add( 'validation', 'Please fill the fields!' );
 
	}
 
}

add_filter('woocommerce_get_availability_text', 'themeprefix_change_soldout', 10, 2 );

/**
* Change Sold Out Text to Something Else
*/
function themeprefix_change_soldout ( $text, $product) {
if ( !$product->is_in_stock() ) {
$text = 'Sold';
}
return $text;
}

function filter_woocommerce_get_catalog_ordering_args( $args, $orderby, $order ) {
	switch( $orderby ) {
	case 'availability':
	$args['orderby'] = 'meta_value_num';
	$args['order'] = 'DESC';
	$args['meta_key'] = '_stock';
	break;
	}
	return $args;
	}
	add_filter( 'woocommerce_get_catalog_ordering_args', 'filter_woocommerce_get_catalog_ordering_args', 10, 3 );
	// Orderby setting
	function filter_orderby( $orderby ) {
	$orderby['availability'] = __( 'Availability', 'woocommerce' );
	return $orderby;
	}
	add_filter( 'woocommerce_default_catalog_orderby_options', 'filter_orderby', 10, 1 );
	add_filter( 'woocommerce_catalog_orderby', 'filter_orderby', 10, 1 );



	function empty_ajax_call(){?>
	<section class="cart-sec return">
		<div class="container">
			Your cart is empty.
			<?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
				<p class="return-to-shop">
					<a class="button wc-backward<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
						<?php
							/**
							 * Filter "Return To Shop" text.
							 *
							 * @since 4.6.0
							 * @param string $default_text Default text.
							 */
							echo esc_html( apply_filters( 'woocommerce_return_to_shop_text', __( 'Click here to browse items to add to your cart', 'woocommerce' ) ) );
						?>
					</a>
				</p>
			<?php endif; ?>
			</div>
		</section>
		<?php
		wp_die();// this is required to terminate immediately and return a proper response
	}
	add_action('wp_ajax_empty_ajax_call', 'empty_ajax_call'); // for logged in users only
	add_action('wp_ajax_nopriv_empty_ajax_call', 'empty_ajax_call'); // for ALL users
// Remove all currency symbols
function sww_remove_wc_currency_symbols( $currency_symbol, $currency ) {
	$currency_symbol = 'USD';
	return $currency_symbol;
}
add_filter('woocommerce_currency_symbol', 'sww_remove_wc_currency_symbols', 10, 2);


