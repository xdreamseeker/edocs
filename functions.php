<?php



/*
 * MPDF Class
 */
 
include('assets/mpdf/mpdf.php');



/*
 * Authenticate
 */

function authenticate() {
	if(get_option('edocs_options')['publish'] == 2) {
		// Visibility = Publish
	} elseif(get_option('edocs_options')['publish'] == 1) {
		// Visibility = Password Protected
		if(is_user_logged_in()) {
			// Registered user - allow through
		} else {
			// HTTP Authentication
			$passwords = array(get_option('edocs_options')['guest_username'] => get_option('edocs_options')['guest_password']);
			$usernames = array_keys($passwords);
			$username = $_SERVER['PHP_AUTH_USER'];
			$password = $_SERVER['PHP_AUTH_PW'];
			$validated = (in_array($username, $usernames)) && ($password == $passwords[$username]);
			if(!$validated) {
			  // Authentication failed - try again
			  header('WWW-Authenticate: Basic realm="SGC"');
			  header('HTTP/1.0 401 Unauthorized');
			} else {
				// Authentication passed - allow through
			}
		}
	} else {
		// Visibility = Private
		if(is_user_logged_in()) {
			// Registered user - allow through
		} else {
			// Unregistered user - 404
			wp_redirect(get_option('edocs_options')['redirect'], get_bloginfo('url'), 301);
			exit;			
		}
	}
}



/*
 * Add support for WordPress Featured Images
 */

add_theme_support( 'post-thumbnails' ); 

	

/*
 * Set eDoc homepage to Sample Page
 */

if(is_user_logged_in()) {
	global $blog_id;	
	if(get_option('show_on_front') != 'page') {
		// Get Sample Page
		$default_page = get_page_by_title( 'Sample Page' );
		// Set homepage to Sample Page ID
		update_option( 'page_on_front', $default_page->ID );
		update_option( 'show_on_front', 'page' );
	}
}



/*
 * Redirect WordPress dashboard to Pages menu
 */

add_action('load-index.php', 'edocs_dashboard_redirect');
function edocs_dashboard_redirect(){
	wp_redirect(admin_url('edit.php?post_type=page'));
}



/*
 * Hide WordPress menu items
 */

add_action( 'admin_menu', 'edocs_remove_menus' );
function edocs_remove_menus(){
	remove_menu_page( 'index.php' );
	remove_menu_page( 'edit.php' );
	remove_menu_page( 'edit-comments.php' );
	remove_menu_page( 'themes.php' );
	remove_menu_page( 'plugins.php' );
	remove_menu_page( 'users.php' );
	remove_menu_page( 'tools.php' );
	remove_menu_page( 'options-general.php' );
	// Add Settings to menu
	add_menu_page(__('Settings', 'customize'), __('Settings', 'customize'), 'edit_pages', 'customize.php', '', '', 100);
	// Get Help page
	$help_page = get_page_by_title( 'Help', '', 'eDocs' )->ID;
	// Add Help to menu
	add_menu_page(__('Help', 'help'), __('Help', 'help'), 'edit_pages', 'post.php?post='.$help_page.'&action=edit', '', 'dashicons-editor-help', 101);
	add_filter('custom_menu_order', 'custom_menu_order');
	add_filter('menu_order', 'custom_menu_order');	
}



/*
 * Reorganise WordPress menu items
 */


function custom_menu_order($menu_ord) {  
	if (!$menu_ord) return true;  
	// Get Help page
	$help_page = get_page_by_title( 'Help', '', 'eDocs' )->ID;
	return array(  
		'edit.php?post_type=page',
		'upload.php',
		'customize.php',
		'post.php?post='.$help_page.'&action=edit',
	);
}




/*
 * Default help text
 */
 
// Get Help page
$help_page = get_page_by_title( 'Help', '', 'eDocs' )->ID;
$help_content = get_post($help_page);

