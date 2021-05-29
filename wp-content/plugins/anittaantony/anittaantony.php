<?php
/**
* Plugin Name: Custom API
* Plugin URI : https://wordpress.org/plugins/anittaantony/
* Description: Enables the WordPress to get two API responses to get the wordpress informations and database table deatails. To get the wordpress information use url index.php/wp-json/wp-content/plugins/anittaantony/api_info.php.To get the database table information use url index.php/wp-json/wp-content/plugins/anittaantony/db_info.php
 * Version:     1.6
 * Author:      WordPress Contributors
 * Author URI:  https://github.com/WordPress/anittaantony/
 */

/** 
wordpress informations route:
 */
add_action('rest_api_init', function () {
  register_rest_route( 'wp-content/plugins', 'anittaantony/api_info.php',array(
                'methods'  => 'GET',
                'callback' => 'wp_rest_api_function'
      ));
});

/** 
database informations route:
 */
add_action('rest_api_init', function () {
  register_rest_route( 'wp-content/plugins', 'anittaantony/db_info.php',array(
                'methods'  => 'GET',
                'callback' => 'wp_db_infos'
      ));
}); 

/*wordpress informations **/
function wp_rest_api_function( $data ) {   
     
      $wp_rest_api_infos = array();
      $wp_rest_api_infos['site_name'] = get_bloginfo();
      $wp_rest_api_infos['site_url']  = get_site_url();
      $wp_rest_api_infos['wp_base_absolute_path']  =set_url_scheme( get_option( 'home' ), 'http' ); 
      $wp_rest_api_infos['wp_content_absolute_path']  = content_url();
      $base = dirname(__FILE__);
      $fi = new FilesystemIterator($base, FilesystemIterator::SKIP_DOTS);
      $wp_rest_api_infos['number_of_files']  = iterator_count($fi);

      return new WP_REST_Response( wp_send_json( $wp_rest_api_infos ) );




} 
/*database informations **/
function wp_db_infos( $data ) 
{
  global $wpdb;
  $mytables=$wpdb->get_results("SHOW TABLES");
  $table_infos = array();
  foreach ($mytables as $mytable)
  {
    foreach ($mytable as $t) 
    {     
        // echo $t . "<br>";
      $no_of_row_count = $wpdb->get_var("SELECT COUNT(*) FROM $t");
      $table_infos[$t] = array( 'records' => $no_of_row_count );
    }
  }
  $table_infos = array('tables' => $table_infos );
  return new WP_REST_Response( wp_send_json( $table_infos ) );

     
}

