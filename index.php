<?php
/*
Plugin Name: CCR Featured Posts
Description: WordPress Featured Posts Widget Plugin choose by categories.
Plugin URI: http://codexcoder.com/featured-posts
Author: CodexCoder Team
Author URI: http://codexcoder.com
Version: 1.0.0
*/


//Feature Post Widget
add_action('widgets_init', 'ccr_feature_post_widget');

function ccr_feature_post_widget() {
	register_widget('CCR_Featured_Posts' );
}

class CCR_Featured_Posts extends WP_Widget
{

	public function __construct()
	{
		parent::__construct(
            'ccr_feature_post', // Base ID
            'CCR Featured Posts', // Name
            array( 'description' => __( 'CCR Featured Posts'), ) // Args
            );
	}

	public function widget( $args, $instance ) {
		$count  = $instance['count'];
		$category_name = $instance['category_name'];
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		$loop = new WP_Query(array( 'cat' => $category_name, 'posts_per_page'=>$count));    

		if($loop->have_posts() ) { while( $loop->have_posts() ) { $loop->the_post() ?>
		<div class="feat">
			<?php the_post_thumbnail(); ?>			
			<div class="metatag">
				<?php echo date( 'd F, Y', strtotime( get_the_date() ) ); ?> <br>
				<a href="<?php comments_link(); ?>"><?php comments_number('0 Comment', '1 Comment', '% Comments'); ?> </a>
			</div>
			<h3 class="feat-title"><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h3>
		</div>
		<?php
	}
}

?>


<?php 
echo $args['after_widget'];
}

public function form( $instance ) {

      // outputs the options form on admin
	$title  = isset($instance[ 'title' ]) ? $instance[ 'title' ] : 'Featured Posts';
	$count  = isset($instance[ 'count' ]) ? $instance[ 'count' ] : 3;
	$category_name  = isset($instance[ 'category_name' ]) ? $instance[ 'category_name' ] : '';
	?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Featured Posts Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_name( 'count' ); ?>"><?php _e( 'Number of posts:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="text" value="<?php echo esc_attr( $count ); ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_name( 'category_name' ); ?>"><?php _e( 'Select Featured Caterory:' ); ?></label> 
		<?php wp_dropdown_categories('hide_empty=0&hierarchical=1&id='.$this->get_field_id('category_name').'&name='.$this->get_field_name('category_name').'&selected='.$category_name); ?>

	</p>
	<?php 
}

public function update( $new_instance, $old_instance ) {
      // processes widget options to be saved

	$instance = array();
	$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
	$instance['count'] = ( ! empty( $new_instance['count'] ) ) ? strip_tags( $new_instance['count'] ) : '';
	$instance['category_name'] = ( ! empty( $new_instance['category_name'] ) ) ? strip_tags( $new_instance['category_name'] ) : '';
	return $instance;  return $instance;

	}
}

//Custom Featured Posts Style
add_action( 'wp_enqueue_scripts', 'ccr_featured_posts_style' );
function ccr_featured_posts_style(){
	wp_register_style('ccr_featured-posts-style', plugins_url( 'style.css', __FILE__) ); 
	wp_enqueue_style( 'ccr_featured-posts-style' );
}


//Featured Posts Thumbnail
function ccr_feature_posts_after_setup_theme() {
	add_image_size( 'feature-thumb', 265 , 160 );
}
add_action('after_setup_theme','ccr_feature_posts_after_setup_theme');