// Reset if help page is empty
//if($help_content->post_content == '') {

	$html = '';
	$html .= '<h3>Web standards</h3>';
	$html .= '<p>This site uses valid HTML5 and CSS3. Content is separated from presentational elements to maintain accessibility across assistive technology devices such as screen readers and text only browsers.</p>';
	$html .= '<p>You can validate the HTML by using the <a href="http://validator.w3.org/check?uri=' . get_bloginfo('url'). '">W3C Markup Validation Service</a>.';
	$html .= '<h3>Mobile</h3>';
	$html .= '<p>This site uses responsive design to modify page elements in realtime for the best viewing experience. Everything from images to tables is adjusted when the device you are using has a smaller screen (or "viewport").</p>';
	$html .= '<p>If you are using a touch screen device, you can quickly navigate between pages by swiping left or right.</p>';
	$html .= '<div class="row">';
	$html .= '<div class="col-md-6">';
	$html .= '<p style="font-weight:400;">Swipe left to go to the previous page</p>';
	$html .= '<div class="swipe_box text-center">';
	$html .= '<img src="' . get_bloginfo('template_url') . '/assets/images/swipe_left.png" alt="Swipe left">';
	$html .= '</div>';
	$html .= '</div>';
	$html .= '<div class="col-md-6">';
	$html .= '<p style="font-weight:400;">Swipe right to go to the next page</p>';
	$html .= '<div class="swipe_box text-center">';
	$html .= '<img src="' . get_bloginfo('template_url') . '/assets/images/swipe_right.png" alt="Swipe right">';
	$html .= '</div>';
	$html .= '</div>';
	$html .= '</div>';
	$html .= '<h3>Display settings</h3>';
	$html .= '<p>You can adjust the colour contrast, font size and line spacing below. The <a href="http://www.bbc.co.uk/accessibility/" target="_blank">BBC Accessibility</a> website provides advice on how to set up your computer and web browser to improve your experience on the web.</p>';
	$html .= '<div class="table-responsive">';
	$html .= '<table style="table-layout:fixed;" class="table table-bordered">';
	$html .= '<tr>';
	$html .= '<th><strong>Contrast</strong></th>';
	$html .= '<td class="contrast low"><a href="#" rel="'.get_bloginfo('template_url').'/assets/styles/contrast/low.css">Low</a></td>';
	$html .= '<td class="contrast normal"><a href="#" rel="'.get_bloginfo('template_url').'/assets/styles/contrast/normal.css">Normal</a></td>';
	$html .= '<td class="contrast high"><a href="#" rel="'.get_bloginfo('template_url').'/assets/styles/contrast/high.css">High</a></td>';
	$html .= '<td class="contrast invert"><a href="#" rel="'.get_bloginfo('template_url').'/assets/styles/contrast/invert.css">Invert</a></td>';
	$html .= '</tr>';
	$html .= '<tr>';
	$html .= '<th><strong>Font</strong></th>';
	$html .= '<td class="font small"><a class="font_small" href="#">Small</a></td>';
	$html .= '<td class="font medium"><a class="font_medium" href="#">Medium</a></td>';
	$html .= '<td class="font large"><a class="font_large" href="#">Large</a></td>';
	$html .= '<td class="font xlarge"><a class="font_xlarge" href="#">Extra large</a></td>';
	$html .= '</tr>';
	$html .= '<tr>';
	$html .= '<th><strong>Spacing</strong></th>';
	$html .= '<td class="spacing small"><a class="spacing_small" href="#">Small</a></td>';
	$html .= '<td class="spacing medium"><a class="spacing_medium" href="#">Medium</a></td>';
	$html .= '<td class="spacing large"><a class="spacing_large" href="#">Large</a></td>';
	$html .= '<td class="spacing xlarge"><a class="spacing_xlarge" href="#">Extra large</a></td>';
	$html .= '</tr>';
	$html .= '</table>';
	$html .= '</div>';
	$html .= '<p>Your preferences are stored as cookies on your computer so they are remembered the next time you visit.</p>';
	$html .= '<h3>Access keys</h3>';
	$html .= '<p>Accesskeys (also known as "accelerator keys", "shortcut keys" or "access keys") can be used in most browsers, and work as shortcuts to enable people to navigate a site using a keyboard. Every web browser treats these differently.</p>';
	$html .= '<p>eDocs use the following access keys:</p>';
	$html .= '<ul>';
	$html .= '<li><kbd>S</kbd> &mdash; Skip to main content</li>';
	if( get_option('edocs_options')['help'] ) {
		$html .= '<li><kbd>0</kbd> &mdash; <a href="'.get_bloginfo('url').'/pages/help">Help</a></li>';
	}
	$html .= '<li><kbd>1</kbd> &mdash; <a href="'.get_bloginfo('url').'">Homepage</a></li>';
	$html .= '<li><kbd>2</kbd> &mdash; <a href="'.get_bloginfo('url').'/pages/contents">Contents</a></li>';
	$html .= '<li><kbd>N</kbd> &mdash; Next page</li>';
	$html .= '<li><kbd>P</kbd> &mdash; Previous page</li>';
	$html .= '</ul>';
	$html .= '<p>Find out how to use <a href="http://en.wikipedia.org/wiki/Access_key#Access_in_different_browsers" target="_blank">access keys in different browsers</a>.</p>';
	
	$default_help = array(
		'ID' => $help_page,
		'post_content' => $html
	);
	
	wp_update_post( $default_help );
	
