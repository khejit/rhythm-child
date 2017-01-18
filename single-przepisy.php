<?php
/**
 * Created by PhpStorm.
 * User: khejit
 * Date: 2016-04-06
 * Time: 09:37
 */
?>

<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div class="page" id="top">
 *
 * @package Rhythm
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php if (ts_get_opt('enable-preloader') == 1):
	$preloader_custom_image = ts_get_opt_media('preloader-custom-image');
	?>
	<!-- Page Loader -->
	<div class="page-loader <?php echo ( !empty($preloader_custom_image) ? 'loader-custom-image' : '' ); ?>">
		<?php if (!empty($preloader_custom_image)): ?>
			<div class="loader-image"><img src="<?php echo esc_url($preloader_custom_image); ?>" alt="<?php _e('Loading...', 'rhythm'); ?>" /></div>
		<?php endif; ?>
		<div class="loader"><?php _e('Loading...', 'rhythm'); ?></div>
	</div>
	<!-- End Page Loader -->
<?php endif; ?>

<?php
//under construction
if (ts_get_opt('enable-under-construction') == 1 && !current_user_can('level_10')): ?>

<!-- Page Wrap -->
<div class="page" id="top">
	<?php get_template_part('templates/header/under-construction'); ?>

	<?php
	//top menu
	else: ?>

	<!-- Page Wrap -->
	<div class="page" id="top">



		<?php
		/*
		 * Default header layout
		 */
		$header_class = array();

		$header_class[] = 'stick-fixed';

		$header_class[] = 'dark';
		$logo_field = 'logo-light';

		$header_class[] = 'transparent';

		?>

		<!-- Navigation panel -->
		<nav class="main-nav <?php echo sanitize_html_classes(implode(' ',$header_class));?>">
			<div class="container relative clearfix">
				<div class="nav-logo-wrap local-scroll">
					<?php rhythm_logo($logo_field, get_template_directory_uri().'/images/logo-dark.png'); ?>
				</div>
				<div class="mobile-nav">
					<i class="fa fa-bars"></i>
				</div>
				<!-- Main Menu -->
				<div class="inner-nav desktop-nav">
					<?php
					$menu = '';

					if (has_nav_menu('primary')):
						wp_nav_menu(array(
							'theme_location'	=> 'primary',
							'menu'				=> $menu,
							'container'			=> false,
							'menu_id'			=> 'primary-nav',
							'menu_class'		=> 'clearlist scroll-nav local-scroll',
							'depth'				=> 3,
							'walker'			=> new rhythm_menu_widget_walker_nav_menu,
						));
					endif;
					?>

					<ul class="clearlist modules">

						<li>
							<a href="http://localhost/magiczny_blog/feed/" onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;">
								<i class="fa fa-rss-square"></i>RSS
							</a>
						</li>

						<!-- Search -->
						<li>
							<a href="#" class="mn-has-sub"><i class="fa fa-search"></i> <?php _e('Search','rhythm');?></a>
							<ul class="mn-sub">
								<li>
									<div class="mn-wrap">
										<form method="get" class="form" action="<?php echo esc_url(ts_get_home_url()); ?>">
											<div class="search-wrap">
												<button class="search-button animate" type="submit" title="<?php echo esc_attr(__('Start Search', 'rhythm'));?>">
													<i class="fa fa-search"></i>
												</button>
												<input type="text" name="s" class="form-control search-field" placeholder="<?php echo esc_attr(__('Search...', 'rhythm'));?>">
											</div>
										</form>
									</div>
								</li>
							</ul>
						</li>
						<!-- End Search -->

					</ul>

				</div>
				<!-- End Main Menu -->
			</div>
		</nav>
		<!-- End Navigation panel -->

		<?php endif; ?>



		<?php

		$container_class = 'relative align-left ';

		$size_class = 'home-section fixed-height-small';
		$title_class = 'mb-20 mb-xs-0';
		$container_class = 'js-height-parent';
		$before_row = '<div class="home-content"><div class="home-text">';
		$after_row = '</div></div>';

		$parallax_effect = 'parallax-4';
		$size_class .= ' '.$parallax_effect;

		$style_class = 'bg-dark';

		$subtitle_class = '';

		$background = ts_get_opt('recipe-title-wrapper-background'); //@TODO change this for my own option loaded to artykuly type
		$background_data = '';
		$add_bg = false;
		if( is_array($background) && $background['url'] != '' ) {
			$add_bg = true;
		}
		?>
		<!-- Title Wrapper Section -->
		<section class="title-wrapper <?php echo sanitize_html_classes($size_class); ?> <?php echo sanitize_html_classes($style_class);?>" <?php echo (true === $add_bg ? 'style="background-image: url('.esc_url($background['url']).');"' : ''); ?>>
			<div class="<?php echo sanitize_html_classes($container_class); ?> container">
				<?php echo $before_row; ?>
				<div class="row">
					<div class="col-md-8 align-left">
						<h1 class="hs-line-11 font-alt <?php echo sanitize_html_classes($title_class); ?>"><?php echo rhythm_get_title(); ?></h1>
						<?php $subtitle = ts_get_post_opt('recipe-title-wrapper-subtitle-local'); //@TODO make this own option
						if (!empty($subtitle)): ?>
							<div class="hs-line-4 font-alt <?php echo sanitize_html_classes($subtitle_class);?>">
								<?php echo wp_kses_data($subtitle); ?>
							</div>
						<?php endif; ?>
					</div>
					<div class="col-md-4 mt-30">
						<?php rhythm_breadcrumbs(); ?>
					</div>
				</div>
				<?php echo $after_row; ?>
			</div>
		</section>
		<!-- End Title Wrapper Section -->

		<!-- Page Section -->
		<section class="main-section page-section">
			<div class="container relative">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">

						<?php while ( have_posts() ) : the_post(); ?>

							<!-- Post -->
							<div id="post-<?php the_ID(); ?>" <?php post_class('blog-item mb-80 mb-xs-40'); ?>>

								<!-- Text -->
								<div class="blog-item-body">

									<?php the_content(); ?>

									<?php
									wp_link_pages( array(
										'before' => '<div class="page-links">' . __( 'Pages:', 'rhythm' ),
										'after'  => '</div>',
									) );
									?>

									<footer class="entry-footer">
										<?php rhythm_entry_footer(); ?>
									</footer><!-- .entry-footer -->

								</div>
								<!-- End Text -->

							</div>
							<!-- End Post -->

							<?php
							// If comments are open or we have at least one comment, load up the comment template
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;
							?>
							<?php rhythm_post_navigation(); ?>

						<?php endwhile; // end of the loop. ?>

					</div>
					<!-- End Page Content -->
				</div><!-- .row -->		</div>
		</section>
		<!-- End Page Section -->


		<?php
		/**
		 * The template for displaying the footer.
		 *
		 * Contains the closing of the #content div and all content after
		 *
		 * @package Rhythm
		 */

		?>

		<?php
		/*
		 * Footer default layout
		 */
		?>
		<?php
		if (ts_get_opt('footer-widgets-enable') == 1): ?>
			<!-- Divider -->
			<hr class="mt-0 mb-0"/>
			<!-- End Divider -->

			<!-- Widgets Section -->
			<section class="footer-sidebar page-section">
				<div class="container relative">
					<div class="row multi-columns-row">
						<div class="col-sm-6 col-md-5 col-md-offset-1">
							<?php if (is_active_sidebar( ts_get_custom_sidebar('footer-1', 'footer-sidebar-1') )): ?>
								<?php dynamic_sidebar( ts_get_custom_sidebar('footer-1', 'footer-sidebar-1') ); ?>
							<?php endif; ?>
						</div>

						<div class="col-sm-6 col-md-5">
							<?php if (is_active_sidebar( ts_get_custom_sidebar('footer-2', 'footer-sidebar-2') )): ?>
								<?php dynamic_sidebar( ts_get_custom_sidebar('footer-2', 'footer-sidebar-2') ); ?>
							<?php endif; ?>
						</div>

					</div>
				</div>
			</section>


			<!-- End Widgets Section -->
		<?php endif; ?>

		<?php if (ts_get_opt('footer-enable') == 1): ?>
			<!-- Foter -->
			<footer class="page-section bg-gray-lighter footer pb-60">
				<div class="container">

					<!-- Footer Logo -->
					<?php if (ts_get_opt('footer-logo-enable')): ?>
						<div class="local-scroll mb-30 wow fadeInUp" data-wow-duration="1.5s">
							<?php rhythm_logo('footer-logo', get_template_directory_uri().'/images/logo-footer.png', ''); ?>
						</div>
					<?php endif; ?>
					<!-- End Footer Logo -->
					<?php
					if (ts_get_opt('footer-enable-social-icons') == 1): ?>
						<!-- Social Links -->
						<div class="footer-social-links mb-110 mb-xs-60">
							<?php rhythm_social_links('%s',ts_get_opt('footer-social-icons-category')); ?>
						</div>
						<!-- End Social Links -->
					<?php endif; ?>

					<!-- Footer Text -->
					<div class="footer-text">
						<div class="footer-copy font-alt">
							<?php echo ts_get_opt('footer-text-content'); ?>
						</div>
						<div class="footer-made">
							<?php echo ts_get_opt('footer-small-text-content'); ?>
						</div>
					</div>
					<!-- End Footer Text -->
				</div>

				<!-- Top Link -->
				<div class="local-scroll">
					<a href="#top" class="link-to-top"><i class="fa fa-caret-up"></i></a>
				</div>
				<!-- End Top Link -->

			</footer>
			<!-- End Foter -->
		<?php endif; ?>

		<secton class="footer-mini">

			<div class="container relative">

				<div class="row">
					<div class="col-xs-12">

						<p><a href="http://blog.magicznyogrod.pl/regulamin/">regulamin</a></p>
						<p><a href="http://blog.magicznyogrod.pl/regulamin/">polityka prywatności</a></p>
						<p><a href="http://blog.magicznyogrod.pl/kontakt/">kontakt</a></p>

						<p id="psocial-footer">
							<a href="https://www.facebook.com/MagicznyOgrod/" onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;"><i class="fa fa-facebook-square"></i></a>
							<a href="https://plus.google.com/+magicznyogr%C3%B3d" onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;"><i class="fa fa-google-plus-square"></i></a>
						</p>

					</div>
				</div>

			</div>

		</secton>


	</div>
	<!-- End Page Wrap -->
	<?php wp_footer(); ?>
</body>
</html>