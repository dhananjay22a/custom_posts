<?php
/**
* Custom Posts widget class
*
* @since 2015-10-09
* @uses $wp_query
* 
* @version 2015-10-09 Dhananjay Singh - PMCVIP-243
*
*/

	namespace dj\custom_post;
	
	class post_widget extends \WP_Widget {
		
		function __construct() {
     		
			$widget_ops = array(
					         'classname'   => 'post_widget_class',
					         'description' => 'Widget to display custom post type data .' 
						);
		
			parent::__construct( 'post_widget', 'Custom Post Widget', $widget_ops ); 

		}
		
		
		//build our widget settings form
     	function form( $instance ) {
			
			$instance 			= wp_parse_args( (array) $instance ); 
			$custom_post_type	= $instance['custom_post_type'];
		
			$args = array(
			   'public'   => true,
			   '_builtin' => false
			);
			$output = 'objects'; // names or objects, note names is the default
			$operator = 'and'; // 'and' or 'or'
			$post_types = get_post_types( $args, $output, $operator ); 
			
			?>
				<p>Select Custom Post Type : 
					<select name="<?php echo $this->get_field_name( 'custom_post_type' ); ?>" id="author">
					<?
						foreach ( $post_types as $post_type => $post_type_name ) {
							$selected	= (trim($custom_post_type) == $post_type) ? "selected" : "";
					?>
							<option value="<?php echo esc_html($post_type) ?> " <?php echo $selected ?>><?php echo esc_html($post_type_name->labels->name) ?></option>
					<?
						}
					?>
					</select>
				</p>
         	<?php
		}
		
		//save our widget settings 
     	function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['custom_post_type']  =  sanitize_text_field( $new_instance['custom_post_type'] );
         	return $instance;
		}
		
		
		
		//Function to display up to 5 most recent posts, maximum of 30 days old, for posts of that custom post type
		function widget( $args, $instance ) {
			
			$currentID	=	(is_single()) ? get_the_ID() : 0;
			
			$args =  array( 
						'posts_per_page' => '5', 
						'post_type'      => $instance['custom_post_type'],
						'post__not_in'	=> array($currentID),
						'orderby' => 'date',
						'order'   => 'DESC',
						'date_query' => array(
							array(
								'after'     => '1 month ago',
								'inclusive' => true,
							),
						)
			); 
			
			//Checking cache if data is available. And if so assign it to $news and avoid if part.
			$news = get_transient('custom_posts_key_'.$currentID);
			if ($news === false) {
				$news = new \WP_Query( $args );
				//Adding data to cache
				set_transient('custom_posts_key_'.$currentID, $news, 60);
			}
			
			//Main widget ourter div
			echo '<div id="pmc_custompost_type_main_div">';
				//Widget title div
				echo '<div class="widget_title_div">';
					echo "Latest News";
				echo '</div>';
				//Widget title div ends here
			
				// The Loop 
				$counter	=	0;
				while ( $news->have_posts() ) : $news->the_post();
					
					$class	=	($counter%2 == 0 ) ? 'row1' : 'row2';
					
					//News Item main div
					echo '<div class="news_item_main_div '.$class.'">';
						//New Item thumbnail div
						echo '<div class="news_item_thumbnail '.$class.'" >';
							?>
								<a href="<?php the_permalink()?>" title="<?php the_title()?> "> 
							<?php
							if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
								the_post_thumbnail( array(50, 50) );
							} else {
								
							}
							echo '</a>';
						echo '</div>';
						//News Item thumbnail div ends here
						
						//News Item title div
						echo '<div class="news_item_title '.$class.'" >';
							?><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo esc_html($this->short_title(100)); ?></a><br /><?php 
						echo '</div>';
						//News item title div ends here
						
						//Authorname div
						echo '<div class="author_name">';
							echo "Author : ";
							the_author();
						echo '</div>';
						//Authername div ends here
						
					$counter++;
					echo '</div>';
					//News item main div ends here
			endwhile;
			
			//Widget main div ends here
			echo "</div>";
			// Reset Post Data 
			wp_reset_postdata();
		}
		
		
		/**
		* Function to short title if larger than $length
		*
		* @since 2015-10-09
		* @uses get_the_title() to get title of current post
		* 
		* @param $length is maximum allowed length
		* @return $newsTitle after shortening.
		*/
		function short_title( $length ) {
			
			$newsTitle	=	get_the_title();
			if(strlen($newsTitle) > $length )
				return $shortNewsTitle	=	substr($newsTitle, 0, $length ). '...';
			else return $newsTitle;
		}
		
	}
?>