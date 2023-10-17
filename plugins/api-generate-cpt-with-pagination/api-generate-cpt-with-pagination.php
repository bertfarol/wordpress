<?php

/**
 * Plugin Name: API Generate Custom Post Type
 * Description: All data fetch in API will be stored in auto generated CPT then will display as it own POST.
 * Version: 1.0
*  Author: Bert Farol
 */



/**
 * Add "Settings" link to plugin actions
 */
function add_settings_link($actions) {
  $anchor_link = '<a href="' . admin_url('admin.php?page=refresh-api-data') . '">Settings</a>';
  $settings_link = array('settings' => $anchor_link,);
  return array_merge($settings_link, $actions);
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'add_settings_link');



/* Retrieve data from API and insert into the Custom Post 'Artwork' */
function create_custom_posts_from_api($custom_post_type) {  
  $base_api_url = 'https://demo.espaciomanilagallery.com/wp-json/api/v1/artworks';
  $page = 1; // Start with page 1
  $per_page = 10; // Number of posts per page
  $orderby = 'date';
  $order = 'ASC';

  do {
    // Construct the API URL with the current page and per_page parameters
    $api_url = $base_api_url . '?per_page=' . $per_page . '&page=' . $page . '&orderby=' . $orderby . '&order=' . $order;

    echo "<script>console.log(". $api_url .")</script>";

    $response = wp_remote_get($api_url);

    if (is_array($response)) {
      $data = json_decode($response['body']);

      if (!empty($data)) {
        foreach ($data as $artwork) {
          // Check if the post already exists based on a unique identifier, for example, 'ID' or 'slug'
          $post_exists = get_page_by_title_custom($artwork->title, $custom_post_type);

          if (!$post_exists) {
            $post_data = array(
              'post_title' => $artwork->title,
              'post_type' => $custom_post_type,
              'post_status' => 'publish',
              'meta_input' => array(
                'artist_name' => $artwork->artist_name,
                // Add more custom fields as needed
              ),
            );

            // Insert the post and get its ID
            $post_id = wp_insert_post($post_data);

            if (!is_wp_error($post_id)) {
              // Get the featured image URL from the API
              $featured_image_url = $artwork->featured_image; // Replace with the actual field or property from the API

              if ($featured_image_url) {
                // Download the image and set it as the featured image
                $image_id = media_sideload_image($featured_image_url, $post_id, '', 'id');
                if (!is_wp_error($image_id)) {
                  set_post_thumbnail($post_id, $image_id);
                }
              }
            }
          }
        }
      }
    }

    // Increment the page number to fetch the next set of data
    $page++;
  } while (!empty($data)); // Continue until there's no more data

  // All data has been retrieved
}


function get_page_by_title_custom($page_title, $post_type = 'page') {
  $posts = get_posts(
      array(
        'post_type'              => $post_type,
        'post_status'            => 'all',
        'posts_per_page'         => 1,
        'update_post_term_cache' => false,
        'update_post_meta_cache' => false,
        'title'                  => $page_title, // Use 'title' parameter to query by title
      )
    );

  if (!empty($posts)) {
    return $posts[0];
  }

  return null;
}


/* Add a custom menu item to trigger data refresh */
function add_custom_menu_item() {
  add_menu_page(
    'Refresh API Data',
    'Refresh API Data',
    'manage_options', // Adjust the capability as needed
    'refresh-api-data',
    'refresh_api_data_callback'
  );
}
add_action('admin_menu', 'add_custom_menu_item');

/* Callback function for the custom menu item */
function refresh_api_data_callback() {
  if (isset($_POST['refresh_api_data'])) {
    $selected_post_type = $_POST['post_type']; // Get the selected post type from the form

    if (!empty($selected_post_type)) {
      echo '<div class="updated" id="loading-message"><p>Loading...</p></div>';
      create_custom_posts_from_api($selected_post_type); // Pass the selected post type
      echo '<script>document.getElementById("loading-message").style.display = "none";</script>';
      echo '<div class="updated"><p>API data has been refreshed and stored in the selected post type: ' . $selected_post_type . '</p></div>';
    } else {
      echo '<div class="error"><p>Please select a custom post type.</p></div>';
    }
  }

  // Display the refresh button and post type selection dropdown
  $post_types = get_post_types(array('public' => true), 'objects'); // Get a list of custom post types
  echo '<div class="wrap">';
  echo '<h2>Refresh API Data</h2>';
  echo '<form method="post">';
  echo '<label for="post_type">Select Post Type:</label>';
  echo '<select name="post_type" id="post_type">';
  foreach ($post_types as $post_type) {
    echo '<option value="' . $post_type->name . '">' . $post_type->label . '</option>';
  }
  echo '</select>';
  echo '<input type="submit" name="refresh_api_data" class="button button-primary" value="Fetch API data">';
  echo '</form>';
  echo '</div>';
}


