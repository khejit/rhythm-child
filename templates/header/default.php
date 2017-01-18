<?php
/* 
 * Default header layout
 */
$header_class = array();

switch (ts_get_opt('header-fixed-switch')) {
	case 'sticky':
		$header_class[] = 'js-stick';
		break;
	
	case 'fixed':
		$header_class[] = 'stick-fixed';
		break;
}

if (ts_get_opt('header-style') == 'dark') {
	$header_class[] = 'dark';
	$logo_field = 'logo-light';
} else {
	$logo_field = 'logo';
}

if (ts_get_opt('header-bg-type') == 'transparent') {
	$header_class[] = 'transparent';
}
?>

<?php if (ts_get_opt('header-enable-switch') == 1): ?>
	<!-- Navigation panel -->
	<nav class="main-nav <?php echo sanitize_html_classes(implode(' ',$header_class));?>">
		<div class="<?php echo (ts_get_opt('header-full-width') == 1 ? 'full-wrapper' : 'container') ?> relative clearfix">
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
				if( is_singular() ) {
					$menu = ts_get_post_opt('header-primary-menu');
				}
				
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
						<a href="http://blog.magicznyogrod.pl/feed/" onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;">
							<i class="fa fa-rss-square"></i>RSS
						</a>
					</li>

					<?php if (ts_get_opt('header-enable-search')): ?>
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
					<?php endif; ?>
						 
					<?php if (ts_get_opt('header-enable-cart') && class_exists( 'WooCommerce' )): ?>
						<!-- Cart -->
						<li>
							<a class="cart-contents" href="<?php echo WC()->cart->get_cart_url(); ?>"><i class="fa fa-shopping-cart"></i> <?php echo sprintf(__('Cart(%d)','rhythm'),WC()->cart->cart_contents_count); ?></a>
						</li>
						<!-- End Cart -->
					<?php endif; ?>
						
					<?php if (ts_get_opt('header-enable-languages') && function_exists('icl_get_languages')): ?>
						<?php 
						global $sitepress_settings;
			
						$skip_missing = 0;
						$languages = icl_get_languages('skip_missing='.$skip_missing.'&orderby=KEY&order=DIR&link_empty_to=str');
						$active_language = null;
						if (is_array($languages) && count($languages) > 0):
							foreach ($languages as $language):
								if ($language['active'] == 1):
									if (isset($sitepress_settings['icl_lso_native_lang']) && $sitepress_settings['icl_lso_native_lang'] == 1):
										$active_language = $language['native_name'];
									elseif (isset($sitepress_settings['icl_lso_display_lang']) && $sitepress_settings['icl_lso_display_lang'] == 1):
										$active_language = $language['translated_name'];
									endif;

									break;
								endif;
							endforeach; ?>
						
							<!-- Languages -->
							<li>
								<a href="#" class="mn-has-sub"><?php echo esc_html($active_language); ?> <i class="fa fa-angle-down"></i></a>

								<ul class="mn-sub">
									<?php
									foreach ($languages as $language): 
										$flag = '';
										if (isset($sitepress_settings['icl_lso_flags']) && $sitepress_settings['icl_lso_flags'] == 1):
											$flag = '<img src="'.esc_url($language['country_flag_url']).'" /> ';
										endif;

										$language_name = '';
										if (isset($sitepress_settings['icl_lso_native_lang']) && $sitepress_settings['icl_lso_native_lang'] == 1):
											$language_name = $language['native_name'];
										endif;

										if (isset($sitepress_settings['icl_lso_display_lang']) && $sitepress_settings['icl_lso_display_lang'] == 1):
											if (!empty($language_name)):
												$language_name .= ' ('.$language['translated_name'].')';
											else:
												$language_name = $language['translated_name'];
											endif;
										endif;

										?>
										<li <?php echo ($language['active'] == 1 ? 'class="active"' : ''); ?>><a href="<?php echo ($language['url'] == 'str' ? '#' : esc_url($language['url']) ); ?>" title="<?php echo esc_attr($language['native_name']); ?>"><?php echo $flag; ?><?php echo esc_html($language_name); ?></a></li>
									<?php endforeach; ?>
								</ul>
							</li>
							<!-- End Languages -->
						
						<?php endif; ?>
						
					<?php endif; ?>
						
					<?php if (ts_get_opt('header-enable-social-icons')): ?>
						<!-- Social Links -->
						<li>
							<?php rhythm_social_links('%s',ts_get_opt('header-social-icons-category'), 3); ?>
						</li>
						<!-- End Social Links -->
					<?php endif; ?>
						
					<?php if (ts_get_opt('header-enable-button')): 
						if (ts_get_opt('header-button-style') == 'filled'):
							$button_style = 'btn-gray';
						elseif (ts_get_opt('header-button-style') == 'filled_dark'):
							$button_style = ''; //no style needed
						else:
							$button_style = 'btn-border-w';
						endif;
						
						$header_button_target = ts_get_opt('header-button-target') ? ts_get_opt('header-button-target') : '_blank';
						?>
						<li>
							<a class="header-button" href="<?php echo esc_url(ts_get_opt('header-button-link')); ?>" target="<?php echo esc_attr($header_button_target); ?>"><span class="btn btn-mod btn-circle <?php echo sanitize_html_classes($button_style); ?>"><?php echo (ts_get_opt('header-button-icon') ? '<i class="'.sanitize_html_classes(ts_get_opt('header-button-icon')).'"></i> ' : ''); ?><?php echo esc_html(ts_get_opt('header-button-text')); ?></span></a>
							<?php ?>
						</li>
					<?php endif; ?>
				</ul>
				
			</div>
			<!-- End Main Menu -->
		</div>
	</nav>
	<!-- End Navigation panel -->
<?php endif; ?>