//}				



/*
 * Default contents text
 */

// Get Contents page
$contents_page = get_page_by_title( 'Contents', '', 'eDocs' )->ID;

$html = '';
$html .= '<ul>';
$html .= wp_list_pages('sort_column=menu_order&title_li=&echo=0');
$html .= '</ul>';

$default_contents = array(
	'ID' => $contents_page,
	'post_content' => $html
);

wp_update_post( $default_contents );



/*
 * Initialise
 */
 
add_action( 'init', 'edocs_init' );
function edocs_init() {
	
	global $wpdb;
	$table = $wpdb->prefix . 'posts';
	
	// Register new eDocs Custom Post Type
	register_post_type( 'edocs',
		array(
			'labels' => array(
				'name' => __( 'eDocs' ),
				'singular_name' => __( 'eDocs' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'pages'),
			'show_ui' => false,
		)
	);
	
	// Create/Check for Contents page
	$check = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE post_type = 'edocs' AND post_title = 'Contents'");
	if($check == 0) {
		$my_post = array(
		  'post_title'    => 'Contents',
		  'post_name'    => 'contents',
		  'post_status'   => 'publish',
		  'post_author'   => 1,
		  'post_type' => 'edocs'
		);
		wp_insert_post( $my_post );		
	}

	// Create/Check for Help page
	$check = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE post_type = 'edocs' AND post_title = 'Help'");
	if($check == 0) {
		$my_post = array(
		  'post_title'    => 'Help',
		  'post_status'   => 'publish',
		  'post_author'   => 1,
		  'post_type' => 'edocs'
		);
		wp_insert_post( $my_post );		
	}

}



/*
 * Metaboxes
 */

add_action( 'add_meta_boxes', 'edocs_meta_boxes' );

// Register eDocs metaboxes
function edocs_meta_boxes( $post ) {
	add_meta_box('page_orientation','Page Orientation','edocs_meta_boxes_orientation','page','side');
	add_meta_box('page_exclude','Exclude from PDF','edocs_meta_boxes_exclude','page','side');
}

// Page orientation metabox
function edocs_meta_boxes_orientation( $post ) {
	$value = get_post_meta( $post->ID, '_page_orientation', true );
	echo '<select name="page_orientation">';
	echo '<option ' . ( $value == 'portrait' ? 'selected="selected' : false ) . '" value="portrait">Portrait</option>';
	echo '<option ' . ( $value == 'landscape' ? 'selected="selected' : false ) . '" value="landscape">Landscape</option>';
	echo '</select>';
}

// Page exclusion metabox
function edocs_meta_boxes_exclude( $post ) {
	$value = get_post_meta( $post->ID, '_page_exclude', true );
	echo '<select name="page_exclude">';
	echo '<option ' . ( $value == '0' ? 'selected="selected' : false ) . '" value="0">No</option>';
	echo '<option ' . ( $value == '1' ? 'selected="selected' : false ) . '" value="1">Yes</option>';
	echo '</select>';
}

// Save metabox values
add_action( 'save_post', 'edocs_save_meta_boxes' );

function edocs_save_meta_boxes( $post_id ) {
	// Cancel if autosaving
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	// Cancel if values are empty
	if ( !isset( $_POST['page_orientation'] ) ) return;
	if ( !isset( $_POST['page_exclude'] ) ) return;
	// Get values
	$orientation = $_POST['page_orientation'];
	$exclude = $_POST['page_exclude'];
	// Update database
	update_post_meta( $post_id, '_page_orientation', $orientation );
	update_post_meta( $post_id, '_page_exclude', $exclude );
}



/*
 * Scripts and styles
 */

add_action( 'admin_enqueue_scripts', 'sgc_admin_scripts_and_styles' );
// Admin scripts and styles
function sgc_admin_scripts_and_styles() {
	//wp_enqueue_style( 'admin', get_template_directory_uri() . '/assets/styles/admin.css');
}

add_action( 'wp_enqueue_scripts', 'sgc_scripts_and_styles' );
// Other scripts and styles
function sgc_scripts_and_styles() {
	if(!is_admin()){
		wp_deregister_script('jquery');
		wp_register_script('jquery', '//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js', false, '', false);
		wp_enqueue_script('jquery');
	}
	// Styles
	wp_enqueue_style( 'admin', get_template_directory_uri() . '/assets/styles/admin.css');
	// Scripts	
	wp_enqueue_script( 'cookie', get_template_directory_uri() . '/assets/scripts/cookie.js', array(), '', true );
	wp_enqueue_script( 'edocs', get_template_directory_uri() . '/assets/scripts/edocs.js', array('jquery'), '', true );
	wp_enqueue_script( 'bootstrap', '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.0/js/bootstrap.min.js', array('jquery'), '', true );
	wp_enqueue_script( 'html5shiv', '//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.min.js', array(), '', true );
	wp_enqueue_script( 'modernizr', '//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js', array(), '', true );
	wp_enqueue_script( 'touchswipe', '//cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.min.js', array(), '', true );
}



/*
 * Schedule build
 */

if(is_admin() && isset($_GET['build']) && $_GET['build'] == 1) {
	// User requested new build
	schedule_build();
} 

add_action( 'save_post', 'schedule_build', 20 );
function schedule_build() {
	// Reset last updated values
	update_option( 'last_updated_date', 'Building...');
	update_option( 'last_updated_time', 'Building...');
	// Schedule build
	wp_schedule_single_event( time(), 'start_build' );
}

// Initiate build
add_action( 'start_build','build_edoc' );




/*
 * Build eDoc
 */

function build_edoc( $paper_size = 'A4', $font = 'sans', $font_size = 12, $cover = 1, $toc = 1 ) {

	// New class
	set_time_limit(3600);
	ini_set('max_execution_time', 3600);
	$mpdf=new mPDF('', $paper_size, $font_size, $font);
	
	// Log start time
	$start_time = time();
	error_log( 'Starting Build: "' . get_bloginfo('name') . '" at ' . $start_time );
	
	// Cover page
	if( get_option('edocs_options')['cover'] ) {
		$mpdf->SetHTMLFooter('');
		$mpdf->AddPage();
		$image = get_option('edocs_options')['cover'];
		$mpdf->Image($image,0,0,210,297,'jpg','',true, false);
		error_log('Page Complete: "Cover Page"' );
	}

	// Table of contents
	if( get_option('edocs_options')['toc'] ) {
		$mpdf->SetHTMLFooter('');
		$mpdf->TOCpagebreakByArray(array( 
			'toc_preHTML' => '<h1>Contents</h1>', 
		));
	    error_log('Page Complete: "Table of Contents"' );
	}

	// Headers and footers
	$mpdf->SetHTMLFooter('
	<table width="100%" style="border-top: 1px solid #000; vertical-align: bottom; font-family: sans; font-size: 12px; color: #000000;">
		<tr>
			<td width="50%" style="text-align: left; ">{DATE j/m/Y}</td>
			<td width="50%" style="text-align: right; ">{PAGENO}/{nbpg}</td>
		</tr>
	</table>
	');

	// Fetch pages
	$args = array(
		'post_type' => 'page',
		'sort_column' => 'menu_order',
		'sort_order' => 'asc'
	);
	
	// Loop through pages
	$pages = get_pages( $args );
	$count = 0;
	foreach ( $pages as $page ) : setup_postdata( $page );

		// Reset page text
		$html = '';

		// Establish page depth
		$parent = $page->post_parent;
		$depth = 0;
		if($parent) {
			while ($parent > 0) {
				$the_page = get_page($parent);
				$parent = $the_page->post_parent;
				$depth++;
			}
		}
		
		// Establish page orientation
		$orientation = get_post_meta( $page->ID, '_page_orientation', true );
		$orientation = ($orientation == 'landscape' ? 'L' : 'P');
		
		// Build the page
		if( get_post_meta( $page->ID, '_page_exclude', true ) != 1 ) {
			$mpdf->Bookmark(get_the_title($page),$depth);
			
			if( get_option('edocs_options')['toc'] ) {
				$mpdf->TOC_Entry(get_the_title($page),$depth);
			}
			
			$mpdf->AddPage($orientation);
			
			if ( has_post_thumbnail($page->ID) ) {
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $page->ID ), 'single-post-thumbnail' )[0];
			    if($orientation == 'P') {
			    	$mpdf->Image($image,0,0,210,297,'jpg','',true, false);
			    } else {
			    	$mpdf->Image($image,0,0,297,210,'jpg','',true, false);
			    }
			} else {
				$html = '<h1>'.get_the_title($page).'</h1>' . wpautop(get_the_content($page));
			}
			
			// Remove excess white space from HTML
			$html = preg_replace('/\s+/', '', $html);
				
			$mpdf->WriteHTML($html);
			// Log page build complete
			error_log('Page Complete: "' . get_the_title($page) . '"' );
			
			// Free memory
			unset($html,$image);
		}
		
		$count++;
		
	endforeach; 
	wp_reset_postdata();

	// Log end time
	$end_time = time();
	error_log( 'Build Complete: "' . get_bloginfo('name') . '" at ' . $end_time );
	// Calculate and log build time
	$build_time = abs($end_time - $start_time) % 60;
	error_log( 'Build Time: ' . $build_time . ' seconds' );
	// Update last updated values
	update_option( 'last_updated_date', $date = date('d/m/Y', time()));
	update_option( 'last_updated_time', $date = date('G:i:s', time()));
	// Check download directory exists
	if(file_exists(dirname(dirname(dirname(__DIR__))).'/download/')) {
		error_log( 'Download Directory: Exists!' );
	} else {
		error_log( 'Download Directory: Created!' );
		mkdir(dirname(dirname(dirname(__DIR__))).'/download/');	
	}
	// Output PDF	
	$mpdf->Output(dirname(dirname(dirname(__DIR__))).'/download/'.str_replace('/','_',str_replace(' ','_',trim(strtolower(get_bloginfo('name'))))).'.pdf', 'F');

	
}



