<?php
/*
Plugin Name: JITB Admin
Plugin URI: http://springbox.com.au/
Description: Custom admin theme for Jack in the box sites. Hope you like orange.
Author: Jack in the box
Version: 2.0.0
Author URI: http://springbox.com.au
*/

namespace jitb_admin;


// LOGIN STYLESHEET
// --------------------------------------------------

function login_styles() { 

    wp_enqueue_style( 'jitb-login', plugins_url('/css/custom-login.css', __FILE__));
}

add_action( 'login_enqueue_scripts', __NAMESPACE__ . '\\login_styles' );



// ADMIN STYLESHEET
// --------------------------------------------------

function admin_styles() {

    wp_enqueue_style('jitb-admin', plugins_url('/css/custom-admin.css', __FILE__));
}

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\admin_styles' );



// ADMIN BAR STYLESHEET (Admin & Frontend)
// --------------------------------------------------

function admin_bar_styles() {

  wp_enqueue_style('jitb-admin-bar', plugins_url('css/custom-admin-bar.css', __FILE__));
}

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\admin_bar_styles' );
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\admin_bar_styles' );



// LOGIN LOGO
// --------------------------------------------------

function login_logo() { ?>

    <style type="text/css">
        body.login div#login h1 a {
            background-image: url( <?php echo plugins_url( 'images/logo-dark.svg' , __FILE__ ); ?> );
        }
    </style>

<?php } add_action( 'login_enqueue_scripts', __NAMESPACE__ . '\\login_logo' );



// ADMIN BAR LOGO (Frontend & Backend)
// --------------------------------------------------

function admin_logo( $wp_admin_bar ){

  $args = array(
    'id' => 'jitb-logo',
    'href' => 'https://www.thebox.com.au',
    'meta' => array(
        'class' => '',
        'target' => '_blank'
    )
  );
  $wp_admin_bar->add_node( $args );
}

add_action( 'admin_bar_menu', __NAMESPACE__ . '\\admin_logo', 10 );


function admin_logo_styles() { ?>

    <style type="text/css">
        #wp-admin-bar-jitb-logo > .ab-item {
            background-image: url( <?php echo plugins_url( 'images/logo-light.svg' , __FILE__ ); ?> ) !important;
        }
    </style>

<?php } 

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\admin_logo_styles' );
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\admin_logo_styles' );



// CHANGE LOGIN LINK
// --------------------------------------------------

function login_logo_custom_link() {

  	return 'https://www.thebox.com.au';
}
add_filter( 'login_headerurl', __NAMESPACE__ . '\\login_logo_custom_link');



// REMOVE WP LINKS FROM ADMIN BAR
// --------------------------------------------------

function cleanup_admin_bar() {

    global $wp_admin_bar;
  	
  	$wp_admin_bar->remove_node( 'wp-logo' );
    $wp_admin_bar->remove_node( 'about' );            // Remove the about WordPress link
    $wp_admin_bar->remove_node( 'wporg' );            // Remove the WordPress.org link
    $wp_admin_bar->remove_node( 'documentation' );    // Remove the WordPress documentation link
    $wp_admin_bar->remove_node( 'support-forums' );   // Remove the support forums link
    $wp_admin_bar->remove_node( 'feedback' );         // Remove the feedback link
    $wp_admin_bar->remove_node( 'new-content' );      // Remove the content link

    $wp_admin_bar->remove_node( 'customize' );
    $wp_admin_bar->remove_node( 'comments' );

    $wp_admin_bar->remove_node( 'widgets' );
    $wp_admin_bar->remove_node( 'themes' );

    $wp_admin_bar->remove_node( 'itsec_admin_bar_menu' );
}
add_action( 'wp_before_admin_bar_render', __NAMESPACE__ . '\\cleanup_admin_bar' );



// CHANGE FOOTER TEXT
// --------------------------------------------------

