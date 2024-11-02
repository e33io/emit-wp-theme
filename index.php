<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<!-- emit wp theme - https://github.com/e33io/emit-wp-theme -->
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="<?php bloginfo( 'description' ); ?> - all content &copy; <?php bloginfo( 'name' ); ?> - all rights reserved" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="wrapper" class="hfeed">
<header id="site-header" class="site-header" role="banner">
	<h1 class="site-title">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
	</h1>
	<p class="tagline"><?php bloginfo( 'description' ); ?></p>	
	<nav id="menu">
		<?php wp_nav_menu( array( 'theme_location' => 'main-menu' ) ); ?>
	</nav>
</header>
<?php if ( is_singular() ) { while ( have_posts() ) : the_post(); ?>
<main id="main" class="site-main" role="main">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="page-header">
			<?php emit_article_title(); ?>
		</header>
		<div class="page-content">
			<?php emit_post_content(); ?>
			<?php emit_post_tags(); ?>
		</div>
	</article>
	<?php emit_post_footer(); ?>
</main>
<?php endwhile;
} elseif ( is_home() || is_archive() || is_search() ) { ?>
<main id="main" class="site-main" role="main">
	<?php emit_archive_search_title(); ?>
	<div class="page-content">
		<?php if( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" class="article-summary">
			<a href="<?php the_permalink(); ?>" class="link-to-post">
				<header class="header-summary">
					<?php emit_article_title(); ?>
				</header>
			</a>
			<div class="entry-summary">
				<?php emit_post_excerpt(); ?>
			</div>
			<a href="<?php the_permalink(); ?>" class="view-post-link">
				<span class="view-post"><?php _e( 'View Post', 'emit' ); ?></span>
			</a>
		</article>
		<?php endwhile; ?>
		<?php else : ?>
			<h5 class="search-error"><?php _e( 'Nothing found — try a new Search or select from the Menu or Archives', 'emit' ); ?></h5>
		<?php endif; ?>
	</div>
	<?php emit_page_navigation(); ?>
</main>
<?php } else { ?>
<main id="main" class="site-main" role="main">
	<header class="page-header">
		<h2 class="entry-title"><?php _e( 'Page not found', 'emit' ); ?></h2>
	</header>
	<div class="page-content">
		<h5 class="error-404"><?php _e( 'Please select from the Menu or Archives', 'emit' ); ?></h5>
	</div>
</main>
<?php } ?>
<footer id="site-footer" class="site-footer" role="contentinfo">
	<div id="footer-widgets">
		<?php emit_footer_widgets(); ?>
	</div>
	<div class="clear"></div>
	<div id="search">
		<?php get_search_form(); ?>
	</div>
	<div id="copyright">
		<?php emit_site_colophon(); ?>
	</div>
</footer>
<?php wp_footer(); ?>
</div>
</body>
</html>