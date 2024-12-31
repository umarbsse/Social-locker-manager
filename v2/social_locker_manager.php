<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * Plugin Name: Social Locker Manager
 * Plugin URI: https://www.piratecity.net/
 * Description: This Plugin enable or disable social locker on all post at once
 * Version: 1.0
 * Author: Umer Shahzad
 * Author URI: https://www.piratecity.net/
 * License: GPL2
 */
	require 'simple_html_dom.php';
	register_activation_hook( __FILE__, 'my_plugin_activation' );
	function my_plugin_activation() {
		add_option( "enable_social_locker_manager", "0", '', 'yes' );
	}
	add_action('admin_menu', 'my_plugin_menu');
	function my_plugin_menu() {
      add_menu_page(
         'Social Locker Manager Settings', //Page title 
         'Social Locker Manager', //Menu title 
         'manage_options',  //the user level allowed to access the page.
         'Social-locker-manager', //the slug used for the page in the URL.
         'Social_locker_manager_settings_page',  //the name of the function you will be using to output the content of the page.
         'dashicons-admin-generic' //A url to an image or a Dashicons string.
      );
  	}
   function Social_locker_manager_settings_page() {
      //process_social_locker_manager();
      save_enable_social_locker_manager();
   ?>
   <div class="wrap">
<h2>Staff Details</h2>

<form method="post" action="<?php echo get_admin_url(); ?>admin.php?page=Social-locker-manager">
    <?php //settings_fields( 'my-plugin-settings-group' ); ?>
    <?php //do_settings_sections( 'my-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Enable Social Locker <?php $enable_social_locker_manager = get_option("enable_social_locker_manager"); ?></th>
        <td>
         <input type="radio" name="enable_social_locker_manager" <?php if($enable_social_locker_manager=="1"){echo "checked";} ?> value="1"> Yes<br><br>
         <input type="radio" name="enable_social_locker_manager" <?php if($enable_social_locker_manager=="0"){echo "checked";} ?>  value="0"> No<br>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
   <?php 
   }
   add_action( 'admin_init', 'my_plugin_settings' );

   function my_plugin_settings() {
      register_setting( 'my-plugin-settings-group', 'enable_social_locker_manager' );
   }
   function save_enable_social_locker_manager(){
      if (isset($_POST['enable_social_locker_manager']) && ($_POST['enable_social_locker_manager']=="1" || $_POST['enable_social_locker_manager']=="0")) {
         update_option( 'enable_social_locker_manager', $_POST['enable_social_locker_manager'] );
         process_social_locker_manager();
      }
   }
   function process_social_locker_manager(){
      $enable_social_locker_manager = get_option("enable_social_locker_manager");

     // $slm_header_txt = "<div id='social_locker_manager_block'>";
    //  $slm_footer_txt = "</div>";
      	$slm_header_active_txt = '<div id="social_locker_manager_block">[sociallocker]';
      	$slm_footer_active_txt = '[/sociallocker]</div>';
      	$slm_header_deactive_txt = '<div id="social_locker_manager_block">';
      	$slm_footer_deactive_txt = '</div>';
      if ($enable_social_locker_manager=="1") {
      	$slm_header_txt = $slm_header_active_txt;
      	$slm_footer_txt = $slm_footer_active_txt;
      }else{
      	$slm_header_txt = $slm_header_deactive_txt;
      	$slm_footer_txt = $slm_footer_deactive_txt;
        //echo "disable options";
      }
      $lastposts = get_posts( array(
         'numberposts' => -1,
          'post_type' => 'post',
          'post_status'    => 'publish',
          'orderby' => 'ID',
        'order' => 'DESC'
      ) );
      
       
      if ( $lastposts ) {
         $count=0;
          foreach ( $lastposts as $post ){
          	//echo $post->post_content;
          	//break;
          	$post->post_content = str_replace($slm_header_active_txt, "", $post->post_content);
          	$post->post_content = str_replace($slm_footer_active_txt, "", $post->post_content);
          	$post->post_content = str_replace($slm_header_deactive_txt, "", $post->post_content);
          	$post->post_content = str_replace($slm_footer_deactive_txt, "", $post->post_content);
          	$slm_header = false;
          	$slm_footer = false;
            $array = array(
               '<h3 style="text-align: center;"> <img src="http://i2.wp.com/www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png" ',
               '<h3 style="text-align: center;">&nbsp;<img class="" src="http://i2.wp.com/www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png',
               '<h4 style="text-align: center;"><img src="http://i2.wp.com/www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png',
               '<h3 style="text-align: center;"><span style="color: #ff0000;"><img src="http://i2.wp.com/www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png',
               '<h3 style="text-align: center;"><img class="alignnone size-full wp-image-4436" src="https://i1.wp.com/www.piratecity.net/wp-content/uploads/2015/08/Download-logo.jpg',
               '<h3 style="text-align: center;"><img class="alignnone size-full wp-image-4436" src="https://www.piratecity.net/wp-content/uploads/2015/08/Download-logo.jpg',
               '<h3 style="text-align: center;"><img src="https://i1.wp.com/www.piratecity.net/wp-content/uploads/2015/08/Download-logo.jpg',
               '<h4 style="text-align: center;"><img src="https://i2.wp.com/www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png',
               '<h3 style="text-align: center;"><img class="alignnone size-full wp-image-4436" src="https://i1.wp.com/www.piratecity.net/wp-content/uploads/2015/08/Download-logo.jpg',
               '<h3 style="text-align: center;"><img src="http://i2.wp.com/www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png',
               '<h3 style="text-align: left;"><img src="https://i2.wp.com/www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png',
               '<h3 style="text-align: center;"><span style="color: #ff0000;"><img class="" src="https://i2.wp.com/www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png',
               '<h3 style="text-align: center;"><span style="color: #ff0000;"><img src="https://i2.wp.com/www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png',
               '<h3 style="text-align: center;"><span style="color: #ff0000;"><img class="" src="https://www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png"',
               '<h3 style="text-align: center;">&nbsp;<img src="https://www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png',
               '<p style="text-align: center;"><img src="https://www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png"',
               '<h4 style="text-align: center;"><img src="https://www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png',
               '<h3 style="text-align: center;"><span style="color: #ff0000;"><img src="https://www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png"',
               '<h3 style="text-align: center;"><img class="" src="https://i1.wp.com/www.piratecity.net/wp-content/uploads/2015/08/Download-logo.jpg',
               '<h3 style="text-align: center;"><img src="https://i2.wp.com/www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png',
               '<h3 style="text-align: center;"> <img class="" src="http://i2.wp.com/www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png',
               '<h3 style="text-align: center;"> <img class="" src="http://i2.wp.com/www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png',
               '<h3 style="text-align: center;"><img src="https://www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png"',
               '<h3 style="text-align: center;"><img class="alignnone size-full wp-image-4436" src="https://i1.wp.com/www.piratecity.net/wp-content/uploads/2015/08/Download-logo.jpg',
               '<h3><span style="color: #ff0000;"><img src="https://i2.wp.com/www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png"',
               '<h3 style="text-align: center;"> <img src="https://www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png"',
               '<h3 style="text-align: center;"><img class="" src="https://i2.wp.com/www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png',
               '<h3 style="text-align: center;"><img class="" src="http://i2.wp.com/www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png',
               '<h3><img src="http://i2.wp.com/www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png',
               '<p style="text-align: center;"><img src="https://i2.wp.com/www.piratecity.net/wp-content/uploads/2014/07/Download-logo.png?ssl=1"');
            $tmp = 0;
            for ($i=0; $i <count($array) ; $i++) {
               if (string_find_s_l_m($post->post_content,$array[$i])==true ){
               	$post->post_content = str_replace($array[$i], $slm_header_txt.$array[$i], $post->post_content);
               	$slm_header = true;
               	$tmp++;
               }
            }
            /*if ($tmp>0) {
               if ($tmp>1) {
               	//echo $tmp."<br>";
               }else{
               		$html = str_get_html($post->post_content);
               		//echo '<a href="'.esc_url( get_permalink($post->ID) ).'">'.$post->post_title." ".$post->ID.'</a><br>';
               		//if (string_find_s_l_m($post->post_content,"Tags")==false ){
               		//echo '<a href="'.esc_url( get_permalink($post->ID) ).'">'.$post->post_title." ".$post->ID.'</a><br>';
               		//echo '<br>---------------------------------------------------------------------<br><br><br><br>';
               		//}
               		//echo $html;
               		$para = $html->find('p');
               		//echo count($para)."<br>";
               		foreach($para as $element){
               			//echo '<pre>';print_r($element);echo '</pre>';
               			//echo $element->innertext . '<br>';
               		}
               		//echo '<br>---------------------------------------------------------------------<br><br><br><br>';
               }
            }else{
               //echo '<a href="'.esc_url( get_permalink($post->ID) ).'">'.$post->post_title." ".$post->ID.'</a><br>';
            }
            */
            $array = array(
               '<p><span style="color: #000000;"><strong>Tag',
               '<p><strong>Tag',
               '<strong>Tag',
               '<p style="text-align: left;"><span style="color: #993366;"><strong><span style="color: #000000;">Tags'
           );
            $tmp = 0;
            for ($i=0; $i <count($array) ; $i++) {
               if (string_find_s_l_m($post->post_content,$array[$i])==true ){
               	$post->post_content = str_replace($array[$i], $slm_footer_txt.$array[$i], $post->post_content);
               	$slm_footer = true;
               	$tmp++;
               }
            }
            if ($slm_header == true && $slm_footer == true) {
            	//update_post_slm_function($post->ID, $post->post_content);
            	$count++;
            	echo '<a href="'.esc_url( get_permalink($post->ID) ).'">'.$post->post_title." ".$post->ID.'</a>';
            	echo "<pre>";print_r($post->post_content);echo "</pre>"; 
            	echo '<br>---------------------------------------------------------------------<br><br>';
            	break;
            }else{
               //$count++;
            	//echo '<a href="'.esc_url( get_permalink($post->ID) ).'">'.$post->post_title." ".$post->ID.'</a><br>';
            	//echo "<pre>";print_r($post->post_content);echo "</pre>"; 
            	//echo '<br>---------------------------------------------------------------------<br><br><br><br>';
            }
            //echo "<pre>";print_r($post->post_content);echo "</pre>"; 
          }
            echo "Total Records = ".$count."<br>";
      }
   }
   function string_find_s_l_m($big,$small){
      $haystack = $big;
      $needle = $small;
      return strpos($haystack, $needle) != false;
   }
   function update_post_slm_function($id, $content){
   	$my_post = array('ID'=> $id,'post_content' => $content);
   	wp_update_post( $my_post );
   }
?>