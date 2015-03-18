<?php authenticate(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo get_bloginfo('name'); ?></title>
    <link rel="stylesheet" href="<?php echo get_bloginfo('stylesheet_url') ;?>" type="text/css" media="all" />
    <link rel="stylesheet" href="<?php echo get_bloginfo('template_url') ;?>/assets/styles/bootstrap.css" type="text/css" media="all" />
    <link rel="stylesheet" href="<?php echo get_bloginfo('template_url') ;?>/assets/styles/edocs.css" type="text/css" media="all" />
    <link rel="stylesheet" href="<?php echo get_bloginfo('template_url') ;?>/assets/styles/font-awesome.css" type="text/css" media="all" />
    <link id="contrast" rel="stylesheet" href="<?php echo get_bloginfo('template_url') ;?>/assets/styles/contrast/normal.css" type="text/css" media="all" />
	<?php if(get_option('edocs_options')['favicon']) { ?>
    	<link rel="icon" type="image/png" href="<?php echo get_option('edocs_options')['favicon'];?>">
	<?php } ?>
	<style>
		section#header {
			background-color: <?php echo (get_option('edocs_options')['title_bar_bg_colour'] ? get_option('edocs_options')['title_bar_bg_colour'] : '#444444' ); ?>;
			color: <?php echo (get_option('edocs_options')['title_bar_text_colour'] ? get_option('edocs_options')['title_bar_text_colour'] : '#FFFFFF' ); ?>;
		}
		.btn-primary {
			background-color: <?php echo (get_option('edocs_options')['title_bar_bg_colour'] ? get_option('edocs_options')['title_bar_bg_colour'] : '#444444' ); ?>;
			color: <?php echo (get_option('edocs_options')['title_bar_text_colour'] ? get_option('edocs_options')['title_bar_text_colour'] : '#FFFFFF' ); ?>;
			border: none;
		}
		<?php
			if( get_option('edocs_options')['custom_css'] ) {
				echo get_option('edocs_options')['custom_css'];
			}
		?>	
	</style>
	<?php wp_head(); ?>
</head>
<body id="top" <?php body_class( $class ); ?>>

<a accesskey="S" href="#content" class="sr-only sr-only-focusable">Skip to main content</a>

<section id="nav" class="hidden-print">

		<nav class="navbar navbar-default" role="navigation">
			<div class="container">

			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				</button>
				<a accesskey="1" class="navbar-brand visible-xs" href="<?php echo get_bloginfo('url');?>"><?php echo get_bloginfo('name'); ?></a>
			</div>
			
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<?php $total_pages = wp_count_posts('page')->publish; if($total_pages > 1) { ?>
						
						<li><a href="<?php echo get_bloginfo('url'); ?>"><i class="fa fa-home"></i> Home</a></li>
						
						<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-list"></i> Contents <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a accesskey="2" href="<?php echo get_bloginfo('url'); ?>/pages/contents">Table of contents</a></li>
								<li role="presentation" class="divider"></li>
								<?php 
									$pages = get_pages( array( 'parent' => 0, 'sort_column' => 'menu_order' ) ); 
									foreach ( $pages as $page ) {
										echo '<li><a href="' . get_page_link( $page->ID ) . '">'.get_the_title($page->ID).'</a></li>';
									}
								?>
							</ul>
						</li>
					<?php } ?>

					<?php
						$pdf_dir = dirname(dirname(dirname(__DIR__))).'/download/';
						$pdf_file = str_replace('/','_',str_replace(' ','_',trim(strtolower(get_bloginfo('name'))))).'.pdf';
						$pdf = $pdf_dir.$pdf_file;
						if(file_exists($pdf)) { ?>
						<li><a href="<?php echo get_bloginfo('url').'/download/'.$pdf_file;?>"><i class="fa fa-cloud-download"></i> Download</a></li>
					<?php } ?>
				</ul>
				
				<ul class="nav navbar-nav navbar-right">
					<?php if( get_option('edocs_options')['sharing'] ) { ?>
					<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-share-alt"></i> Share <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
					<li><a class="email" href="mailto:?subject=<?php echo str_replace(' ','%20',get_bloginfo('name')); ?>&amp;body=<?php echo str_replace(' ','%20',get_bloginfo('url')); ?>">Email</a></li>
					<li><a class="twitter" href="http://twitter.com/home?status=Reading:<?php echo get_bloginfo('url'); ?>" target="_blank">Twitter</a></li>
					<li><a class="facebook" href="http://www.facebook.com/sharer.php?u=<?php echo get_bloginfo('url');?>&amp;t=<?php echo $_GET['title']; ?>" onclick="window.open(this.href); return false;">Facebook</a></li>
					<li><a class="google-plus" target="_blank" href="https://plus.google.com/share?url=<?php echo get_bloginfo('url'); ?>">Google+</a></li>
					<li><a class="instapaper" target="_blank" href="http://www.instapaper.com/hello2?url=<?php echo get_bloginfo('url'); ?>&title=<?php bloginfo('name'); ?> &description=<?php bloginfo('description'); ?> ">Instapaper</a></li>
					</ul></li>
					<?php } ?>
					<?php if( get_option('edocs_options')['help'] ) { ?>
					<li><a accesskey="0" href="<?php echo get_bloginfo('url'); ?>/pages/help"><i class="fa fa-question-circle"></i> Help</a></li>
					<?php } ?>
				</ul>
				
				</div>
			</div>
		</nav>
			
</section>

<?php if(get_option('edocs_options')['masthead']) { ?>
	<section id="masthead" class="bg-image hidden-print text-center">
		<img src="<?php echo get_option('edocs_options')['masthead']; ?>" class="img-responsive" alt="<?php get_bloginfo('name'); ?>">
	</section>
<?php } ?>

<?php if(get_bloginfo('name')) { ?>
<section id="header" class="hidden-xs hidden-print">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="title">
					<?php echo (get_bloginfo('name') ? '<h1>'.get_bloginfo('name').'</h1>' : false ); ?>
					<?php echo (get_bloginfo('description') ? '<h2>'.get_bloginfo('description').'</h2>' : false ); ?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php } ?>