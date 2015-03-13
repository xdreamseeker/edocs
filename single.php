<?php get_header(); ?>

<section id="title">
	<div class="container">
		<?php 
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post(); 
					echo '<h1>'.get_the_title().'</h1>';
				}
			}
		?>
	</div>
</section>

<section id="content">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<?php the_content(); ?>				
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>