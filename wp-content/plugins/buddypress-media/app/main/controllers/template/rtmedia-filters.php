<?php

/**
 * Creating an album
 *
 * @global      RTMediaQuery    $rtmedia_query
 *
 * @param       array           $options
 *
 * @return      array|void
 */
function rtmedia_create_album( $options ) {

	if ( ! is_rtmedia_album_enable() ) {
		return;
	}

	if ( ! rtm_is_album_create_allowed() ) {
		return;
	}

	global $rtmedia_query;

	$user_id            = get_current_user_id();
	$display            = false;
	$context_type_array = array( 'profile', 'group' );

	if ( isset( $rtmedia_query->query['context'] ) && in_array( $rtmedia_query->query['context'], $context_type_array, true ) && 0 !== $user_id ) {
		switch ( $rtmedia_query->query['context'] ) {
			case 'profile':
				if ( $rtmedia_query->query['context_id'] === $user_id ) {
					$display = rtm_is_user_allowed_to_create_album();
				}

				break;
			case 'group':
				$group_id = $rtmedia_query->query['context_id'];

				if ( can_user_create_album_in_group( $group_id ) ) {
					$display = true;
				}

				break;
		}
	}

	if ( true === $display ) {
		add_action( 'rtmedia_before_media_gallery', 'rtmedia_create_album_modal' );

		$options[] = "<a href='#rtmedia-create-album-modal' class='rtmedia-reveal-modal rtmedia-modal-link'  title='" . esc_attr__( 'Create New Album', 'buddypress-media' ) . "'><i class='dashicons dashicons-plus-alt rtmicon'></i>" . esc_html__( 'Add Album', 'buddypress-media' ) . '</a>';
	}

	return $options;

}

add_filter( 'rtmedia_gallery_actions', 'rtmedia_create_album', 12 );

/**
 * Edit album option
 *
 * @global      RTMediaQuery    $rtmedia_query
 *
 * @param       array           $options
 *
 * @return      array|void
 */
function rtmedia_album_edit( $options ) {

	if ( ! is_rtmedia_album() || ! is_user_logged_in() ) {
		return;
	}

	if ( ! is_rtmedia_album_enable() ) {
		return;
	}

	global $rtmedia_query;

	if ( isset( $rtmedia_query->media_query ) && isset( $rtmedia_query->media_query['album_id'] ) && ! in_array( intval( $rtmedia_query->media_query['album_id'] ), array_map( 'intval', rtmedia_get_site_option( 'rtmedia-global-albums' ) ), true ) ) {
		if ( rtmedia_is_album_editable() || is_rt_admin() ) {
			$options[] = "<a href='edit/' class='rtmedia-edit' title='" . esc_attr__( 'Edit Album', 'buddypress-media' ) . "' ><i class='rtmicon dashicons dashicons-edit'></i>" . esc_html__( 'Edit Album', 'buddypress-media' ) . '</a>';
			$options[] = '<form method="post" class="album-delete-form rtmedia-inline" action="delete/">' . wp_nonce_field( 'rtmedia_delete_album_' . $rtmedia_query->media_query['album_id'], 'rtmedia_delete_album_nonce' ) . '<button type="submit" name="album-delete" class="rtmedia-delete-album" title="' . esc_attr__( 'Delete Album', 'buddypress-media' ) . '"><i class="dashicons dashicons-trash rtmicon"></i>' . esc_html__( 'Delete Album', 'buddypress-media' ) . '</button></form>';

			if ( is_rtmedia_group_album() ) {
				$album_list = rtmedia_group_album_list();
			} else {
				$album_list = rtmedia_user_album_list();
			}

			if ( $album_list ) {
				$options[] = '<a href="#rtmedia-merge" class="rtmedia-reveal-modal rtmedia-modal-link" title="' . esc_attr__( 'Merge Album', 'buddypress-media' ) . '"><i class="dashicons dashicons-randomize"></i>' . esc_html__( 'Merge Album', 'buddypress-media' ) . '</a>';
			}
		}
	}

	return $options;

}

add_filter( 'rtmedia_gallery_actions', 'rtmedia_album_edit', 11 );

/**
 * Add activity type
 *
 * @param       array       $actions
 *
 * @return      array
 */
