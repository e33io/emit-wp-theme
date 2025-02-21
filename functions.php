<?php

// setup theme 
function emit_init() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'disable-custom-colors' );
	add_theme_support( 'editor-color-palette', array() );
	add_theme_support( 'disable-custom-gradients' );
	add_theme_support( 'editor-gradient-presets', array() );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'script',
		'style',
	) );
	global $content_width;
	if ( ! isset( $content_width ) ) { $content_width = 1080; }
	register_nav_menus( array( 'main-menu' => __( 'Main Menu', 'emit' ) ) );
	load_theme_textdomain( 'emit', get_template_directory() . '/languages' );
	// remove SVG and global styles
	remove_action( 'wp_enqueue_scripts','wp_enqueue_global_styles' );
	// remove wp_footer action that adds global inline styles
	remove_action( 'wp_footer','wp_enqueue_global_styles',1 );
	// remove render_block filters
	remove_filter( 'render_block','wp_render_duotone_support' );
	remove_filter( 'render_block','wp_restore_group_inner_container' );
	remove_filter( 'render_block','wp_render_layout_support_flag' );
}
add_action( 'after_setup_theme', 'emit_init' );

// enqueue block editor styles
function emit_block_editor_styles() {
	wp_enqueue_style( 'emit-block-editor-css', get_theme_file_uri( '/assets/css/block-editor.css' ), false );
}
add_action( 'enqueue_block_editor_assets', 'emit_block_editor_styles' );

// enqueue styles and scripts 
function emit_scripts_styles() {
	wp_enqueue_style( 'emit-style', get_stylesheet_uri() );
	wp_enqueue_style( 'emit-inter-font', get_theme_file_uri( '/assets/fonts/inter/inter.css' ), false );
	wp_enqueue_style( 'emit-sovranmono-font', get_theme_file_uri( '/assets/fonts/sovranmono/sovranmono.css' ), false );
	if ( ( ! is_admin() ) && is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	// remove theme.json
	wp_dequeue_style( 'global-styles' );
}
add_action( 'wp_enqueue_scripts', 'emit_scripts_styles' );

// add template-tags file 
require get_template_directory() . '/inc/template-tags.php';

// register optimized styles 
function emit_register_optimized_styles() {
    wp_register_style( 'comments-optimized', get_template_directory_uri() . '/assets/css/comments.css' );
    wp_register_style( 'jetpack-optimized', get_template_directory_uri() . '/assets/css/jetpack.css' );
}
add_action('init', 'emit_register_optimized_styles');

// enqueue optimized styles 
function emit_enqueue_optimized_styles() {
    if ( is_singular() && comments_open() || is_singular() && get_comments_number() ) {
        wp_enqueue_style( 'comments-optimized' );
    }
    if ( in_array( 'jetpack/jetpack.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		wp_enqueue_style( 'jetpack-optimized' );
	}
}
add_action( 'wp_enqueue_scripts', 'emit_enqueue_optimized_styles' );

// change excerpt more characters 
function emit_excerpt_more( $more ) {
	global $post;
	return '<a class="more-link" href="' . get_permalink($post->ID) . '">...</a>';
}
add_filter( 'excerpt_more', 'emit_excerpt_more' );

// change content more text 
function emit_read_more_link() {
	return '... <a class="more-link" href="' . get_permalink() . '">continue reading...</a>';
}
add_filter( 'the_content_more_link', 'emit_read_more_link' );

// change excerpt length 
add_filter( 'excerpt_length', function( $length ) {
	return 27;
} );

// setup footer widgets 
function emit_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Primary Footer (Full-Width)', 'emit' ),
		'id'            => 'footer-full-width',
		'description'   => esc_html__( 'Add widgets here.', 'emit' ),
		'before_widget' => '<div id="%1$s" class="widget full-width-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-full-width-title widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Secondary Footer 1', 'emit' ),
		'id'            => 'footer-1',
		'description'   => esc_html__( 'Add widgets here.', 'emit' ),
		'before_widget' => '<div id="%1$s" class="widget widget-1 %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Secondary Footer 2', 'emit' ),
		'id'            => 'footer-2',
		'description'   => esc_html__( 'Add widgets here.', 'emit' ),
		'before_widget' => '<div id="%1$s" class="widget widget-2 %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'emit_widgets_init' );

// modify tag cloud widget 
function mod_tag_cloud_widget() {
	$args = array(
		'smallest' => 0.9375,
		'largest' => 1.75,
		'unit' => 'rem',
		'echo' => false,
	);
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'mod_tag_cloud_widget' );

// remove URL field from Comment form 
function unset_url_field( $fields ) {
    if( isset( $fields['url'] ) )
       unset( $fields['url'] );
       return $fields;
}
add_filter('comment_form_default_fields', 'unset_url_field');

// remove query strings from static resources 
function _remove_script_version( $src ) {
	$parts = explode( '?', $src );
	return $parts[0];
}
add_filter( 'script_loader_src', '_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', '_remove_script_version', 15, 1 );

// header cleanup 
add_action( 'do_feed_rss2_comments', 'disable_feeds', -1 );
add_action( 'do_feed_atom_comments', 'disable_feeds', -1 );
add_action( 'feed_links_show_comments_feed', '__return_false', -1 );
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );

// disable JetPack CSS 
add_filter( 'jetpack_sharing_counts', '__return_false', 99 );
add_filter( 'jetpack_implode_frontend_css', '__return_false', 99 );

// disable the emojis 
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
}
add_action( 'init', 'disable_emojis' );
function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}
function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' == $relation_type ) {
		$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
		$urls = array_diff( $urls, array( $emoji_svg_url ) );
	}
	return $urls;
}

// redirect attachment page to post 
function emit_redirect_attachment_page() {
	if ( is_attachment() ) {
		global $post;
		if ( $post && $post->post_parent ) {
			wp_redirect( esc_url( get_permalink( $post->post_parent ) ), 301 );
			exit;
		} else {
			wp_redirect( esc_url( home_url( '/' ) ), 301 );
			exit;
		}
	}
}
add_action( 'template_redirect', 'emit_redirect_attachment_page' );

// enqueue Contact Form 7 scripts and styles only on pages 
function opt_cf7_dequeue() {
	if ( ! is_page() ) {
		wp_dequeue_script( 'contact-form-7' );
		wp_dequeue_style( 'contact-form-7' );
	}
}
add_action( 'wp_enqueue_scripts', 'opt_cf7_dequeue' );

// disable Contact Form 7 validation 
add_filter( 'wpcf7_validate_configuration', '__return_false' );

// add Site Icon login logo start 
function emit_login_logo() { ?>
	<style type="text/css">
		.login h1 a {
			background-image: url( <?php site_icon_url() ?> );
			background-size: contain;
			width: 90px;
			height: 90px;
			border-radius: 45px;
		}
	</style>
<?php }
add_action( 'login_head', 'emit_login_logo' );
function emit_login_url() {
	return '/';
}
add_filter( 'login_headerurl', 'emit_login_url' );

// delay RSS feed 
function emit_delay_rss_feed( $where ) {
	global $wpdb;
	if ( is_feed() ) {
		$now = gmdate( 'Y-m-d H:i:s' );
		$wait = '1'; // integer 
		$device = 'HOUR'; // MINUTE, HOUR, DAY, WEEK, MONTH, YEAR 
		$where .= " AND TIMESTAMPDIFF( $device, $wpdb->posts.post_date_gmt, '$now' ) > $wait ";
	}
	return $where;
}
add_filter( 'posts_where', 'emit_delay_rss_feed' );