/*
 * Settings/Cutomizer
 */

add_action('customize_register', 'customise_theme');
function customise_theme($wp_customize){

	// Textarea class
	class textarea extends WP_Customize_Control {
	    public $type = 'textarea';
	 
	    public function render_content() {
	        ?>
	        <label>
	        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
	        <textarea rows="10" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
	        </label>
	        <?php
	    }
	}
	
	// Remove Static Front Page section
	$wp_customize->remove_section('static_front_page');



	/*
	 * General section
	 */

	$wp_customize->add_section( 'title_tagline' , array(
		'title'		=> 'General',
		'priority'	=> 1,
	));

	// Homepage
	$wp_customize->add_setting('edocs_options[homepage]', array(
	    'default'        => get_option('page_on_front'),
	    'type'           => 'option',
	));	
	$wp_customize->add_control( 'edocs_options[homepage]', array(
	    'settings' => 'edocs_options[homepage]',
	    'label'   => 'Homepage',
	    'section' => 'title_tagline',
	    'type'    => 'dropdown-pages',
	));	



	/*
	 * Visibility section
	 */
	
	$wp_customize->add_section( 'visibility' , array(
		'title'		=> 'Visibility',
		'priority'	=> 2,
	));

	// Publish
	$wp_customize->add_setting('edocs_options[publish]', array(
	    'default'        => '0',
	    'type'           => 'option',
	));
	$wp_customize->add_control( 'edocs_options[publish]', array(
	    'settings' => 'edocs_options[publish]',
	    'label'   => 'Status',
	    'priority'	=> 1,
	    'section' => 'visibility',
	    'type'    => 'select',
	    'choices'    => array(
	        0 => 'Private',
	        1 => 'Password Protected',
	        2 => 'Published'
	    ),
	));
	
	if( get_option('edocs_options')['publish'] == 1 ) {
		
		// Visiblity = Password Protected
		
		// Guest username
		$wp_customize->add_setting( 'edocs_options[guest_username]', array(
		    'default'	=> 'guest',
		    'type'		=> 'option',
		));
		$wp_customize->add_control( 'edocs_options[guest_username]', array(
		    'label'     => 'Username',
		    'section'   => 'visibility',
		    'settings'  => 'edocs_options[guest_username]',
			'priority'	=> 2,
		));
		
		// Check for guest password
		if( empty( get_option('edocs_options')['guest_password'] ) ) {
			// Not found - generate new one
			$password = substr(md5(uniqid(mt_rand(), true)), 0, 8);
		} else {
			// Found - fetch existing
			$password = get_option('edocs_options')['guest_password'];
		}

		// Guest password		
		$wp_customize->add_setting( 'edocs_options[guest_password]', array(
		    'default'	=> $password,
		    'type'		=> 'option',
		));
		$wp_customize->add_control( 'edocs_options[guest_password]', array(
		    'label'     => 'Password',
		    'section'   => 'visibility',
		    'settings'  => 'edocs_options[guest_password]',
			'priority'	=> 2,
		));

	}



	/*
	 * Web section
	 */

	$wp_customize->add_section(
		'web',
		array(
			'title' => 'Web',
			'priority'	=> 2,
		)
	);

	// 404 redirect
	$wp_customize->add_setting( 'edocs_options[redirect]', array(
	    'default'	=> get_bloginfo('url'),
	    'type'		=> 'option',
	));
	$wp_customize->add_control( 'edocs_options[redirect]', array(
	    'label'     => '404 Redirect',
	    'section'   => 'web',
	    'settings'  => 'edocs_options[redirect]',
	));	

	// Masthead image
	$wp_customize->add_setting('edocs_options[masthead]', array(
	    'default' => false,
	    'type' => 'option',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'edocs_options[masthead]', array(
	    'label'    => 'Header Image',
	    'section'  => 'web',
	    'settings' => 'edocs_options[masthead]',
	)));

	// Sharing
	$wp_customize->add_setting('edocs_options[sharing]', array(
	    'default'        => '1',
	    'type'           => 'option',
	));
	$wp_customize->add_control( 'edocs_options[sharing]', array(
	    'settings' => 'edocs_options[sharing]',
	    'label'   => 'Sharing',
	    'section' => 'web',
	    'type'    => 'select',
	    'choices'    => array(
	        '0' => 'Hide',
	        '1' => 'Show'
	    ),
	));

	// Help
	$wp_customize->add_setting('edocs_options[help]', array(
	    'default'        => '1',
	    'type'           => 'option',
	));
	$wp_customize->add_control( 'edocs_options[help]', array(
	    'settings' => 'edocs_options[help]',
	    'label'   => 'Help',
	    'section' => 'web',
	    'type'    => 'select',
	    'choices'    => array(
	        '0' => 'Hide',
	        '1' => 'Show'
	    ),
	));
	
	// Title bar background colour
	$wp_customize->add_setting('edocs_options[title_bar_bg_colour]', array(
	    'default'           => '#444444',
	    'sanitize_callback' => 'sanitize_hex_color',
	    'type'           => 'option',
	 
	));
	$wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'edocs_options[title_bar_bg_colour]', array(
	    'label'    => 'Title Bar Background Colour',
	    'section'  => 'web',
	    'settings' => 'edocs_options[title_bar_bg_colour]',
	)));
	
	// Title bar text colour
	$wp_customize->add_setting('edocs_options[title_bar_text_colour]', array(
	    'default'           => '#FFFFFF',
	    'sanitize_callback' => 'sanitize_hex_color',
	    'type'           => 'option',
	));
	$wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'edocs_options[title_bar_text_colour]', array(
	    'label'    => 'Title Bar Text Colour',
	    'section'  => 'web',
	    'settings' => 'edocs_options[title_bar_text_colour]',
	)));

	// Footer text
	$wp_customize->add_setting( 'edocs_options[footer_text]', array(
	    'default'	=> get_bloginfo('name'),
	    'type'		=> 'option',
	));
	$wp_customize->add_control( 'edocs_options[footer_text]', array(
	    'label'     => 'Footer Text',
	    'section'   => 'web',
	    'settings'  => 'edocs_options[footer_text]',
	));	

	// Footer year
	$wp_customize->add_setting('edocs_options[footer_year]', array(
	    'default'        => '1',
	    'type'           => 'option',
	));
	$wp_customize->add_control( 'edocs_options[footer_year]', array(
	    'settings' => 'edocs_options[footer_year]',
	    'label'   => 'Footer Year',
	    'section' => 'web',
	    'type'    => 'select',
	    'choices'    => array(
	        '0' => 'Hide',
	        '1' => 'Show'
	    ),
	));

	// Custom CSS
	$wp_customize->add_setting( 'edocs_options[custom_css]', array(
	    'type' => 'option'
	)); 
	$wp_customize->add_control( new textarea( $wp_customize, 'edocs_options[custom_css]', array(
	    'label'   => 'Custom CSS',
	    'section' => 'web',
	    'settings'   => 'edocs_options[custom_css]',
	)));
		


	/*
	 * PDF section
	 */

	$wp_customize->add_section(
		'pdf',
		array(
			'title' => 'PDF',
			'priority'	=> 4,
		)
	);
	
	// Cover page/image
	$wp_customize->add_setting('edocs_options[cover]', array(
	    'default' => false,
	    'type' => 'option',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'edocs_options[cover]', array(
	    'label'    => 'Cover Image',
	    'section'  => 'pdf',
	    'settings' => 'edocs_options[cover]',
	)));
	
	// Table of contents
	$wp_customize->add_setting('edocs_options[toc]', array(
	    'default'        => '0',
	    'type'           => 'option',
	 
	));	
	$wp_customize->add_control( 'edocs_options[toc]', array(
	    'settings' => 'edocs_options[toc]',
	    'label'   => 'Table of Contents',
	    'section' => 'pdf',
	    'type'    => 'select',
	    'choices'    => array(
	        '0' => 'No',
	        '1' => 'Yes'
	    ),
	));	



}



