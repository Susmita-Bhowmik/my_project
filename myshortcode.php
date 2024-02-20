<?php 

/** Template Name: Home */


get_header(); ?>
<?php get_footer(); ?>
<?php bloginfo('template_url'); ?>/
<?php
if( have_rows('') ):
 while( have_rows('') ) : the_row(); ?>

 <?php
    endwhile;
    endif;
?>
<?php the_post_thumbnail_url(); ?>
 <?php the_field(''); ?>
 <?php the_sub_field(''); ?>
 <?php wp_nav_menu( array( 'theme_location' => 'header-menu') );?>
 <?php  dynamic_sidebar(''); ?>
 <?php 
 function register_my_menus() {
	register_nav_menus(
	  array(
		'header-menu' => __( 'Header Menu' ),
		'footer-menu' => __( 'Footer Menu' )
	  )
	);
  }
  add_action( 'init', 'register_my_menus' );
  ?>

<?php
$category = get_field('select_category');
$args = array( 'post_type' => 'product', 'posts_per_page' => 1, 'post__not_in'=>'', 'product_cat' =>  $category, 'order' => 'DESC' );
$loop = new WP_Query( $args );
while ( $loop->have_posts() ) : $loop->the_post();
$product_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
?>
<?php endwhile; ?>
<?php wp_reset_query(); ?>
<?php echo $product_image[0] ;?>

<?php the_title() ; ?>

<?php the_content() ; ?>
	<?php
    $args = array( 'post_type' => 'service', 'posts_per_page' => 10  ,'orderby' => 'ID','order' => 'ASC','orderby' => 'menu_order');
    $loop = new WP_Query( $args );
    while ( $loop->have_posts() ) : $loop->the_post();
	$myimage = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
    
	 $date = get_the_date(); echo $date; 
	?>
	<?php

    $myimage = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
    $product_price = $product->get_price();
	 $date = get_the_date(); echo $date; 
	
	do_action( 'woocommerce_after_shop_loop_item' );
	?> 
	<?php
	function special_nav_class ($classes, $item) {
		if (in_array('current-menu-item', $classes) ){
			$classes[] = 'active ';
		}
		return $classes;
	}
	add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);
	?>
|<?php	add_filter('wpcf7_autop_or_not', '__return_false'); ?>
<?php  echo do_shortcode('') ; ?>

<?php  echo do_shortcode('[contact-form-7 id="270" title="Pre Assessment General Info Form"]'); ?> 

<?php if( have_rows('') ): ?> 
    
    <?php while( have_rows('') ): the_row();  ?>

     <?php endwhile; ?>
        <?php endif; ?>
<!-- newletter -->
        <form action="" id="newsletter_form" method="post">
                                        <div class="footer-form">
                                            <div class="form-grop">
                                                <label for="">Email</label>
                                                <input type="text" name="user_email" id="user_email" placeholder="Enter your Email addreess">
                                                <button class="footer-btn" type="submit" name="submit" id="submit"></button>
												<div id="result_msg" style="color:white; margin:10px;">
                                            </div>
                                        </div>
                                    </form>

<!-- ajax hook function for newsletter -->
 <?php
 
  add_action("wp_ajax_newsletter", "newsletter");
  add_action("wp_ajax_nopriv_newsletter", "newsletter");
  
  function newsletter(){
	$arr=[];
	wp_parse_str($_POST['newsletter'],$arr);
	$email = $arr['user_email'];
	global $wpdb;
	global $table_prefix;
	$table = $table_prefix.'newsletter';


	$query   = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}newsletter WHERE user_email =  '$email' ");
   $results = $wpdb->get_results( $query );

if ( count( $results ) > 0 ) {
    wp_send_json_error( 'This Email is already exist!');
}
else{
		$result=$wpdb->insert($table,[
		
			"user_email"=>$arr['user_email']
		]);
		if($result>0){
			wp_send_json_success("Thanks for subscribing us!");
		}else{
			wp_send_json_error("Please try again");
		}

	  }
	}
?>

<?php
$taxonomy     = 'product_cat';
$orderby      = 'name';
$show_count   = 0;      
$pad_counts   = 0;     
$hierarchical = 1;     
$title        = '';
$empty        = 0;

$args = array(
	'taxonomy'     => $taxonomy,
	'orderby'      => $orderby,
	'show_count'   => $show_count,
	'pad_counts'   => $pad_counts,
	'hierarchical' => $hierarchical,
	'title_li'     => $title,
	'hide_empty'   => $empty,
);


// get product categories and image