function rtmedia_bp_activity_get_types( $actions ) {

	$actions['rtmedia_update'] = 'rtMedia update';

	return $actions;

}

add_filter( 'bp_activity_get_types', 'rtmedia_bp_activity_get_types', 10, 1 );

/**
 * Checking if BuddyPress enable
 *
 * @global      RTMediaQuery    $rtmedia_query
 *
 * @param       bool            $flag
 *
 * @return      bool
 */
function rtm_is_buddypress_enable( $flag ) {

	global $rtmedia_query;

	if ( isset( $rtmedia_query->query ) && isset( $rtmedia_query->query['context'] ) && 'group' === $rtmedia_query->query['context'] && is_rtmedia_group_media_enable() ) {
		return $flag;
	} else if ( isset( $rtmedia_query->query ) && isset( $rtmedia_query->query['context'] ) && 'profile' === $rtmedia_query->query['context'] && is_rtmedia_profile_media_enable() ) {
		return $flag;
	}

	return false;

}

add_filter( 'rtm_main_template_buddypress_enable', 'rtm_is_buddypress_enable', 10, 1 );

/**
 * we need to use show title filter when there is a request for template from rtMedia.backbone.js
 *
 * @param       bool    $flag
 *
 * @return      bool
 */
function rtmedia_media_gallery_show_title_template_request( $flag ) {

	if ( isset( $_REQUEST['media_title'] ) && 'false' === $_REQUEST['media_title'] ) {
		return false;
	}

	return $flag;

}

add_filter( 'rtmedia_media_gallery_show_media_title', 'rtmedia_media_gallery_show_title_template_request', 10, 1 );

/**
 * we need to use lightbox filter when there is a request for template from rtMedia.backbone.js
 *
 * @param       string      $class
 *
 * @return      string
 */
function rtmedia_media_gallery_lightbox_template_request( $class ) {

	if ( isset( $_REQUEST['lightbox'] ) && 'false' === $_REQUEST['lightbox'] ) {
		return $class .= ' no-popup';
	}

	return $class;

}

add_filter( 'rtmedia_gallery_list_item_a_class', 'rtmedia_media_gallery_lightbox_template_request', 10, 1 );

/**
 * Fix for BuddyPress multilingual plugin on activity pages
 *
 * @param       array       $params
 *
 * @return      array
 */
function rtmedia_modify_activity_upload_url( $params ) {

	// return original params if BuddyPress multilingual plugin is not active
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	if ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'buddypress-multilingual/sitepress-bp.php' ) ) {
		if ( class_exists( 'BuddyPress' ) ) {
			// change upload url only if it's activity page and if it's group page than it shouldn't group media page
			if ( bp_is_activity_component() || ( bp_is_groups_component() && ! is_rtmedia_page() ) ) {
				if ( function_exists( 'bp_get_activity_directory_permalink' ) ) {
					$params['url'] = bp_get_activity_directory_permalink() . 'upload/';
				}
			}
		}
	}

	return $params;

}

add_filter( 'rtmedia_modify_upload_params', 'rtmedia_modify_activity_upload_url', 999, 1 );

/**
 * WordPress filter to change browser title if theme has title-tag support
 *
 * @global      RTMediaQuery    $rtmedia_query
 *
 * @param       array           $title
 *
 * @return      array
 */
function rtm_modify_document_title_parts( $title = array() ) {

	if ( is_rtmedia_page() ) {
		global $rtmedia_query;

		if ( isset( $rtmedia_query->action_query->media_type ) ) {
			( ! class_exists( 'BuddyPress' ) ) ? array_unshift( $title, ucfirst( $rtmedia_query->action_query->media_type ), RTMEDIA_MEDIA_LABEL ) : array_unshift( $title, ucfirst( $rtmedia_query->action_query->media_type ) );
		} else {
			( ! class_exists( 'BuddyPress' ) ) ? array_unshift( $title, RTMEDIA_MEDIA_LABEL ) : '';
		}
	}

	return $title;

}

add_filter( 'document_title_parts', 'rtm_modify_document_title_parts', 30, 1 );

/**
 * Replace original src with the transcoded media src
 *
 * @param       string      $html
 * @param       object      $rtmedia_media
 *
 * @return      string
 */
