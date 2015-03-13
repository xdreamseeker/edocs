<?php
	wp_redirect(get_option('edocs_options')['redirect'], get_bloginfo('url'), 301);
	exit;