function footer_admin_credits() { ?>

    <hr/>

    <div class="jitb-admin-footer">

        <div class="jitb-help-support">

            <?php if ( !empty ( $GLOBALS['admin_page_hooks']['theme-help'] ) ) : // If theme-help page exists add button ?>
    
                <a class="button button-primary" href="<?php echo site_url( '/wp-admin/admin.php?page=theme-help' ); ?>">Help Documentation</a> 
            
            <?php endif; ?>

            <a class="button" href="mailto:support@springbox.com.au">Email Support</a>

        </div>

      	<div class="jitb-credits">

            <p>Built with WordPress by <a href="https://www.thebox.com.au" target="_blank">Jack in the Box</a></p>      
            
        </div>

    </div>    

<?php } add_filter('admin_footer_text', __NAMESPACE__ . '\\footer_admin_credits');
	


// CHANGE 'DASHBOARD' TO 'SPRINGBOARD'
// --------------------------------------------------

function rename_dashboard( $menu ) {

  	$menu = str_ireplace( 'Dashboard', 'Springboard', $menu );
  	return $menu;
}
add_filter( 'gettext', __NAMESPACE__ . '\\rename_dashboard' );
add_filter( 'ngettext', __NAMESPACE__ . '\\rename_dashboard' );



// REMOVE DASHBOARD PANELS
// --------------------------------------------------

function cleanup_dashboard_items() {

    remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
    // remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
}
add_action( 'admin_init', __NAMESPACE__ . '\\cleanup_dashboard_items' );



// HIDE NOTIFICATIONS FOR NON-ADMINS
// --------------------------------------------------

function jitb_hide_notifications() {	

    if ( !current_user_can( 'manage_options' ) ) {
        remove_action ( 'admin_notices', 'update_nag', 3 );	
    }
}
add_action ( 'admin_notices', __NAMESPACE__ . '\\jitb_hide_notifications', 1 );

function jitb_hide_plugin_messages() { ?>

    <style type="text/css">
        div#setting-error-tgmpa { 
            display: none; 
        }
    </style>

<?php } add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\jitb_hide_plugin_messages' );



// Remove admin color picker 
// --------------------------------------------------

remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );



// Remove Welcome Panel
// --------------------------------------------------

remove_action('welcome_panel', 'wp_welcome_panel');



// ==================================================
// SCRAP
// ==================================================


// ==================================================
// Image Uploader
// ==================================================
 
// function admin_scripts() {

//   if ( isset( $_GET['page'] ) && $_GET['page'] == 'my-setting-admin' ) {

//     wp_enqueue_media();
//     wp_register_script( 'custom-scripts', plugins_url( '/js/custom.js', __FILE__ ), array( 'jquery','media-upload','thickbox' ) );
//     wp_enqueue_script( 'custom-scripts' );
//   }
// }
// add_action('admin_enqueue_scripts', __NAMESPACE__ . '\\admin_scripts');


/*-----------------------------------------------------------------------------------*/
/* Image Uploader */
/*-----------------------------------------------------------------------------------*/
// add_action('admin_enqueue_scripts', 'jitb_admin_scripts');
 
// function jitb_admin_scripts() {
//   if ( isset( $_GET['page'] ) && $_GET['page'] == 'my-setting-admin' ) {
//     wp_enqueue_media();
//     wp_register_script( 'custom-scripts', plugins_url( '/js/custom.js', __FILE__ ), array( 'jquery','media-upload','thickbox' ) );
//     wp_enqueue_script( 'custom-scripts' );
//   }
// }


// ==================================================
// Remove edit page elements
// ==================================================

// if ( is_admin() ) {

//  function remove_meta_boxes() {

//     // if( !current_user_can('manage_options') ) {
//      // Custom Fields
//      // remove_meta_box( 'postcustom' , 'page' , 'normal' );
//      // Discussion
//      // remove_meta_box( 'commentsdiv' , 'page' , 'normal' );
//      // Comments
//      // remove_meta_box( 'commentstatusdiv' , 'page' , 'normal' );
//      // Revisions
//      // remove_meta_box( 'revisionsdiv' , 'page' , 'normal' );
//     // }
//  }
//  add_action( 'admin_menu', __NAMESPACE__ . '\\remove_meta_boxes' );

// }


?>