function replace_src_with_transcoded_file_url( $html, $rtmedia_media ) {

	if ( empty( $rtmedia_media->media_id ) ) {
		return $html;
	}

	$media_type 	= '';
	$attachment_id 	= $rtmedia_media->media_id;

	if ( 'video' === $rtmedia_media->media_type ) {
		$media_type = 'mp4';
	} elseif ( 'music' === $rtmedia_media->media_type ) {
		$media_type = 'mp3';
	} else {
		return $html;
	}

	$medias = get_post_meta( $attachment_id, '_rt_media_transcoded_files', true );

	if ( $file_url = rtt_is_video_exists( $medias, $media_type ) ) {
		/* for WordPress backward compatibility */
		if ( function_exists( 'wp_get_upload_dir' ) ) {
			$uploads = wp_get_upload_dir();
		} else {
			$uploads = wp_upload_dir();
		}

		if ( 0 === strpos( $file_url, $uploads['baseurl'] ) ) {
			$final_file_url = $file_url;
		} else {
			$final_file_url = $uploads['baseurl'] . '/' . $file_url;
		}

		$final_file_url = apply_filters( 'transcoded_file_url', $final_file_url, $attachment_id );
	} else {
		$final_file_url = wp_get_attachment_url( $attachment_id );
	}

	return preg_replace( "/src=[\"]([^\"]+)[\"]/", "src=\"$final_file_url\"", $html );

}

add_filter( 'rtmedia_single_content_filter', 'replace_src_with_transcoded_file_url', 100, 2 );

/**
 * Replace aws url of image with the wordpress attachment url in buddypress activity
 * @param  string $html
 * @param  object $rtmedia_media
 *
 * @return string
 */
function replace_aws_img_urls_from_activity( $html, $rtmedia_media ) {
	if ( empty( $rtmedia_media ) ) {
		return $html;
	}
	/**
	 * Allow users/plugins to prevent replacing of URL from activty
	 *
	 * @var boolean					Boolean false is passed as a parameter.
	 * @var object $rtmedia_media	Object of rtmedia containing media_id, media_type etc.
	 */
	if ( apply_filters( 'replace_aws_img_urls_from_activity', false, $rtmedia_media ) ) {
		return $html;
	}

	if ( empty( $rtmedia_media->media_id ) || empty( $rtmedia_media->media_type ) ) {
		return $html;
	}

	$media_type 	= $rtmedia_media->media_type;

	if ( 'imgae' === $media_type ) {
		/**
		 * Fix for rtAmazon S3 addon
		 * When rtAmazon S3 is disabled we need to restore/replace the attachment URLS with the
		 * original WordPress URL structure
		 */
		if ( ! class_exists( 'RTAWSS3_Class' ) && ! class_exists( 'AS3CF_Utils' ) ) {
			/* for WordPress backward compatibility */
			if ( function_exists( 'wp_get_upload_dir' ) ) {
				$uploads = wp_get_upload_dir();
			} else {
				$uploads = wp_upload_dir();
			}

			$baseurl = $uploads['baseurl'];

			$search 	= '/^(http|https)(.*)([wp\-content])(\/uploads\/)/i';
			$replace 	= $baseurl . '/';

			$thumbnail_url = preg_replace( $search, $replace, $thumbnail_id );
			if ( ! empty( $thumbnail_url ) ) {
				$html = preg_replace( "/src=[\"]([^\"]+)[\"]/", "src=\"$thumbnail_url\"", $html );
			}
		}
	}
	return $html;
}

add_filter( 'rtmedia_single_content_filter', 'replace_aws_img_urls_from_activity', 100, 2 );

/**
 * Add the notice when file is sent for the transcoding and adds the poster thumbnail if poster tag is empty
 *
 * @since 1.0.1
 *
 * @param  string $content  HTML contents of the activity
 * @param  object $activity Activity object
 *
 * @return string
 */
