<!DOCTYPE html>
<html <?php language_attributes(); ?>><head>

<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<meta name="viewport" content="initial-scale=1.0" />
<meta name="HandheldFriendly" content="true"/>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> >

<?php
global $_wp_theme_features;
$exclusive_header_image = get_header_image(); ?>
 
<div id="wrapper">
  <div id="header-wrapper" >
	<header>
	     <?php if(! empty($exclusive_header_image)){  ?>
				<a class="custom-header-a" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img src="<?php echo header_image(); ?>" class="custom-header">	
				</a>
					<?php
				} ?>
	
	
			<div id="header">
				<div class="container">
					 <?php exclusive_logo_img(); ?>
					<div class="header-phone-block">
						<div id="site-description">
							<p id="site-description-p"><?php bloginfo( 'description' ); ?></p>
						</div>
						<div id="search-block">
						 <?php get_search_form(); ?>
						</div>
					</div>
					<nav id="top-nav">
					  <?php
					  $wdwt_show_home = true;
						if(has_nav_menu( 'primary-menu')){
							$wdwt_show_home = false;
						}
					    $wdwt_menu = wp_nav_menu(	array(
					    			'show_home' => $wdwt_show_home,
										'theme_location'  => 'primary-menu',
										'container'       => false,
										'container_class' => '',
										'container_id'    => '',
										'menu_class'      => 'top-nav-list',
										'menu_id'         => '',
										'echo'            => false,
										'fallback_cb'     => 'wp_page_menu',
										'before'          => '',
										'after'           => '',
										'link_before'     => '',
										'link_after'      => '',
										'items_wrap'      => '<ul id="top-nav-list" class=" %2$s">%3$s</ul>',
										'depth'           => 0,
										'walker'          => ''
									));
							echo $wdwt_menu;		?>
						
					</nav>
				</div>
			</div>
	</header>		
			