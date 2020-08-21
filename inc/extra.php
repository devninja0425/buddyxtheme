<?php
// buddy_excerpt_length
function buddy_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'buddy_excerpt_length', 999 );

// Content wrapper
if ( !function_exists( 'buddy_content_top' ) ) {
	function buddy_content_top() { ?>
		<div class="site-wrapper">
	<?php }
}

add_action( 'buddy_before_content', 'buddy_content_top' );

if ( !function_exists( 'buddy_content_bottom' ) ) {
	function buddy_content_bottom() { ?>
		</div>
	<?php }
}

add_action( 'buddy_after_content', 'buddy_content_bottom' );

// Site Sub Header
if ( !function_exists( 'buddyx_sub_header' ) ) {
	add_action( 'buddyx_sub_header', 'buddyx_sub_header' );

	function buddyx_sub_header() {
		global $post;
		if ( is_front_page() ) {
			return;
		}
		?>
	<div class="site-sub-header">
		<div class="container">
		  <?php
			if ( get_post_type() === 'post' || is_single() || is_archive( 'post-type-archive-forum' ) && ( function_exists( 'is_shop' ) && ! is_shop() ) ) {
				get_template_part( 'template-parts/content/page_header' );
				$breadcrumbs = get_theme_mod( 'site_breadcrumbs', buddyx_defaults( 'site-breadcrumbs' ) );
				if ( ! empty( $breadcrumbs ) ) {
					the_breadcrumb();
				}
			} elseif ( get_post_type() === 'page' || is_single() ) {
					// PAGE
					get_template_part( 'template-parts/content/entry_title', get_post_type() );
					$breadcrumbs = get_theme_mod( 'site_breadcrumbs', buddyx_defaults( 'site-breadcrumbs' ) );
				if ( ! empty( $breadcrumbs ) ) {
					the_breadcrumb();
				}
			}
			?>
		</div>
 	</div>
<?php }
}

/**
 * BREADCRUMBS
 */
//  to include in functions.php
if ( !function_exists( 'the_breadcrumb' ) ) {
	function the_breadcrumb() {

		$wpseo_titles = get_option( 'wpseo_titles' );
		if ( function_exists('yoast_breadcrumb') && isset($wpseo_titles['breadcrumbs-enable']) &&  $wpseo_titles['breadcrumbs-enable'] == 1 ) {

			yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );

		} else {

			$sep = ' &raquo ';

			if (!is_front_page()) {

				// Start the breadcrumb with a link to your homepage
				echo '<div class="buddyx-breadcrumbs">';
				echo '<a href="';
				echo home_url();
				echo '">';
				echo'Home';
				echo '</a>' . $sep;

				// Check if the current page is a category, an archive or a single page. If so show the category or archive name.
				if ( is_category() || is_single() ){
					the_category(' > ');
				} elseif ( is_archive() || is_single() ){
					if ( is_day() ) {
						printf( __( '%s', 'buddyxpro' ), get_the_date() );
					} elseif ( is_month() ) {
						printf( __( '%s', 'buddyxpro' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'buddyxpro' ) ) );
					} elseif ( is_year() ) {
						printf( __( '%s', 'buddyxpro' ), get_the_date( _x( 'Y', 'yearly archives date format', 'buddyxpro' ) ) );
					} elseif( is_shop() ) {
						_e( 'Shop', 'buddyxpro' );
					}elseif( is_archive('post-type-archive-forum') ) {
						_e( 'Forums Archives', 'buddyxpro' );
					} else {
						_e( 'Blog Archives', 'buddyxpro' );
					}
				}

				// If the current page is a single post, show its title with the separator
				if (is_single()) {
					echo $sep;
					the_title();
				}

				// If the current page is a static page, show its title.
				if (is_page()) {
					echo the_title();
				}

				// if you have a static page assigned to be you posts list page. It will find the title of the static page and display it. i.e Home > Blog
				if (is_home()){
					_e( 'Blog', 'buddyxpro' );
				}

				echo '</div>';
			}
		}
	}
}

