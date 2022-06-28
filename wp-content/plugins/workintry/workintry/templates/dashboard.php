<?php
/**
 * Template Name: User Dashboard
 *
 * @package Workintry
 * @since Workintry 1.0
 * @desc Dashboard.
 */
/* Define Global Variables */
if( !is_user_logged_in() ){
	$login_page_url	= codesquare_workintry_get_settings_option('register_page');
	if( !empty($login_page_url) ) {
		$login_page_url	= get_the_permalink( $login_page_url );
		wp_safe_redirect( $login_page_url );
		exit;
	}
}
global $current_user, $wp_roles, $userdata, $post;

$user_identity = $current_user->ID;
$url_identity = $user_identity;

if (isset($_GET['identity']) && !empty($_GET['identity'])) {
    $url_identity = sanitize_text_field($_GET['identity']);
}
if( is_user_logged_in() ){
	if( $current_user->ID != $url_identity ){
		esc_html_e('No Kiddies Please', 'workintry');
		die();
	}
} else {
	esc_html_e('No Kiddies Please', 'workintry');
	die();
}

$cl_menu = codesquare_workintry_get_settings_option('dash-menu');
$default_directory = codesquare_workintry_get_settings_option('workintry_default_system');
$default_directory = !empty( $default_directory ) ? $default_directory : 'default';
if( $default_directory == 'default' ){
	$workintry_default = 'add';
} elseif( $default_directory == 'car'){
	$workintry_default = 'car';
} elseif( $default_directory == 'mobile' ){
	$workintry_default = 'mobile';
} elseif( $default_directory == 'property'){
	$workintry_default = 'home';
} else {
	$workintry_default = 'add';
}

$logo = codesquare_workintry_get_settings_option('logo');
$copyright = codesquare_workintry_get_settings_option( 'cl_dashboard_copy' );
$copyright = !empty( $copyright ) ? $copyright : esc_html__('© 2020 Workintry All rights reserved. Design by CodeSquareCo', 'workintry');
$cl_class = '';
if( is_user_logged_in() ){
	$cl_class = 'pc-wrapper';
} else {
	$cl_class = '';
}

//Profile page
$profile_page = codesquare_workintry_get_profile_url();

