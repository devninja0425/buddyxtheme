<?php
/**
 * Template part for displaying the footer info
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;
$copyright = get_theme_mod( 'site_copyright_text' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

?>

	<div class="site-info">
		<div class="container">
			

			<?php if ( $copyright ) {
				echo $copyright; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				$current_year = date_i18n(
					/* translators: Copyright date format, see https://www.php.net/date */
					_x( 'Y', 'copyright date format', 'buddyx' )
				);
				echo sprintf(__('Copyright &copy; %1$s. All rights reserved by, %2$s.','buddyx'), $current_year ,'<a href="'.esc_url( home_url( '/' ) ).'">'.get_bloginfo( 'name' ).'</a>');
			} ?>
		</div>

	<?php
	if ( function_exists( 'the_privacy_policy_link' ) ) {
		the_privacy_policy_link();
	}
	?>
</div><!-- .site-info -->