$all_categories = get_categories( $args );
foreach ($all_categories as $cat) {
	if($cat->slug != 'uncategorized') {
		$category_id = $cat->term_id;

		// Get the category thumbnail image URL
		$thumbnail_id = get_term_meta( $category_id, 'thumbnail_id', true );
		$image_url    = wp_get_attachment_url( $thumbnail_id );

		// Display the category thumbnail image
?>

	
  
<li>
	<div class="wapper-main">
		<div class="filter-drop"></div>
		<span>
			<figure style="background-image: url(<?php echo $image_url;  ?>);"></figure>
		</span>
	 <?php   echo '<br /><a href="'. get_term_link($cat->slug, 'product_cat') .'">'. $cat->name .'</a>'; ?>
	</div>
</li>
	<?php } } ?>





<!-- ajax newsletter -->
<script>
$('#newsletter_form').submit(function(event){
 
   event.preventDefault();
    $('#result_msg').html('');
    var link = "<?php echo admin_url('admin-ajax.php')?>";
    var form = $('#newsletter_form').serialize();
    var formData = new FormData;
    formData.append('action','newsletter');
    formData.append('newsletter',form);
    $('#submit').attr('disabled',true);
	
    $.ajax({
        url:link,
        data:formData,
        processData:false,
        contentType:false,
        type:'post',
        success:function(result){
            $('#submit').attr('disabled',false);
            if(result.success==true){
                $('#newsletter_form')[0].reset();
            }
            $('#result_msg').html('<span class="'+result.success+'">'+result.data+'</span>')    
            //result.success
            //result.data
        }
    });
});    
</script>
<div id="result_msg" style="color:white; margin:10px;">
<?php
// custom postype
add_action( 'init', 'blog' );

add_post_type_support( 'blog', 'thumbnail' ); 

function blog() {
    register_post_type( 'blog',
        array(
            'labels' => array(
                'name' => __( 'Blog' ),
                'singular_name' => __( 'Blog' )
            ),
            'public' => true,
            'has_archive' => true,
	          'rewrite' => array('slug' => 'blog'),
            'menu_icon' => 'dashicons-format-aside'

        )
    );
}
add_action( 'init', 'blog' );

$trimmed_content = wp_trim_words($content, 40);
 
echo $trimmed_content;
?>

<section class="inner-banner" style="background-image: url(<?php the_post_thumbnail_url(); ?>)">
    <div class="text">
        <h2><?php the_title(); ?></h2>
    </div>
</section>

<!-- custom post type with custom tags and custom categories -->
<?php 

	  function create_my_taxonomy() {

		register_taxonomy(
			'service-category',
			'service',
			array(
				'label' => __( 'Category' ),
				'rewrite' => array( 'slug' => 'service-category' ),
				'hierarchical' => true,
			)
		);
		register_taxonomy(
			'blog-tags',
			'blog',
			array(
				'label' => __( 'Tags' ),
				'rewrite' => array( 'slug' => 'blog-tags' ),
				'hierarchical' => true,
			)
		);
	}
	add_action( 'init', 'create_my_taxonomy' );
	


add_action( 'init', 'blog' );

add_post_type_support( 'blog', 'thumbnail' ); 

function blog() {
    register_post_type( 'blog',
        array(
            'labels' => array(
                'name' => __( 'Blog' ),
                'singular_name' => __( 'Blog' )
            ),
            'public' => true,
            'has_archive' => true,
	          'rewrite' => array('slug' => 'blog'),
			  'supports' => array('title', 'editor', 'page-attributes'),
            'menu_icon' => 'dashicons-format-aside'

        )
    );
}
add_action( 'init', 'blog' );

// custom options making
function mythemeoptios(){
   
	/// menu page code to be added at admin panel
	add_menu_page(
	"theme-options",  // page title
	"Theme Options",  // Menu title
	"manage_options", // capability
	"theme-options",  // menu slug
	"mycustom_options", // callback function
	"dashicons-sticky" // icon
	);
 
 }
 function mycustom_options(){
	// we have to link our custom settings 
	?>
	<form action="options.php" method="post">
	<?php
	 settings_errors();
	
	 settings_fields("section");
     do_settings_sections("theme-options");
     submit_button();
	?>
	</form>
	<?php
 }
 add_action("admin_menu","mythemeoptios");
 

