<?php
/**
 * The main template file for display page.
 *
 * Template Name: Galériák listája
 * @package WordPress
*/


/**
*	Get Current page object
**/
$page = get_page($post->ID);

/**
*	Get current page id
**/
$current_page_id = '';

if(isset($page->ID))
{
    $current_page_id = $page->ID;
}

get_header(); 

//Get Page background style
$bg_style = get_post_meta($current_page_id, 'page_bg_style', true);

//Check browser and version for performance tuning
$isIE8 = ereg('MSIE 8',$_SERVER['HTTP_USER_AGENT']);
if($isIE8)
{
	$bg_style = 'Static Image';
}

if($bg_style == 'Static Image')
{
    if(has_post_thumbnail($current_page_id, 'full'))
    {
        $image_id = get_post_thumbnail_id($current_page_id); 
        $image_thumb = wp_get_attachment_image_src($image_id, 'full', true);
        $pp_page_bg = $image_thumb[0];
    }
    else
    {
    	$pp_page_bg = get_stylesheet_directory_uri().'/example/bgblack.jpg';
    }
?>
<script type="text/javascript"> 
    jQuery.backstretch( "<?php echo $pp_page_bg; ?>", {speed: 'slow'} );
</script>

<?php
} // end if static image
else
{
    $page_bg_gallery_id = get_post_meta($current_page_id, 'page_bg_gallery_id', true);
    $args = array( 
    	'post_type' => 'attachment', 
    	'numberposts' => -1, 
    	'post_status' => null, 
    	'post_parent' => $page_bg_gallery_id,
    	'order' => 'ASC',
    	'orderby' => 'menu_order',
    ); 
    $bg_photo_arr = get_posts( $args );
?>

<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/supersized.css" type="text/css" media="screen" />

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/supersized.3.1.3.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/supersized.shutter.js"></script>

<script type="text/javascript">  
    
    jQuery(function($){
    	$.supersized({
    	
    		//Functionality
    		slideshow               :   1,		//Slideshow on/off
    		autoplay				:	1,		//Slideshow starts playing automatically
    		start_slide             :   1,		//Start slide (0 is random)
    		random					: 	0,		//Randomize slide order (Ignores start slide)
    		<?php						
    			$pp_portfolio_slideshow_timer = get_option('pp_portfolio_slideshow_timer');
    			
    			if(empty($pp_portfolio_slideshow_timer))
    			{
    				$pp_portfolio_slideshow_timer = 5000;
    			}
    			else
    			{
    				$pp_portfolio_slideshow_timer = $pp_portfolio_slideshow_timer*1000;
    			}
    		?>
    		slide_interval          :   <?php echo $pp_portfolio_slideshow_timer; ?>,	//Length between transitions
    		<?php						
    			$pp_portfolio_slideshow_trans = get_option('pp_portfolio_slideshow_trans');
    			
    			if(empty($pp_portfolio_slideshow_trans))
    			{
    				$pp_portfolio_slideshow_trans = 1;
    			}
    		?>
    		transition              :   <?php echo $pp_portfolio_slideshow_trans; ?>, 		//0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
    		transition_speed		:	500,	//Speed of transition
    		new_window				:	1,		//Image links open in new window/tab
    		pause_hover             :   0,		//Pause slideshow on hover
    		keyboard_nav            :   1,		//Keyboard navigation on/off
    		performance				:	1,		//0-Normal, 1-Hybrid speed/quality, 2-Optimizes image quality, 3-Optimizes transition speed // (Only works for Firefox/IE, not Webkit)
    		image_protect			:	0,		//Disables image dragging and right click with Javascript

    		//Size & Position
    		min_width		        :   0,		//Min width allowed (in pixels)
    		min_height		        :   0,		//Min height allowed (in pixels)
    		vertical_center         :   1,		//Vertically center background
    		horizontal_center       :   1,		//Horizontally center background
    		<?php						
    			$pp_portfolio_enable_fit_image = get_option('pp_portfolio_enable_fit_image');
    			
    			if(empty($pp_portfolio_enable_fit_image))
    			{
    				$pp_portfolio_enable_fit_image = 1;
    			}
    			else
    			{
    				$pp_portfolio_enable_fit_image = 0;
    			}
    		?>
    		fit_portrait         	:   <?php echo $pp_portfolio_enable_fit_image; ?>,		//Portrait images will not exceed browser height
    		fit_landscape			:   <?php echo $pp_portfolio_enable_fit_image; ?>,		//Landscape images will not exceed browser width
    		
    		//Components
    		navigation              :   1,		//Slideshow controls on/off
    		thumbnail_navigation    :   0,		//Thumbnail navigation
    		slide_counter           :   0,		//Display slide numbers
    		slide_captions          :   0,		//Slide caption (Pull from "title" in slides array)
    		progress_bar			:	1,
    		slides 					:  	[		//Slideshow Images
    											  


<?php
    foreach($bg_photo_arr as $key => $photo)
    {
        $small_image_url = get_stylesheet_directory_uri().'/images/000_70.png';
        $hyperlink_url = get_permalink($photo->ID);
        
        if(!empty($photo->guid))
        {
        	$image_url[0] = $photo->guid;
        }

?>

	<?php $homeslides .= '{image : \''.$image_url[0].'\', thumb: \''.$small_image_url.'\', title: "<div id=\"gallery_title\">'.htmlentities($photo->post_title).'</div><div id=\"gallery_desc\">'.htmlentities($photo->post_content).'</div>"},'; ?>
	
<?php
	}
?>

    			<?php $homeslides = substr($homeslides,0,-1);
    			echo $homeslides; ?>						]
    									
    	}); 
    });
    
</script>

<div id="thumb-tray" class="load-item">
    <div id="thumb-back"></div>
    <div id="thumb-forward"></div>
    <a id="prevslide" class="load-item"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/arrow_back.png" alt=""/></a>
    <a id="nextslide" class="load-item"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/arrow_forward.png" alt=""/></a>
</div>

<div id="progress-back" class="load-item">
	<div id="progress-bar"></div>
</div>

<input type="hidden" id="pp_image_path" name="pp_image_path" value="<?php echo get_stylesheet_directory_uri(); ?>/images/"/>

<?php
}
?>

