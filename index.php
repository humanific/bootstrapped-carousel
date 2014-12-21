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

function bootstrapped_carousel_shortcode( $atts, $content = null ) {
   global $post;
   global $carouselid;


   if(isset($atts['ids'])){
      $pids = explode(',', $atts['ids']);
     $ids = array();
     foreach( $pids as $id ) $ids[] = intval($id);
      $wpq = new WP_Query( array(  'post__in' => $ids ,'post_type' => 'attachment','post_status'=>'inherit','posts_per_page' => -1,'orderby' => 'post__in') );
      $images = $wpq->posts;
   }else if(isset($atts['postid'])){
    $images =get_children( array('post_parent' => $atts['postid'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') );
   }else if($content==null) {
      $images =get_children( array('post_parent' => $post->ID, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') );
   }

  $atts['indicators'] = !isset($atts['indicators']) || $atts['indicators'] != 'false' ;
	$atts['controls'] = !isset($atts['controls']) || $atts['controls'] != 'false' ;
	$carouselid++;
  $first = -1;


	ob_start();
	?>
		<div class="carousel slide" data-interval="<?php echo $atts['interval'] ? $atts['interval'] : 5000 ?>" id="carousel<?php echo $carouselid ?>" data-ride="carousel">
		  <!-- Indicators -->
      <?php if($atts['indicators']): ?>
		  <ol class="carousel-indicators">
			<?php 
      if ($images) :
  			foreach( $images as $k => $imagePost ): 
          if($first===-1) $first = $k;
          ?>
  				<li data-target="#carousel<?php echo $carouselid ?>" data-slide-to="<?php echo $k;?>" <?php if($k==$first) : ?> class="active" <?php endif; ?>></li>
  			<?php endforeach ; 
      endif; ?>

		  </ol>
		  <?php endif; ?>
		  <div class="carousel-inner">
			<?php 
      if ($images) :
  			foreach( $images as $k => $imagePost ): 
  				$image_attributes = wp_get_attachment_image_src(  $imagePost->ID, 'big' ); 
  				?>
  				<div class="item<?php if($k==$first) : ?> active<?php endif; ?>" ><img class="fullwidth" src="<?php echo $image_attributes[0]; ?>" /></div>
  			<?php endforeach;
      endif; ?>

      <?php echo str_replace(array('<p>','</p>'), array('',''), $content);  ?>

		  </div>

		  <?php 

      if($atts['controls']): ?>
		  <a class="left carousel-control" href="#carousel<?php echo $carouselid ?>" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left"></span>
		  </a>
		  <a class="right carousel-control" href="#carousel<?php echo $carouselid ?>" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right"></span>
		  </a>
    <?php endif; ?>
		</div>
		<?php 

    return ob_get_clean();
}





add_shortcode( 'carousel', 'bootstrapped_carousel_shortcode' );


?>