// Site Loader
if ( !function_exists( 'site_loader' ) ) {
	function site_loader() {
		$loader	 = get_theme_mod( 'site_loader', buddyx_defaults( 'site-loader' ) );
		if ( $loader == "1" ) {
			echo '<div class="site-loader"><div class="loader-inner"><span class="dot"></span><span class="dot dot1"></span><span class="dot dot2"></span><span class="dot dot3"></span><span class="dot dot4"></span></div></div>';
		}
	}
}

// Site Search and Woo icon
if ( !function_exists( 'site_menu_icon' ) ) {
	function site_menu_icon () {
		// menu icons
		$searchicon = (int) get_theme_mod( 'site_search', buddyx_defaults( 'site-search' ) );
		$carticon = (int) get_theme_mod( 'site_cart', buddyx_defaults( 'site-cart' ) );
		if( !empty($searchicon) || !empty($carticon) ) : ?>
			<div class="menu-icons-wrapper"><?php
				if( !empty($searchicon) ): ?>
					<div class="search">
						<a href="javascript:void(0)" id="overlay-search" class="search-icon"> <span class="fa fa-search"> </span> </a>
						<div class="top-menu-search-container">
							<?php get_search_form(); ?>
						</div>
					</div>
					<?php
				endif;
				if( !empty($carticon) && function_exists("is_woocommerce")) : ?>
					<div class="cart">
						<a href="<?php echo wc_get_cart_url(); ?>" title="<?php esc_html_e( 'View Shopping Cart', 'buddyx' ); ?>">
							<span class="fa fa-shopping-cart"> </span><?php
							$count = WC()->cart->cart_contents_count;
							if( $count > 0 ) : ?>
								<sup><?php echo "{$count}";?></sup><?php
							endif;?>
						</a>
					</div><?php
				endif; ?>
			</div><?php
		endif;
	}
}

// bp_get_activity_css_first_class
if ( !function_exists( 'bp_get_activity_css_first_class' ) ) {
	function bp_get_activity_css_first_class() {
		global $activities_template;
		/**
		 * Filters the available mini activity actions available as CSS classes.
		 *
		 * @since 1.2.0
		 *
		 * @param array $value Array of classes used to determine classes applied to HTML element.
		 */
		$mini_activity_actions = apply_filters( 'bp_activity_mini_activity_types', array(
			'friendship_accepted',
			'friendship_created',
			'new_blog',
			'joined_group',
			'created_group',
			'new_member'
		) );
		return apply_filters( 'bp_get_activity_css_first_class', $activities_template->activity->component );
	}
}

/**
 * Is the current user online
 *
 * @param $user_id
 *
 * @return bool
 */
if ( !function_exists( 'buddyx_is_user_online' ) ) {

	function buddyx_is_user_online( $user_id ) {

		if( !function_exists( 'bp_get_user_last_activity' ) ) {
			return;
		}

		$last_activity = strtotime( bp_get_user_last_activity( $user_id ) );

		if ( empty( $last_activity ) ) {
			return false;
		}

		// the activity timeframe is 5 minutes
		$activity_timeframe = 5 * MINUTE_IN_SECONDS;
		return ( time() - $last_activity <= $activity_timeframe );
	}

}

/**
 * BuddyPress user status
 *
 * @param $user_id
 *
 */
if ( !function_exists( 'buddyx_user_status' ) ) {

	function buddyx_user_status( $user_id ) {
		if( buddyx_is_user_online( $user_id ) ) {
			echo '<span class="member-status online"></span>';
		}
	}
}

/**
 * woocommerce_cart_collaterals
 */
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart_form', 'woocommerce_cross_sell_display', 10 );


/* Ensure cart contents update when products are added to the cart via AJAX */
add_filter( 'woocommerce_add_to_cart_fragments', 'buddyx_header_add_to_cart_fragment' );

if ( !function_exists( 'buddyx_header_add_to_cart_fragment' ) ) {
	function buddyx_header_add_to_cart_fragment( $fragments ) {
		$count = WC()->cart->get_cart_contents_count();
		ob_start();
		?>
		<a class=".menu-icons-wrapper .cart" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php _e( 'View your shopping cart', 'buddyx' ); ?>">
			<span class="fa fa-shopping-cart"></span>
			<sup><?php echo esc_html( $count ); ?></sup>
		</a>
		<?php
		$fragments[ '.menu-icons-wrapper .cart a' ] = ob_get_clean();
		return $fragments;
	}
}