?>
<!doctype html>
<!--[if (gt IE 9)|!(IE)]><html lang="en"><![endif]-->
<html <?php language_attributes(); ?>>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
        <?php wp_head(); ?>
    </head>
	<body <?php body_class('pc-home'); ?>>
		<!-- Wrapper Start -->
		<div id="pc-wrapper" class="pc-wrapper pc-haslayout <?php echo esc_attr( $cl_class ); ?>">
			<!-- Header Start -->
			<header id="pc-header" class="pc-header pc-haslayout">
				<div class="pc-navigationarea">
					<div class="container-fluid">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<?php if( !empty( $logo ) ){ ?>
									<strong class="pc-logo"><a href="<?php echo esc_url( $profile_page ); ?>"><img src="<?php echo esc_url( $logo ); ?>" alt="<?php esc_attr_e('logo', 'workintry'); ?>"></a></strong>
								<?php } ?>
								<div class="pc-rightarea">
									<?php if( !empty( $cl_menu ) ) { ?>
										<nav id="pc-nav" class="pc-nav navbar-expand-lg">
											<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
												<i class="lnr lnr-menu"></i>
											</button>
											<?php
												wp_nav_menu(
													array(
														'theme_location' => $cl_menu,
														'menu_class'     => 'navbar-nav pc-navigation',
														'items_wrap'     => '<ul id="navbarNavs" class="%2$s navbar-nav">%3$s</ul>',	
														'container_class'=> 'collapse navbar-collapse pc-navigation',
														'container_id'=> 'navbarNav',
													)
												);
												?>										
										</nav>
									<?php } ?>
									<div class="pc-loginholder">
										<div class="pc-notiarea pc-notice-chat-count-<?php echo esc_attr( $current_user->ID ); ?>">
											<a href="<?php codesquare_workintry_profile_menu_link($profile_page, 'chat', $user_identity, '', 'profile'); ?>"><i class="lnr lnr-envelope"></i><em><?php echo esc_html( codesquare_workintry_get_total_unseen_count() ); ?></em></a>
										</div>
										<div class="pc-loginarea">
											<?php do_action('codesquare_workintry_print_user_profile_top_image'); 
												codesquare_workintry_Print_Profile_Menu('sub-menu'); 
											?>										
										</div>
										<div class="pc-addlistingbtn">
											<a href="<?php codesquare_workintry_profile_menu_link($profile_page, $workintry_default, $user_identity, '', 'ad'); ?>" class="pc-btn"><i class="fa fa-plus"></i><?php esc_html_e('Create Gig', 'workintry'); ?>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</header>
			<!-- Header End -->
			<!-- Main Start -->			
			<main id="pc-main" class="pc-main">
				<!-- Nav Sidebar Start -->
				<div class="pc-sidebarholder">
					<a href="javascript:void(0);" class="pc-menures"><i class="lnr lnr-menu"></i></a>
					<?php do_action('codesquare_workintry_print_user_profile_image'); ?>		
					<?php codesquare_workintry_Print_Profile_Menu_Html(); ?>	
				</div>
				<!-- Sidebar End -->
				<?php 
					//Load Specific Templates
				if ( isset($_GET['rule']) && $_GET['rule'] === 'insight' && $url_identity == $user_identity ) {
					require_once codesquare_workintry_addon_template_exsits('workintry/front-end/dashboard-templates/insights');							
				} elseif( isset($_GET['rule']) && $_GET['rule'] === 'add' && $url_identity == $user_identity ){
					require_once codesquare_workintry_addon_template_exsits('workintry/front-end/dashboard-templates/add');
				} elseif( isset($_GET['rule']) && $_GET['rule'] === 'profile' && $url_identity == $user_identity ){
					require_once codesquare_workintry_addon_template_exsits('workintry/front-end/dashboard-templates/profile');
				} elseif( isset( $_GET['rule'] ) && $_GET['rule'] === 'edit' && $url_identity == $user_identity ){
					require_once codesquare_workintry_addon_template_exsits('workintry/front-end/dashboard-templates/edit');
				} elseif( isset($_GET['rule']) && $_GET['rule'] === 'listings' && $url_identity == $user_identity ){
					require_once codesquare_workintry_addon_template_exsits('workintry/front-end/dashboard-templates/listings');
				} elseif( isset($_GET['rule']) && $_GET['rule'] === 'packages' && $url_identity == $user_identity ){
					require_once codesquare_workintry_addon_template_exsits('workintry/front-end/dashboard-templates/packages');
				} elseif( isset($_GET['rule']) && $_GET['rule'] === 'favourite' && $url_identity == $user_identity ){
					require_once codesquare_workintry_addon_template_exsits('workintry/front-end/dashboard-templates/favourites');
				} elseif( isset($_GET['rule']) && $_GET['rule'] === 'security' && $url_identity == $user_identity ){
					require_once codesquare_workintry_addon_template_exsits('workintry/front-end/dashboard-templates/security');
				}elseif( isset($_GET['rule']) && $_GET['rule'] === 'chat' && $url_identity == $user_identity ){
					require_once codesquare_workintry_addon_template_exsits('workintry/front-end/dashboard-templates/chat');
				}elseif( isset($_GET['rule']) && $_GET['rule'] === 'earnings' && $url_identity == $user_identity ){
					require_once codesquare_workintry_addon_template_exsits('workintry/front-end/dashboard-templates/earnings');
				}elseif( isset($_GET['rule']) && $_GET['rule'] === 'expenses' && $url_identity == $user_identity ){
					require_once codesquare_workintry_addon_template_exsits('workintry/front-end/dashboard-templates/expenses');
				}elseif( isset($_GET['rule']) && $_GET['rule'] === 'order' && $url_identity == $user_identity ){
					require_once codesquare_workintry_addon_template_exsits('workintry/front-end/dashboard-templates/order');
				} else {
					require_once codesquare_workintry_addon_template_exsits('workintry/front-end/dashboard-templates/insights');
				}				
				?>
				<div id="audio-file" data-id="<?php echo esc_attr( CSC_WORKINTRY_PLUGIN_URL . 'assets/audio/beep.wav' ); ?>"></div>				
				<!-- Footer Start -->
				<div class="pc-footer pc-haslayout">
					<span><?php echo esc_html( $copyright ); ?></span>
				</div>
				<!-- Footer End -->				
			</main>
			<!-- Main End -->
		</div>
		<!-- Wrapper End -->
		<?php wp_footer(); ?>
	</body>
</html>