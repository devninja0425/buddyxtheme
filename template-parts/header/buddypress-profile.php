<?php
// User Messages
if ( class_exists( 'BuddyPress' ) && is_user_logged_in() && bp_is_active( 'messages' ) ) { ?>
     <div class="bp-msg">
        <a class="bp-icon-wrap" href="<?php echo bp_loggedin_user_domain() . bp_get_messages_slug(); ?>">
        <span class="fa fa-envelope"></span>
        <?php
            if ( function_exists( 'bp_total_unread_messages_count' ) ) {
                $count = bp_get_total_unread_messages_count();
                if ( $count > 0 ) { ?>
                    <sup><?php bp_total_unread_messages_count(); ?></sup><?php
                } else {
                    echo '<sup>0</sup>';
                }
            }
        ?>
        </a>
  </div>
  <?php
}
// User notifications
if ( class_exists( 'BuddyPress' ) && is_user_logged_in() && bp_is_active( 'notifications' ) ) {
  global $bp;
  ?>
  <div class="user-notifications">
    <a class="bp-icon-wrap" href="<?php echo esc_url( bp_loggedin_user_domain() . $bp->notifications->slug ); ?>" title="<?php _e( esc_attr( 'Notifications' ), 'buddyx' ); ?>">
        <span class="fa fa-bell"></span>
        <?php
        if ( function_exists( 'bp_notifications_get_unread_notification_count' ) ) {
            $count = bp_notifications_get_unread_notification_count( get_current_user_id() );
            ?>
            <sup> <?php echo esc_html( $count ); ?></sup><?php
        }
        ?>
    </a>
    <?php
    $notifications = bp_notifications_get_notifications_for_user( bp_loggedin_user_id() );
    if ( $notifications ) { ?>
        <ul id="bp-notify" class="bp-header-submenu bp-dropdown"><?php
            rsort( $notifications );
            foreach ( $notifications as $notification ) { ?>
                <li><?php echo $notification; ?></li><?php
            } ?>
            <li class="bp-view-all">
                <a href="<?php echo esc_url( bp_loggedin_user_domain() . $bp->notifications->slug ); ?>"><?php _e( 'View all notifications', 'buddyx' ); ?></a>
            </li>
        </ul>
    <?php } else { ?>
      <ul id="bp-notify" class="bp-header-submenu bp-dropdown bp-notify">
        <li><a href="<?php bp_loggedin_user_domain() . BP_NOTIFICATIONS_SLUG ?>"><?php _e( "No new notifications", "buddypress" ); ?></a></li>
      </ul>
    <?php }
    ?>
  </div><?php
}
// User Account Details
if ( is_user_logged_in() ) {
  $current_user = wp_get_current_user();
  if ( ($current_user instanceof WP_User ) ) {
    $user_link = function_exists( 'bp_core_get_user_domain' ) ? bp_core_get_user_domain( get_current_user_id() ) : '#';
    echo '<div class="user-link-wrap">';
    echo '<a class="user-link">';
    ?>
    <span class="bp-user"><?php echo $current_user->display_name; ?></span>
    <?php
    echo get_avatar( $current_user->user_email, 100 );
    echo '</a>';
    wp_nav_menu( array( 'theme_location' => 'user_menu', 'menu_id' => 'user-profile-menu', 'fallback_cb' => '', 'container' => false, 'menu_class' => 'user-profile-menu', ) );
    echo '</div>';
    }
    } else {
      global $wbtm_buddyx_settings;
      $login_page_url = wp_login_url();
      if ( isset( $settings[ 'buddyx_pages' ][ 'buddyx_login_page' ] ) && ( $wbtm_buddyx_settings[ 'buddyx_pages' ][ 'buddyx_login_page' ] != '-1' ) ) {
        $login_page_id   = $wbtm_buddyx_settings[ 'buddyx_pages' ][ 'buddyx_login_page' ];
        $login_page_url  = get_permalink( $login_page_id );
      }
      $registration_page_url = wp_registration_url();
      if ( isset( $wbtm_buddyx_settings[ 'buddyx_pages' ][ 'buddyx_register_page' ] ) && ( $wbtm_buddyx_settings[ 'buddyx_pages' ][ 'buddyx_register_page' ] != '-1' ) ) {
        $registration_page_id  = $wbtm_buddyx_settings[ 'buddyx_pages' ][ 'buddyx_register_page' ];
        $registration_page_url   = get_permalink( $registration_page_id );
      }
      ?>
  <div class="bp-icon-wrap">
    <a href="<?php echo $login_page_url; ?>" class="btn-login" title="Login"> <span class="fa fa-user"></span>Log in</a>
  </div>
  <?php
  if ( get_option( 'users_can_register' ) ) {
    ?>
    <div class="bp-icon-wrap">
      <a href="<?php echo $registration_page_url; ?>" class="btn-register" title="Register"><span class="fa fa-address-book"></span>Register</a>
    </div>
    <?php }
}