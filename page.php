<?php
	$total_pages = wp_count_posts('page')->publish;
	$pages = get_pages('sort_column=menu_order&sort_order=asc');
	$ids = array();
	if($total_pages < 30)
	{
	    // Get progress based on word count
	    $word_count_index =array();
	    foreach($pages as $page) {
	        $ids[] .= $page->ID;
	        // Get word count
	        $content = get_post_field( 'post_content', $page->ID );
	        $word_count = str_word_count( strip_tags( $content ) );
	        $word_count_index[] = $word_count;
	    }
	    $page_index = array_search(get_the_ID(),$ids) + 1;      
	    $total_words = array_sum($word_count_index);
	    $words_used_so_far = array_sum(array_slice($word_count_index,0,$page_index)); 
	    $progress = round(($words_used_so_far / $total_words) * 100);
	
	    // stop reaching 100% before the last page - in case last page is less than 1% of total length. 
	    if($progress == 100 && $page_index != $total_pages)
	    {
	        $progress = 99;
	    }
	}
	else
	{
	    // Get progress based on page count
	    foreach($pages as $page) {
	        $ids[] .= $page->ID;
	    }
	    $page_index = array_search(get_the_ID(),$ids) + 1;
	    $progress = round(($page_index / $total_pages) * 100);      
	}
	get_header();
?>

<section id="title">
	<div class="container">
	
		<?php 
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post(); 
					//if($total_pages > 1) {
						echo '<h1>'.get_the_title().'</h1>';
					//}
				}
			}
		?>
		
	</div>
</section>

<section id="content">
	<div class="container">
	
		<div class="row">
			<div class="col-md-12">
			
				<?php 
				
					if ( have_posts() ) {
						while ( have_posts() ) {
							the_post(); 
						
							if ( has_post_thumbnail($page->ID) ) {	
								$image = wp_get_attachment_image_src( get_post_thumbnail_id( $page->ID ), 'single-post-thumbnail' )[0];
								echo '<img src="'.$image.'" class="img-responsive">';
							} else {
								the_content();
							}
						
						
						}
					}
				?>
				
			</div>
		</div>
	</div>
</section>

<?php if($total_pages > 1) { ?>

	<section id="pagination" class="hidden-print">
		<div class="container">
			<div class="row">

					<?php
						$pagelist = get_pages('sort_column=menu_order&sort_order=asc');
						$pages = array();
						$count = 1;
						foreach ($pagelist as $page) {
						   $pages[$count] += $page->ID;
						   $count++;
						}
						
						$current = array_search(get_the_ID(), $pages);
						$prevID = $pages[$current-1];
						$nextID = $pages[$current+1];
						
						echo '<div class="col-md-5 col-sm-6 col-xs-4 text-left">';
						if (!empty($prevID)) {
							echo '<a accesskey="P" data-toggle="tooltip" data-placement="top" title="'.get_the_title($prevID).'" class="hidden-xs prev-page btn btn-primary btn-lg btn-block" href="'.get_permalink($prevID).'"><i class="fa fa-long-arrow-left"></i></a>';
							echo '<a accesskey="P" class="visible-xs prev-page btn btn-primary btn-lg btn-block" href="'.get_permalink($prevID).'"><i class="fa fa-long-arrow-left"></i></a>';
						}
						echo '</div>';

						echo '<div class="percentage col-md-2 hidden-xs text-center">';
						echo '<span data-toggle="tooltip" data-placement="top" title="Page ' . $current .' of ' . count($pagelist) . '">' . (is_front_page() ? 0 : $progress ) . '%</span>';
						echo '</div>';

						echo '<div class="visible-xs col-xs-4 text-right">';						
						if (!empty($nextID)) {
							echo '<a class="btn btn-primary btn-lg btn-block" href="#top"><i class="fa fa-long-arrow-up"></i></a>';
						}
						echo '</div>';

						echo '<div class="col-md-5 col-sm-6 col-xs-4 text-right">';						
						if (!empty($nextID)) {
							echo '<a accesskey="N" data-toggle="tooltip" data-placement="top" title="'.get_the_title($nextID).'" class="hidden-xs next-page btn btn-primary btn-lg btn-block" href="'.get_permalink($nextID).'"><i class="fa fa-long-arrow-right"></i></a>';
							echo '<a accesskey="N" class="visible-xs next-page btn btn-primary btn-lg btn-block" href="'.get_permalink($nextID).'"><i class="fa fa-long-arrow-right"></i></a>';
						}
						echo '</div>';

					?>

			</div>			
		</div>
	</section>

<?php } ?>

<?php get_footer(); ?>