/*
 * Customise WordPress admin bar
 */
 
add_action( 'wp_before_admin_bar_render', 'sgc_remove_admin_bar_links' );

function sgc_remove_admin_bar_links() {
   
    global $wp_admin_bar;
    // Remove items
    $wp_admin_bar->remove_menu('site-name'); // Remove the about WordPress link
    $wp_admin_bar->remove_menu('dashboard'); // Remove the about WordPress link
    $wp_admin_bar->remove_menu('themes'); // Remove the about WordPress link
    $wp_admin_bar->remove_menu('customize'); // Remove the about WordPress link
    $wp_admin_bar->remove_menu('view-site'); // Remove the about WordPress link
    $wp_admin_bar->remove_menu('new-content'); // Remove the about WordPress link
    $wp_admin_bar->remove_menu('documentation'); // Remove the WordPress documentation link
    $wp_admin_bar->remove_menu('support-forums'); // Remove the support forums link
    $wp_admin_bar->remove_menu('feedback'); // Remove the feedback link
	$wp_admin_bar->remove_menu('wp-logo'); // Remove the WordPress logo
	$wp_admin_bar->remove_menu('about'); // Remove the about WordPress link
	$wp_admin_bar->remove_menu('wporg'); // Remove the WordPress.org link
	$wp_admin_bar->remove_menu('comments'); // Remove the comments link
	$wp_admin_bar->remove_menu('updates'); // Remove the updates link
	$wp_admin_bar->remove_menu('wpseo-menu'); // Remove SEO link
	$wp_admin_bar->remove_menu('my-account');
	$wp_admin_bar->remove_menu('search');
	$wp_admin_bar->remove_menu('edit');
	$wp_admin_bar->remove_menu('view');
	
	// Manage
	if(is_admin()) {
		$wp_admin_bar->add_menu( array(
			'id'    => 'manage',
			'title' => get_bloginfo('name'),
			'href'  => get_bloginfo('url')
		));
	} else {
		$wp_admin_bar->add_menu( array(
			'id'    => 'manage',
			'title' => get_bloginfo('name'),
			'href'  => admin_url()
		));
	}

	// Pages
	$wp_admin_bar->add_menu( array(
		'id'    => 'pages',
		'title' => 'Pages',
		'href'  => admin_url()
	));	
	
	// Media
	$wp_admin_bar->add_menu( array(
		'id'    => 'media',
		'title' => 'Media',
		'href'  => admin_url().'upload.php'
	));	

	// Settings
	$wp_admin_bar->add_menu( array(
		'id'    => 'settings',
		'title' => 'Settings',
		'href'  => admin_url().'customize.php'
	));	

	// Help
	$help_page = get_page_by_title( 'Help', '', 'eDocs' )->ID;
	$wp_admin_bar->add_menu( array(
		'id'    => 'help',
		'title' => 'Help',
		'href'  => 'post.php?post='.$help_page.'&action=edit'
	));	

	// Logout
	$wp_admin_bar->add_menu( array(
		'id'    => 'logout',
		'title' => 'Logout',
		'parent'=> 'top-secondary',
		'href'  => wp_logout_url()
	));
	
	// Build
	$pdf_updated = get_option('last_updated_date');
	$wp_admin_bar->add_menu( array(
		'id'    => 'build',
		'title' => 'Build eDoc' . ($pdf_updated ? ' (' . $pdf_updated . ')' : false ),
		'parent'=> 'top-secondary',
		'href'  => admin_url().'?build=1'
	));

	// Preview PDF
	$pdf_dir = dirname(dirname(dirname(__DIR__))).'/download/';
	$pdf_file = str_replace('/','_',str_replace(' ','_',trim(strtolower(get_bloginfo('name'))))).'.pdf';
	$pdf = $pdf_dir.$pdf_file;
	if(file_exists($pdf)) {
	$wp_admin_bar->add_menu( array(
		'id'    => 'preview-pdf',
		'title' => 'Preview PDF',
		'parent'=> 'top-secondary',
		'href'  => dirname(get_bloginfo('url')).'/download/'.$pdf_file
	));
	}

	// Preview Web
	$wp_admin_bar->add_menu( array(
		'id'    => 'preview-web',
		'title' => 'Preview Web',
		'parent'=> 'top-secondary',
		'href'  => get_bloginfo('url')
	));

	// View Page
	$page = $_GET['post'];
	if(strstr($_SERVER['REQUEST_URI'], 'wp-admin/post.php')) {
		$wp_admin_bar->add_menu( array(
			'id'    => 'view-page',
			'title' => 'View Page',
			'parent'=> 'top-secondary',
			'href'  => get_page_link($page)
		));
	}

	// Edit Page
	if(is_page()) {
		$wp_admin_bar->add_menu( array(
			'id'    => 'edit-page',
			'title' => 'Edit Page',
			'parent'=> 'top-secondary',
			'href'  => get_edit_post_link()
		));
	}
    
}

?>