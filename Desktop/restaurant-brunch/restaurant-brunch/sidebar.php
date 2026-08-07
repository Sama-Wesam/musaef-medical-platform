<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package restaurant_brunch
 */
?>

<aside id="secondary" class="widget-area">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->

<?php 
  if ( ! is_active_sidebar( 'sidebar-1' )) { ?>
	<aside id="secondary" class="widget-area">
		<section id="Search" class="widget widget_block widget_archive " >
		    <h2 class="widget-title"><?php esc_html_e('Search', 'restaurant-brunch'); ?></h2>
		    <?php get_search_form(); ?>
		</section>
		<section id="archives" class="widget widget_block widget_archive " >
		    <h2 class="widget-title"><?php esc_html_e('Archives', 'restaurant-brunch'); ?></h2>
		    <ul>
		        <?php
		        wp_get_archives(array(
		            'type'            => 'monthly',
		            'show_post_count' => true,
		        ));
		        ?>
		    </ul>
		</section>
		<section id="categories" class="widget widget_categories" role="complementary">
		    <h2 class="widget-title"><?php esc_html_e('Categories', 'restaurant-brunch'); ?></h2>
		    <ul>
		        <?php
		        wp_list_categories(array(
		            'orderby'    => 'name',
		            'title_li'   => '',
		            'show_count' => true,
		        ));
		        ?>
		    </ul>
		</section>
		<section id="tags" class="widget widget_tag_cloud" role="complementary">
		    <h2 class="widget-title"><?php esc_html_e('Tags', 'restaurant-brunch'); ?></h2>
		    <?php
				$restaurant_brunch_tags = get_tags();
				if ($restaurant_brunch_tags) {
				    echo '<div class="tag-cloud">';
				    foreach ($restaurant_brunch_tags as $restaurant_brunch_tag) {
				        $restaurant_brunch_tag_link = get_tag_link($restaurant_brunch_tag->term_id);
				        echo '<a href="' . esc_url($restaurant_brunch_tag_link) . '" style="font-size:' . esc_attr($restaurant_brunch_tag->font_size) . 'px;" class="tag-link">' . esc_html($restaurant_brunch_tag->name) . '</a>';
				    }
				    echo '</div>';
				} else {
					echo '<p>' . esc_html__( 'No tags found.', 'restaurant-brunch' ) . '</p>';
				}
			?>
		</section>
		<section id="recent-posts" class="widget" role="complementary">
		    <h2 class="widget-title"><?php esc_html_e('Recent Posts', 'restaurant-brunch'); ?></h2>
		    <ul class="recent-posts-list">
		        <?php
		        $restaurant_brunch_recent_posts = get_posts(array(
		            'numberposts' => 5, // Adjust the number of posts to display
		            'post_status' => 'publish',
		        ));

		        foreach ($restaurant_brunch_recent_posts as $restaurant_brunch_post) :
		            setup_postdata($restaurant_brunch_post);
		            ?>
		            <li>
		                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		            </li>
		            <?php
		        endforeach;
		        wp_reset_postdata();
		        ?>
		    </ul>
		</section>

	</aside><!-- #secondary -->
<?php } ?>