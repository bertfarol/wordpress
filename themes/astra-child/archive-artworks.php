<?php
get_header(); // Include your header template

$args = array(
  'post_type' => 'artworks', // Use 'artwork' as the post type
  'posts_per_page' => -1,  // Display all posts
  'order' => 'ASC',       // Change to 'DESC' if you want to reverse the order
);

$custom_query = new WP_Query($args);

if ($custom_query->have_posts()) :
  while ($custom_query->have_posts()) : $custom_query->the_post();
    // Display the content of each artwork post
?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <header class="entry-header">
        <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      </header>
      <div class="entry-content">
        <?php
        // Display custom fields like artist name
        $artist_name = get_post_meta(get_the_ID(), 'artist_name', true);
        if (!empty($artist_name)) {
          echo '<p>Artist: ' . $artist_name . '</p>';
        }
        // Add more custom fields as needed
        ?>
      </div>
    </article>
<?php
  endwhile;

  // Add pagination links if needed
  the_posts_pagination();

else :
  // If no posts are found
  echo 'No artworks found';

endif;

wp_reset_postdata(); // Restore the global post data

get_footer(); // Include your footer template
?>