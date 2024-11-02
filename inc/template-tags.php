<?php

// article section title 
function emit_article_title() {
	the_title( '<h2 class="entry-title">', '</h2>' );
	if ( is_page() ) {
		echo '';
	} else {
		echo '<div class="entry-date">';
		the_time( get_option( 'date_format' ) );
		echo '</div>';
	}
}

// archives or search page title 
function emit_archive_search_title() {
	if ( is_archive() ) : the_archive_title(
		'<header class="page-header"><h2 class="entry-title archives-title">',
		'</h2></header>'
	);
	elseif ( is_search() ) : printf(
		__( '<header class="page-header"><h2 class="entry-title archives-title">' .
		'Search Results for: %s', 'emit' ), get_search_query() .
		'</h2></header>'
	);
	endif;
}

// post excerpt 
function emit_post_excerpt() {
	echo '<p class="summary-excerpt">' . wp_strip_all_tags( get_the_excerpt() ) . '</p>';
}

// post content with page-links for paginated posts 
function emit_post_content() {
	the_content();
	wp_link_pages( array(
		'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'emit' ) . '</span>',
		'after'       => '</div>',
		'link_before' => '<span>',
		'link_after'  => '</span>',
	) );
}

// post tags 
function emit_post_tags() {
	the_tags(
		'<div class="post-tags"><ul class="tag-links-list"><li class="tag-links" style="display: inline-block;">',
		'</li> <li class="tag-links" style="display: inline-block;">',
		'</li></ul></div>'
	);
}

// post navigation and post comments 
function emit_post_footer() {
	if ( is_single() && wp_count_posts()->publish > 1 ) {
		echo '<div class="post-nav"><nav id="nav-below" class="navigation" role="navigation">';
		echo '<ul><li class="nav-next">';
		next_post_link( '%link', '&#10094; Next Post' );
		echo '</li><li class="nav-previous">';
		previous_post_link( '%link', 'Prev<span class="nav-abbr">ious</span> Post &#10095;' );
		echo '</li></ul>';
		echo '</nav></div>';
		comments_template();
	}
}

// page navigation 
function emit_page_navigation() {
	global $wp_query;
	if ( $wp_query->max_num_pages > 1 ) {
		echo '<nav id="nav-below" class="navigation" role="navigation">';
		echo '<ul><li class="nav-next">';
		previous_posts_link( '&#10094; Newer' );
		echo '</li><li class="nav-previous">';
		next_posts_link( 'Older &#10095;' );
		echo '</li></ul>';
		echo '</nav>';
	}
}

// footer widgets 
function emit_footer_widgets() {
	if( is_active_sidebar( 'footer-full-width' ) ) {
		dynamic_sidebar( 'footer-full-width' );
	}
	if( is_active_sidebar( 'footer-1' ) ) {
		dynamic_sidebar( 'footer-1' );
	}
	if( is_active_sidebar( 'footer-2' ) ) {
		dynamic_sidebar( 'footer-2' );
	}
}

// copyright and post count 
function emit_site_colophon() {
	echo date( "Y" );
	echo ' &copy; ';
	bloginfo( 'name' );
	echo '<span class="post-count"> &ndash; ';
	$count_posts = wp_count_posts();
	$total_posts = $count_posts->publish;
	echo $total_posts . ' posts';
	echo '</span>';
}

// get featured image or first image or default image URL 
function emit_thumbnail_image_url() {
	if ( has_post_thumbnail() ) {
		the_post_thumbnail_url( 'large' );
	} else {
		global $post;
		$args = array(
			'post_type' => 'attachment',
			'numberposts' => -1,
			'post_mime_type' => 'image',
			'post_status' => null,
			'post_parent' => $post->ID,
			'posts_per_page' => 1
		);
		$attachments = get_posts( $args );
		if ( $attachments ) {
			foreach ( $attachments as $attachment ) {
				$src = wp_get_attachment_image_src( $attachment->ID, 'large' );
				if ( $src ) {
					echo $src[0];
				}
			}
		} else {
			echo get_stylesheet_directory_uri() . '/assets/images/default-post-image.png';
		}
	}
}

// add Open Graph and Twitter Card meta tags 
function emit_og_meta_tags() {
	$current_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$current_uri = $_SERVER['REQUEST_URI'];
	global $post;
	if ( is_single() ) { ?>
		<meta name="twitter:card" content="summary_large_image" />
		<meta property="og:url" content="<?php the_permalink() ?>" />
		<meta property="og:title" content="<?php echo wp_strip_all_tags( get_the_excerpt() ); ?>" />
		<meta property="og:description" content="<?php the_time( get_option( 'date_format' ) ); ?>" />
		<meta property="og:image" content="<?php emit_thumbnail_image_url() ?>" />
	<?php } elseif ( is_front_page() ) { ?>
		<meta name="twitter:card" content="summary" />
		<meta property="og:url" content="<?php echo get_home_url(); ?>" />
		<meta property="og:title" content="<?php bloginfo( 'name' ); ?>" />
		<meta property="og:description" content="<?php bloginfo( 'description' ); ?>" />
		<meta property="og:image" content="<?php site_icon_url() ?>" />
	<?php } else { ?>
		<meta name="twitter:card" content="summary" />
		<meta property="og:url" content="<?php echo $current_url; ?>" />
		<meta property="og:title" content="<?php bloginfo( 'name' ); ?>" />
		<meta property="og:description" content="<?php echo $current_uri; ?>" />
		<meta property="og:image" content="<?php site_icon_url() ?>" />
	<?php }
}
add_action( 'wp_head', 'emit_og_meta_tags', 99 );