// theme options settings page
function theme_options_setting(){
     
	// Step#1 this code basically provides an area where you can register your fields
	add_settings_section(
	"section", // id of settings section
	 "All page", // title
	 null, // callback function
	 "theme-options" // page 
	);
	
	// Step#2
	add_settings_field(
	"channel_name",
	"Channel Name",
	"display_channel_name",
	"theme-options",
	"section"
	);
	
	add_settings_field(
	"facebook_url",
	"Facbook URL",
	"display_fb_url",
	"theme-options",
	"section"
	);
	
	 add_settings_field(
	"twitter_url",
	"Twitter URL",
	"display_twitter_url",
	"theme-options",
	"section"
	);
	
	// step #3 We need to add this(channel_name) setting to area
	
	register_setting("section","channel_name");
	register_setting("section","facebook_url");
	register_setting("section","twitter_url");
	
  }
  
  add_action("admin_init","theme_options_setting");
  
  function display_twitter_url(){
	?>
<input type="text" name="twitter_url" value="<?php echo get_option('twitter_url'); ?>">
	<?php
	// twitter input box for admin
  }
  
  function display_channel_name(){
	?>
	<input type="text" name="channel_name"  value="<?php echo get_option('channel_name'); ?>">
		<?php
	 // channel input box for admin
  }
  
  function display_fb_url(){
	?>
	<input type="text" name="facebook_url"  value="<?php echo get_option('facebook_url'); ?>">
		<?php
	// facebook url input box for admin
  }
  add_action("admin_menu","mythemeoptios");




  $content = get_the_content();
  $trimmed_content = wp_trim_words($content, 40); echo $trimmed_content; ?>

  <?php 
  $content = get_sub_field('content');
  $trimmed_content = wp_trim_words($content, 20); echo $trimmed_content; ?>

  <?php the_field('about_section_content_subheading', 5); ?>
  <a href="<?php echo home_url(); ?>">

                           
<?php 
  
  $custom_logo_id = get_theme_mod( 'custom_logo' );
  $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
  if ( has_custom_logo() ) {
	  echo '<img  src="' . esc_url( $logo[0] ) . '" alt="' . get_bloginfo( 'name' ) . '">';
  } else {
	  echo '<h1>' . get_bloginfo('name') . '</h1>';
  }
  
?>
	

  </a>
   <?php
       global $woocommerce;
    echo $count = $woocommerce->cart->cart_contents_count;
        ?> 
<!-- adddin class in js -->
<script>
$(".tab-pane:eq(0)").addClass("active show")</script>
		


		<!-- fetch category of the post type -->

							<?php
                                $args = array(
                                            'taxonomy' => 'blog-category',
                                            'orderby' => 'name',
                                            'order'   => 'ASC'
                                        );

                                $cats = get_categories($args);

                                foreach($cats as $cat) {
                                ?>
                                    <li><a href="<?php echo get_category_link( $cat->term_id ) ?>">
                                        <?php echo $cat->name; ?>
                                    </a></li> 
                                <?php
                                }
                                ?> 


<?php $str = get_sub_field('mission_name');
              $str_new   = str_replace(' ', '-', strtolower($str)); ?>

                                                

<!-- post type fetch monthly -->
<?php 
                                $args = array(
                                    'post_type'    => 'blogs',
                                    'type'         => 'monthly',
                                    'echo'         => 0
                                );
                                echo '<ul>'.wp_get_archives($args).'</ul>'; ?>
                                
							<header class= 'mian-header <?php if (!is_home()){ $page_id = 6; if (!is_page($page_id)) {  echo 'inner-header'; }}?> '>


							<script>	setTimeout(function(){
									window.location.href = "<?php echo site_url('/my-account'); ?>" 
								}, 2000)

								[_site_title] <wordpress@creativeproapp.com> </script>



							

<!-- default serach wordpress -->
					
							<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                                     
								<input type="text" class="search-field" placeholder="Search ..."value="<?php echo get_search_query(); ?>" name="s" >
								<button type="submit"  class="search-btn search-submit"></button>
							</form>
												

							<span>Is Here and Everywhere</span> 

<script>
    $(document).ready(function() {
        $('a[href^="#"]').on('click', function(event) {
            
            event.preventDefault();

            
            var target = $(this).attr('href');

           
            $('html, body').animate({
                scrollTop: $(target).offset().top
            }, 1000); 
        });
    });
</script>
<script>

     setTimeout(function () {
                     $('#theDiv').html('');
                 }, 2500);
</script>

<h2>Contact Information</h2>
<table  border="1" cellspacing="0" cellpadding="0">
   
  
   <tr>
      <td><b>Full Name</b></td> <td>[contact_fname] [contact_lname]</td>
    </tr>
    
   <tr>
      <td><b>Email Address</b></td> <td>[Contact_email]</td>
   </tr>
   <tr>
      <td><b>Phone Number</b></td> <td>[contact_phone]</td>
   </tr>
   
   <tr>
      <td><b>Message </b></td> <td>[message]</td>
   </tr>
   
</table>

<?php echo sprintf(
										'<a href="%s" data-quantity="1" class="add_cart add_to_cart_button product_type_simple" data-product_id="%s" data-product_sku="%s"><i class="fa-solid fa-bag-shopping"></i>%s</a>',
										esc_url($product->add_to_cart_url()),
										esc_attr($product->get_id()),
										esc_attr($product->get_sku()),
										esc_html__('Add to Cart', 'woocommerce')
									); ?>



for dynamic changing copyright
----------------------------------------
Copyright © <script>document.write(new Date().getFullYear());</script> Web.com Group, Inc. All Rights Reserved.

