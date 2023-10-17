<?php
/* Template Name: API Integration */

get_header();

/* 
  Fetch and display API data using PHP
*/

// $api_url = 'https://demo.espaciomanilagallery.com/wp-json/wl/v1/artworks';
// $api_response = wp_remote_get($api_url);
// $api_data = wp_remote_retrieve_body($api_response);

// if (!is_wp_error($api_response)) {
//   // Decode the JSON response into an array
//   $api_array = json_decode($api_data, true);

//   if (!empty($api_array)) {
//     // Loop through the array and display the data
//     foreach ($api_array as $item) {
//       echo '<h2>' . esc_html($item['title']) . '</h2>';
//       echo '<p>' . esc_html($item['artwork_type']) . '</p>';
//       // Add more HTML elements and data as needed
//     }
//   } else {
//     echo 'No data available from the API.';
//   }
// } else {
//   echo 'Failed to fetch data from the API.';
// }
?>



<!-- Fetch and display API data using JavaScript Fetch/Ajax -->

<div class="artwork-container">

</div>

<?php
get_footer();
?>