function replace_aws_img_urls_from_activities( $content, $activity = '' ) {

	if ( empty( $content ) || empty( $activity ) ) {
		return $content;
	}

	/**
	 * Allow users/plugins to prevent replacing of URL from activty
	 *
	 * @var boolean					Boolean false is passed as a parameter.
	 * @var object $activity		Object of activity.
	 */
	if ( apply_filters( 'replace_aws_img_urls_from_activity', false, $activity ) ) {
		return $content;
	}

	$rt_model  = new RTMediaModel();
	$all_media = $rt_model->get( array( 'activity_id' => $activity->id ) );

	$is_img 	= false;
	$url 		= '';
	$is_img 	= strpos( $content , '<img ' );

	$search 	= "/<img.+src=[\"]([^\"]+)[\"]/";
	preg_match_all( $search , $content, $url );

	if ( ! empty( $is_img ) && ! empty( $url ) && ! empty( $url[1] ) ) {
		/**
		 * Fix for rtAmazon S3 addon
		 * When rtAmazon S3 is disabled we need to restore/replace the attachment URLS with the
		 * original WordPress URL structure
		 */
		foreach ( $url[1] as $key => $url ) {
			if ( ! class_exists( 'RTAWSS3_Class' ) && ! class_exists( 'AS3CF_Utils' ) ) {
				/* for WordPress backward compatibility */
				if ( function_exists( 'wp_get_upload_dir' ) ) {
					$uploads = wp_get_upload_dir();
				} else {
					$uploads = wp_upload_dir();
				}

				$baseurl = $uploads['baseurl'];

				$search 	= "/^(http|https)(.*)([wp\-content])(\/uploads\/)/i";
				$replace 	= $baseurl . '/';

				$thumbnail_url = preg_replace( $search, $replace, $url );
				if ( ! empty( $thumbnail_url ) ) {
					$content = str_replace( $url, $thumbnail_url, $content );
				}
			} else {
				/**
				 * Sometimes there's no attachment ID for the URL assigned, so we pass MD5 hash of the URL as a attachment ID
				 */
				$attachment_id = md5( $url );
				if ( ! empty( $all_media ) && ! empty( $all_media[0]->media_id ) ) {
					$attachment_id 	= $all_media[0]->media_id;
				}
				$image_url = apply_filters( 'rtmedia_filtered_photo_url', $url, $attachment_id );
				$content = str_replace( $url, $image_url, $content );
			}
		}

	}
	return $content;
}

add_filter( 'bp_get_activity_content_body', 'replace_aws_img_urls_from_activities', 99, 2 );


/**
 * Gives the WordPress's default attachment URL if the base URL of the attachment is
 * different than the WordPress's default base URL. e.g following URL
 * https://s3.amazonaws.com/bucket-name/wp-content/uploads/2016/09/attachment.jpg
 * will get replaced with
 * http://www.wordpress-base.url/wp-content/uploads/2016/09/1473432502-small-10-1-16_1.jpg
 *
 * @param       int         $thumbnail_id       It can be attachment URL or attachment ID
 * @param       string 	    $media_type   	    Media type
 * @param       int 		$media_id     	    Attachment ID
 *
 * @return      string 		Attachment URL if attachment URL is provided in the argument
 */
function rtt_restore_og_wp_image_url( $thumbnail_id, $media_type, $media_id ) {

	if ( is_numeric( $thumbnail_id ) ) {
		return $thumbnail_id;
	}

	/**
	 * Fix for rtAmazon S3 addon
	 * When rtAmazon S3 is disabled we need to restore/replace the attachment URLS with the
	 * original WordPress URL structure
	 */
	if ( ! class_exists( 'RTAWSS3_Class' ) && ! class_exists( 'AS3CF_Utils' ) ) {
		/* for WordPress backward compatibility */
		if ( function_exists( 'wp_get_upload_dir' ) ) {
			$uploads = wp_get_upload_dir();
		} else {
			$uploads = wp_upload_dir();
		}

		$baseurl       = $uploads['baseurl'];
		$search        = '/^(http|https)(.*)([wp\-content])(\/)(uploads\/)/i';
		$replace       = $baseurl . '/';
		$thumbnail_url = preg_replace( $search, $replace, $thumbnail_id );

		if ( ! empty( $thumbnail_url ) ) {
			$thumbnail_id = $thumbnail_url;
		}
	}

	/**
	 * Apply filter to get amazon s3 URL
	 */
	$final_file_url = apply_filters( 'transcoded_file_url', $thumbnail_id, $media_id );

	return $final_file_url;

}

add_filter( 'show_custom_album_cover', 'rtt_restore_og_wp_image_url', 100, 3 );
