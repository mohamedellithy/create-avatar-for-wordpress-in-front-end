<?php 
 /**
  ** here code to create upload wordpress user avatar in front-end
  **/   
   
   global $wpdb;
  
  /* if user is logged in */
  global $current_user;
  $img_url = upload_image_by_user_in_form($_FILES['student_avatar']);
  $avtar_id = upload_avatar($img_url);
  update_user_meta($current_user->ID, $wpdb->get_blog_prefix() . 'user_avatars', ['media_id'=>$avtar_id,'site_id'=>1,'full'=>$img_url ] );

  
   function upload_image_by_user_in_form($upload_avatar_from_field){
        if ( ! function_exists( 'wp_handle_upload' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
        } 
        $uploadedfile = upload_avatar_from_field;

        $upload_overrides = array( 'test_form' => false );
       
        $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

        if ( $movefile && ! isset( $movefile['error'] ) ) { 

           return  $movefile['url'];
        } 

    }


    /**
     **  
    **/

    function upload_avatar($upolad_image){
        $file     = $upolad_image;
        $filename = basename($file);

        $upload_file = wp_upload_bits($filename, null, file_get_contents($file));
        if (!$upload_file['error']) {
          $wp_filetype = wp_check_filetype($filename, null );
          $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_parent' => $parent_post_id,
            'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
            'post_content' => '',
            'post_status' => 'inherit'
          );
          $attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $parent_post_id );
          if (!is_wp_error($attachment_id)) {
            require_once(ABSPATH . "wp-admin" . '/includes/image.php');
            $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
            wp_update_attachment_metadata( $attachment_id,  $attachment_data );
          }
          
          return $attachment_id;
        }
    }



     
            



?>