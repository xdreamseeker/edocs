<section id="footer" class="hidden-print hidden-xs">
	<div class="container">
		<div class="row">
			<div class="col-md-6 text-left">
				<ul class="list-inline" style="margin-bottom: 0;">
					<li><i class="fa fa-arrow-up"></i> <a href="#top">Top</a></li>
				</ul>
			</div>
			<div class="col-md-6 text-right">
				<ul class="list-inline" style="margin-bottom: 0;">
					<li>
					<?php
						if(get_option('edocs_options')['footer_text']) {
							echo get_option('edocs_options')['footer_text'];
						} else {
							echo '&copy; ' . get_bloginfo('name');
						}
						if(get_option('edocs_options')['footer_year']) {
							echo ' ' . date('Y');
						}
					?>
					</li>
				</ul>
			</div>
		</div>			
	</div>
</section>
<?php wp_footer(); ?>
</body>
</html>