/**
 * disable_woo_commerce_sidebar
 */
if ( !function_exists( 'disable_woo_commerce_sidebar' ) ) {
	function disable_woo_commerce_sidebar() {
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
	}
}
add_action('init', 'disable_woo_commerce_sidebar');

/**
 * Output badges on profile
 */
if ( !function_exists( 'buddyx_profile_achievements' ) ) {

	function buddyx_profile_achievements() {
		global $user_ID;

		//user must be logged in to view earned badges and points

		if ( is_user_logged_in() && function_exists( 'badgeos_get_user_achievements' ) ) {

			$achievements = badgeos_get_user_achievements( array( 'user_id' => bp_displayed_user_id(), 'display' => true ) );

			if ( is_array( $achievements ) && !empty( $achievements ) ) {

				$number_to_show	 = 5;
				$thecount		 = 0;

				wp_enqueue_script( 'badgeos-achievements' );
				wp_enqueue_style( 'badgeos-widget' );

				//load widget setting for achievement types to display
				$set_achievements = ( isset( $instance[ 'set_achievements' ] ) ) ? $instance[ 'set_achievements' ] : '';

				//show most recently earned achievement first
				$achievements = array_reverse( $achievements );

				echo '<ul class="profile-achievements-listing">';

				foreach ( $achievements as $achievement ) {

					//verify achievement type is set to display in the widget settings
					//if $set_achievements is not an array it means nothing is set so show all achievements
					if ( !is_array( $set_achievements ) || in_array( $achievement->post_type, $set_achievements ) ) {

						//exclude step CPT entries from displaying in the widget
						if ( get_post_type( $achievement->ID ) != 'step' ) {

							$permalink	 = get_permalink( $achievement->ID );
							$title		 = get_the_title( $achievement->ID );
							$img		 = badgeos_get_achievement_post_thumbnail( $achievement->ID, array( 50, 50 ), 'wp-post-image' );
							$thumb		 = $img ? '<a class="badgeos-item-thumb" href="' . esc_url( $permalink ) . '">' . $img . '</a>' : '';
							$class		 = 'widget-badgeos-item-title';
							$item_class	 = $thumb ? ' has-thumb' : '';

							// Setup credly data if giveable
							$giveable	 = credly_is_achievement_giveable( $achievement->ID, $user_ID );
							$item_class	 .= $giveable ? ' share-credly addCredly' : '';
							$credly_ID	 = $giveable ? 'data-credlyid="' . absint( $achievement->ID ) . '"' : '';

							echo '<li id="widget-achievements-listing-item-' . absint( $achievement->ID ) . '" ' . $credly_ID . ' class="widget-achievements-listing-item' . esc_attr( $item_class ) . '">';
							echo $thumb;
							echo '<a class="widget-badgeos-item-title ' . esc_attr( $class ) . '" href="' . esc_url( $permalink ) . '">' . esc_html( $title ) . '</a>';
							echo '</li>';

							$thecount++;

							if ( $thecount == $number_to_show && $number_to_show != 0 && is_plugin_active( 'badgeos-community-add-on/badgeos-community.php' ) ) {
								echo '<li id="widget-achievements-listing-item-more" class="widget-achievements-listing-item">';
								echo '<a class="badgeos-item-thumb" href="' . bp_core_get_user_domain( bp_displayed_user_id() ) . 'bos-bp-achievements/"><span class="fa fa-ellipsis-h"></span></a>';
								echo '<a class="widget-badgeos-item-title ' . esc_attr( $class ) . '" href="' . bp_core_get_user_domain( bp_displayed_user_id() ) . 'bos-bp-achievements/">' . __( 'See All', 'buddyx' ) . '</a>';
								echo '</li>';
								break;
							}
						}
					}
				}

				echo '</ul><!-- widget-achievements-listing -->';
			}
		}
	}

}
