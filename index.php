<?php
/**
 * @package bootstrapped_carousel
 * @version 0.1
 */
/*
Plugin Name: Bootstrapped carousel
Plugin URI: https://github.com/humanific/bootstrapped-carousel
Description: Shortcode for displaying bootstrap carousels
Author: Francois Richir
Version: 0.1
Author URI: http://humanific.com
*/

function bootstrapped_carousel_get($images){
	global $carouselid;
	$carouselid++;
	ob_start();
	if ($images) :?>
		<div class="carousel slide" data-ride="carousel" id="carousel<?php echo $carouselid ?>">
		  <!-- Indicators -->
		  <ol class="carousel-indicators">
			<?php 
			foreach( $images as $k => $imagePost ): ?>
				<li data-target="#carousel<?php echo $carouselid ?>" data-slide-to="<?php echo $k;?>" <?php if($k==0) : ?> class="active" <?php endif; ?>></li>
			<?php endforeach ;?>
		  </ol>
		  <!-- Wrapper for slides -->
		  <div class="carousel-inner">
			<?php 
			foreach( $images as $k => $imagePost ): 
				$image_attributes = wp_get_attachment_image_src(  $imagePost->ID, 'big' ); 
				?>
				<div class="item<?php if($k==0) : ?> active<?php endif; ?>" ><img src="<?php echo $image_attributes[0]; ?>" /></div>
			<?php endforeach ;?>
		  </div>
		  <!-- Controls -->
		  <a class="left carousel-control" href="#carousel<?php echo $carouselid ?>" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left"></span>
		  </a>
		  <a class="right carousel-control" href="#carousel<?php echo $carouselid ?>" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right"></span>
		  </a>
		</div>
		<?php endif; 
		return ob_get_clean();
}


function bootstrapped_carousel_shortcode( $atts, $content = null ) {
   global $post;
   if(isset($atts['ids'])){
   		$pids = explode(',', $atts['ids']);
	   $ids = array();
	   foreach( $pids as $id ) $ids[] = intval($id);
   		$wpq = new WP_Query( array(  'post__in' => $ids ,'post_type' => 'attachment','post_status'=>'inherit','posts_per_page' => -1,'orderby' => 'post__in') );
		$images = $wpq->posts;
   }else if(isset($atts['postid'])){
		$images =get_children( array('post_parent' => $atts['postid'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') );
   }else{
   		$images =get_children( array('post_parent' => $post->ID, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') );
   }
   return bootstrapped_carousel_get($images);
}

add_shortcode( 'carousel', 'bootstrapped_carousel_shortcode' );


?>