<!-- Begin content -->
<?php
	//Get social media sharing option
	$pp_social_sharing = get_option('pp_social_sharing');
	
	if(!empty($pp_social_sharing))
	{
?>
<div class="gallery_social">
    <div class="each">
    	<iframe class="facebook_button" src="//www.facebook.com/plugins/like.php?app_id=262802827073639&amp;href=<?php echo urlencode($page->guid); ?>&amp;send=false&amp;layout=box_count&amp;width=200&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=70" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:50px; height:70px;" allowTransparency="true"></iframe>
    </div>
    <div class="each">				
    	<a href="https://twitter.com/share" data-text="<?php echo $page->post_title; ?>" data-url="<?php echo $page->guid; ?>" class="twitter-share-button" data-count="vertical">Tweet</a><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
    </div>
    <div class="each">
    	<!-- Place this tag where you want the +1 button to render -->
    	<g:plusone size="tall" href="<?php echo $page->guid; ?>"></g:plusone>
    	
    	<!-- Place this render call where appropriate -->
    	<script type="text/javascript">
    	  (function() {
    	    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    	    po.src = 'https://apis.google.com/js/plusone.js';
    	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    	  })();
    	</script>
    </div>
</div>
<?php
	}
?>

<div class="page_control">
    <a id="page_minimize" href="#">
    	<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon_minus.png" alt=""/>
    </a>
    <a id="page_maximize" href="#">
    	<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon_plus.png" alt=""/>
    </a>
</div>

<div id="page_content_wrapper" style="background:#1f282d;" >

    <div class="inner wrapper">
    
    <!-- Begin main content -->
    <div class="inner_wrapper">
    
    	<div id="page_caption" class="sidebar_content full_width">
    		<h1 class="cufon"><?php the_title(); ?></h1>
    	</div>
        
        <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>		
        	
        	<div class="sidebar_content full_width">
        	
        			<?php the_content(); ?>
        			
        	</div>

        <?php endwhile; ?>
    
    </div>
    <!-- End main content -->
</div>
</div>
<br class="clear"/>
<?php get_footer(); ?>