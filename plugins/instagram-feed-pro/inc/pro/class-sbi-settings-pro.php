<?php
/**
 * Class SB_Instagram_Settings_Pro
 *
 * @since 5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class SB_Instagram_Settings_Pro extends SB_Instagram_Settings{

	private $business_accounts;

	/**
	 * SB_Instagram_Settings constructor.
	 *
	 * @param $atts
	 * @param $db
	 */
	public function __construct( $atts, $db ) {
		$this->atts = $atts;
		$this->db   = $db;

		$connected_accounts = isset( $db['connected_accounts'] ) ? $db['connected_accounts'] : array();

		//Create the includes string to set as shortcode default
		$hover_include_string = '';
		if( isset($db[ 'sbi_hover_inc_username' ]) ){
			($db[ 'sbi_hover_inc_username' ] && $db[ 'sbi_hover_inc_username' ] !== '') ? $hover_include_string .= 'username,' : $hover_include_string .= '';
		}
		//If the username option doesn't exist in the database yet (eg: on plugin update) then set it to be displayed
		if ( !array_key_exists( 'sbi_hover_inc_username', $db ) ) $hover_include_string .= 'username,';

		if( isset($db[ 'sbi_hover_inc_icon' ]) ){
			($db[ 'sbi_hover_inc_icon' ] && $db[ 'sbi_hover_inc_icon' ] !== '') ? $hover_include_string .= 'icon,' : $hover_include_string .= '';
		}
		if ( !array_key_exists( 'sbi_hover_inc_icon', $db ) ) $hover_include_string .= 'icon,';

		if( isset($db[ 'sbi_hover_inc_date' ]) ){
			($db[ 'sbi_hover_inc_date' ] && $db[ 'sbi_hover_inc_date' ] !== '') ? $hover_include_string .= 'date,' : $hover_include_string .= '';
		}
		if ( !array_key_exists( 'sbi_hover_inc_date', $db ) ) $hover_include_string .= 'date,';

		if( isset($db[ 'sbi_hover_inc_instagram' ]) ){
			($db[ 'sbi_hover_inc_instagram' ] && $db[ 'sbi_hover_inc_instagram' ] !== '') ? $hover_include_string .= 'instagram,' : $hover_include_string .= '';
		}
		if ( !array_key_exists( 'sbi_hover_inc_instagram', $db ) ) $hover_include_string .= 'instagram,';

		if( isset($db[ 'sbi_hover_inc_location' ]) ){
			($db[ 'sbi_hover_inc_location' ] && $db[ 'sbi_hover_inc_location' ] !== '') ? $hover_include_string .= 'location,' : $hover_include_string .= '';
		}
		if( isset($db[ 'sbi_hover_inc_caption' ]) ){
			($db[ 'sbi_hover_inc_caption' ] && $db[ 'sbi_hover_inc_caption' ] !== '') ? $hover_include_string .= 'caption,' : $hover_include_string .= '';
		}
		if( isset($db[ 'sbi_hover_inc_likes' ]) ){
			($db[ 'sbi_hover_inc_likes' ] && $db[ 'sbi_hover_inc_likes' ] !== '') ? $hover_include_string .= 'likes,' : $hover_include_string .= '';
		}
		if ( isset( $db[ 'sb_instagram_incex_one_all' ] ) ) {
			if ( $db[ 'sb_instagram_incex_one_all' ]  == 'one' ) {
				$db[ 'sb_instagram_include_words' ] = '';
				$db[ 'sb_instagram_exclude_words' ] = '';
			}
		}

		$this->settings = shortcode_atts(
			array(
				//Feed general
				'type' => isset($db[ 'sb_instagram_type' ]) ? $db[ 'sb_instagram_type' ] : 'user',
				'order' => isset($db[ 'sb_instagram_order' ]) ? $db[ 'sb_instagram_order' ] : '',
				'id' => isset($db[ 'sb_instagram_user_id' ]) ? $db[ 'sb_instagram_user_id' ] : '',
				'hashtag' => isset($db[ 'sb_instagram_hashtag' ]) ? $db[ 'sb_instagram_hashtag' ] : '',
				'tagged' => isset($db[ 'sb_instagram_tagged_ids' ]) ? $db[ 'sb_instagram_tagged_ids' ] : '',
				'location' => isset($db[ 'sb_instagram_location' ]) ? $db[ 'sb_instagram_location' ] : '',
				'coordinates' => isset($db[ 'sb_instagram_coordinates' ]) ? $db[ 'sb_instagram_coordinates' ] : '',
				'single' => '',
				'width' => isset($db[ 'sb_instagram_width' ]) ? $db[ 'sb_instagram_width' ] : '',
				'widthunit' => isset($db[ 'sb_instagram_width_unit' ]) ? $db[ 'sb_instagram_width_unit' ] : '',
				'widthresp' => isset($db[ 'sb_instagram_feed_width_resp' ]) ? $db[ 'sb_instagram_feed_width_resp' ] : '',
				'height' => isset($db[ 'sb_instagram_height' ]) ? $db[ 'sb_instagram_height' ] : '',
				'heightunit' => isset($db[ 'sb_instagram_height_unit' ]) ? $db[ 'sb_instagram_height_unit' ] : '',
				'sortby' => isset($db[ 'sb_instagram_sort' ]) ? $db[ 'sb_instagram_sort' ] : '',
				'disablelightbox' => isset($db[ 'sb_instagram_disable_lightbox' ]) ? $db[ 'sb_instagram_disable_lightbox' ] : '',
				'captionlinks' => isset($db[ 'sb_instagram_captionlinks' ]) ? $db[ 'sb_instagram_captionlinks' ] : '',
				'offset' => isset($db[ 'sb_instagram_offset' ]) ? $db[ 'sb_instagram_offset' ] : '',
				'num' => isset($db[ 'sb_instagram_num' ]) ? $db[ 'sb_instagram_num' ] : '',
				'apinum'           => isset( $db['sb_instagram_minnum'] ) ? $db['sb_instagram_minnum'] : '',
				'nummobile' => isset($db[ 'sb_instagram_nummobile' ]) ? $db[ 'sb_instagram_nummobile' ] : '',
				'cols' => isset($db[ 'sb_instagram_cols' ]) ? $db[ 'sb_instagram_cols' ] : '',
				'colsmobile' => isset($db[ 'sb_instagram_colsmobile' ]) ? $db[ 'sb_instagram_colsmobile' ] : '',
				'disablemobile' => isset($db[ 'sb_instagram_disable_mobile' ]) ? $db[ 'sb_instagram_disable_mobile' ] : '',
				'imagepadding' => isset($db[ 'sb_instagram_image_padding' ]) ? $db[ 'sb_instagram_image_padding' ] : '',
				'imagepaddingunit' => isset($db[ 'sb_instagram_image_padding_unit' ]) ? $db[ 'sb_instagram_image_padding_unit' ] : '',
				'layout' => isset($db[ 'sb_instagram_layout_type' ]) ? $db[ 'sb_instagram_layout_type' ] : 'grid',

				//Lightbox comments
				'lightboxcomments' => isset($db[ 'sb_instagram_lightbox_comments' ]) ? $db[ 'sb_instagram_lightbox_comments' ] : '',
				'numcomments' => isset($db[ 'sb_instagram_num_comments' ]) ? $db[ 'sb_instagram_num_comments' ] : '',

				//Photo hover styles
				'hovereffect' => isset($db[ 'sb_instagram_hover_effect' ]) ? $db[ 'sb_instagram_hover_effect' ] : '',
				'hovercolor' => isset($db[ 'sb_hover_background' ]) ? $db[ 'sb_hover_background' ] : '',
				'hovertextcolor' => isset($db[ 'sb_hover_text' ]) ? $db[ 'sb_hover_text' ] : '',
				'hoverdisplay' => $hover_include_string,

				//Item misc
				'background' => isset($db[ 'sb_instagram_background' ]) ? $db[ 'sb_instagram_background' ] : '',
				'imageres' => isset($db[ 'sb_instagram_image_res' ]) ? $db[ 'sb_instagram_image_res' ] : '',
				'media' => isset($db[ 'sb_instagram_media_type' ]) ? $db[ 'sb_instagram_media_type' ] : '',
				'showcaption' => isset($db[ 'sb_instagram_show_caption' ]) ? $db[ 'sb_instagram_show_caption' ] : '',
				'captionlength' => isset($db[ 'sb_instagram_caption_length' ]) ? $db[ 'sb_instagram_caption_length' ] : '',
				'captioncolor' => isset($db[ 'sb_instagram_caption_color' ]) ? $db[ 'sb_instagram_caption_color' ] : '',
				'captionsize' => isset($db[ 'sb_instagram_caption_size' ]) ? $db[ 'sb_instagram_caption_size' ] : '',
				'showlikes' => isset($db[ 'sb_instagram_show_meta' ]) ? $db[ 'sb_instagram_show_meta' ] : '',
				'likescolor' => isset($db[ 'sb_instagram_meta_color' ]) ? $db[ 'sb_instagram_meta_color' ] : '',
				'likessize' => isset($db[ 'sb_instagram_meta_size' ]) ? $db[ 'sb_instagram_meta_size' ] : '',
				'hidephotos' => isset($db[ 'sb_instagram_hide_photos' ]) ? $db[ 'sb_instagram_hide_photos' ] : '',

				//Footer
				'showbutton' => isset($db[ 'sb_instagram_show_btn' ]) ? $db[ 'sb_instagram_show_btn' ] : '',
				'buttoncolor' => isset($db[ 'sb_instagram_btn_background' ]) ? $db[ 'sb_instagram_btn_background' ] : '',
				'buttontextcolor' => isset($db[ 'sb_instagram_btn_text_color' ]) ? $db[ 'sb_instagram_btn_text_color' ] : '',
				'buttontext' => isset($db[ 'sb_instagram_btn_text' ]) ? stripslashes( esc_attr( $db[ 'sb_instagram_btn_text' ] ) ) : '',
				'showfollow' => isset($db[ 'sb_instagram_show_follow_btn' ]) ? $db[ 'sb_instagram_show_follow_btn' ] : '',
				'followcolor' => isset($db[ 'sb_instagram_folow_btn_background' ]) ? $db[ 'sb_instagram_folow_btn_background' ] : '',
				'followtextcolor' => isset($db[ 'sb_instagram_follow_btn_text_color' ]) ? $db[ 'sb_instagram_follow_btn_text_color' ] : '',
				'followtext' => isset($db[ 'sb_instagram_follow_btn_text' ]) ? stripslashes( esc_attr( $db[ 'sb_instagram_follow_btn_text' ] ) ) : '',

				//Header
				'showheader' => isset($db[ 'sb_instagram_show_header' ]) ? $db[ 'sb_instagram_show_header' ] : '',
				'headercolor' => isset($db[ 'sb_instagram_header_color' ]) ? $db[ 'sb_instagram_header_color' ] : '',
				'headerstyle' => isset($db[ 'sb_instagram_header_style' ]) ? $db[ 'sb_instagram_header_style' ] : '',
				'showfollowers' => isset($db[ 'sb_instagram_show_followers' ]) ? $db[ 'sb_instagram_show_followers' ] : '',
				'showbio' => isset($db[ 'sb_instagram_show_bio' ]) ? $db[ 'sb_instagram_show_bio' ] : '',
				'custombio' => isset($db[ 'sb_instagram_custom_bio' ]) ? $db[ 'sb_instagram_custom_bio' ] : '',
				'customavatar' => isset($db[ 'sb_instagram_custom_avatar' ]) ? $db[ 'sb_instagram_custom_avatar' ] : '',
				'headerprimarycolor' => isset($db[ 'sb_instagram_header_primary_color' ]) ? $db[ 'sb_instagram_header_primary_color' ] : '',
				'headersecondarycolor' => isset($db[ 'sb_instagram_header_secondary_color' ]) ? $db[ 'sb_instagram_header_secondary_color' ] : '',
				'headersize' => isset($db[ 'sb_instagram_header_size' ]) ? $db[ 'sb_instagram_header_size' ] : '',
				'stories' => isset($db[ 'sb_instagram_stories' ]) ? $db[ 'sb_instagram_stories' ] : '',
				'storiestime' => isset($db[ 'sb_instagram_stories_time' ]) ? $db[ 'sb_instagram_stories_time' ] : '',
				'headeroutside' => isset($db[ 'sb_instagram_outside_scrollable' ]) ? $db[ 'sb_instagram_outside_scrollable' ] : '',

				'class' => '',
				'ajaxtheme' => isset($db[ 'sb_instagram_ajax_theme' ]) ? $db[ 'sb_instagram_ajax_theme' ] : '',
				'cachetime' => isset($db[ 'sb_instagram_cache_time' ]) ? $db[ 'sb_instagram_cache_time' ] : '',
				'blockusers' => isset($db[ 'sb_instagram_block_users' ]) ? $db[ 'sb_instagram_block_users' ] : '',
				'showusers' => isset($db[ 'sb_instagram_show_users' ]) ? $db[ 'sb_instagram_show_users' ] : '',
				'excludewords' => isset($db[ 'sb_instagram_exclude_words' ]) ? $db[ 'sb_instagram_exclude_words' ] : '',
				'includewords' => isset($db[ 'sb_instagram_include_words' ]) ? $db[ 'sb_instagram_include_words' ] : '',
				'maxrequests' => isset($db[ 'sb_instagram_requests_max' ]) ? $db[ 'sb_instagram_requests_max' ] : '',

				//Carousel
				'carousel' => isset($db[ 'sb_instagram_carousel' ]) ? $db[ 'sb_instagram_carousel' ] : '',
				'carouselrows' => isset($db[ 'sb_instagram_carousel_rows' ]) ? $db[ 'sb_instagram_carousel_rows' ] : '',
				'carouselloop' => isset($db[ 'sb_instagram_carousel_loop' ]) ? $db[ 'sb_instagram_carousel_loop' ] : '',
				'carouselarrows' => isset($db[ 'sb_instagram_carousel_arrows' ]) ? $db[ 'sb_instagram_carousel_arrows' ] : '',
				'carouselpag' => isset($db[ 'sb_instagram_carousel_pag' ]) ? $db[ 'sb_instagram_carousel_pag' ] : '',
				'carouselautoplay' => isset($db[ 'sb_instagram_carousel_autoplay' ]) ? $db[ 'sb_instagram_carousel_autoplay' ] : '',
				'carouseltime' => isset($db[ 'sb_instagram_carousel_interval' ]) ? $db[ 'sb_instagram_carousel_interval' ] : '',

				//Highlight
				'highlighttype' => isset($db[ 'sb_instagram_highlight_type' ]) ? $db[ 'sb_instagram_highlight_type' ] : '',
				'highlightoffset' => isset($db[ 'sb_instagram_highlight_offset' ]) ? $db[ 'sb_instagram_highlight_offset' ] : '',
				'highlightpattern' => isset($db[ 'sb_instagram_highlight_factor' ]) ? $db[ 'sb_instagram_highlight_factor' ] : '',
				'highlighthashtag' => isset($db[ 'sb_instagram_highlight_hashtag' ]) ? $db[ 'sb_instagram_highlight_hashtag' ] : '',
				'highlightids' => isset($db[ 'sb_instagram_highlight_ids' ]) ? $db[ 'sb_instagram_highlight_ids' ] : '',

				//WhiteList
				'whitelist' => '',

				//Load More on Scroll
				'autoscroll' => isset($db[ 'sb_instagram_autoscroll' ]) ? $db[ 'sb_instagram_autoscroll' ] : '',
				'autoscrolldistance' => isset($db[ 'sb_instagram_autoscrolldistance' ]) ? $db[ 'sb_instagram_autoscrolldistance' ] : '',

				//Moderation Mode
				'moderationmode' => isset($db[ 'sb_instagram_moderation_mode' ]) ? $db[ 'sb_instagram_moderation_mode' ] === 'visual' : '',

				//Permanent
				'permanent' => isset($db[ 'sb_instagram_permanent' ]) ? $db[ 'sb_instagram_permanent' ] : false,
				'accesstoken' => '',
				'user' => isset($db[ 'sb_instagram_user_id' ]) ? $db[ 'sb_instagram_user_id' ] : false,

				//Misc
				'feedid' => isset($db[ 'sb_instagram_feed_id' ]) ? $db[ 'sb_instagram_feed_id' ] : false,

				'resizeprocess'    => isset( $db['sb_instagram_resizeprocess'] ) ? $db['sb_instagram_resizeprocess'] : 'background',
				'mediavine'    => isset( $db['sb_instagram_media_vine'] ) ? $db['sb_instagram_media_vine'] : '',
				'customtemplates'    => isset( $db['custom_template'] ) ? $db['custom_template'] : '',

			), $atts );
		$this->settings['customtemplates'] = $this->settings['customtemplates'] === 'true' || $this->settings['customtemplates'] === 'on';
		if ( isset( $_GET['sbi_debug'] ) ) {
			$this->settings['customtemplates'] = false;
		}
		$this->settings['showbio'] = $this->settings['showbio'] === 'true' || $this->settings['showbio'] === 'on' || $this->settings['showbio'] === true;
		if ( isset( $atts['showbio'] ) && $atts['showbio'] === 'false' ) {
			$this->settings['showbio'] = false;
		}
		// allow the use of "user=" for tagged type as well
		if ( $this->settings['type'] === 'tagged'
		     && empty( $atts['tagged'] )
		     && ! empty( $atts['user'] ) ) {
			$this->settings['tagged'] = $atts['user'];
		}
		$this->settings['num'] = max( (int)$this->settings['num'], 0);
		$this->settings['minnum'] = max( (int)$this->settings['num'], (int)$this->settings['nummobile'] );
		if ( $this->settings['sortby'] === 'likes' ) {
			$this->settings['apinum'] = 200;
		}

		$this->settings['disable_resize'] = isset( $db['sb_instagram_disable_resize'] ) && ($db['sb_instagram_disable_resize'] === 'on');
		$this->settings['favor_local'] = ! isset( $db['sb_instagram_favor_local'] ) || ($db['sb_instagram_favor_local'] === 'on') || ($db['sb_instagram_favor_local'] === true);
		$this->settings['backup_cache_enabled'] = ! isset( $db['sb_instagram_backup'] ) || ($db['sb_instagram_backup'] === 'on') || $db['sb_instagram_backup'] === true;
		$this->settings['font_method'] = isset( $db['sbi_font_method'] ) ? $db['sbi_font_method'] : 'svg';
		$this->settings['disable_js_image_loading'] = isset( $db['disable_js_image_loading'] ) && ($db['disable_js_image_loading'] === 'on');

		switch ( $db['sbi_cache_cron_interval'] ) {
			case '30mins' :
				$this->settings['sbi_cache_cron_interval'] = 60*30;
				break;
			case '1hour' :
				$this->settings['sbi_cache_cron_interval'] = 60*60;
				break;
			default :
				$this->settings['sbi_cache_cron_interval'] = 60*60*12;
		}
		$this->settings['sb_instagram_cache_time'] = isset( $this->db['sb_instagram_cache_time'] ) ? $this->db['sb_instagram_cache_time'] : 1;
		$this->settings['sb_instagram_cache_time_unit'] = isset( $this->db['sb_instagram_cache_time_unit'] ) ? $this->db['sb_instagram_cache_time_unit'] : 'hours';

		$this->settings['stories'] = (($this->settings['stories'] === '' && ! isset( $db['sb_instagram_stories'])) || $this->settings['stories'] === true || $this->settings['stories'] === 'on' || $this->settings['stories'] === 'true') && $this->settings['stories'] !== 'false';

		$this->settings['addModerationModeLink'] = ($this->settings['moderationmode'] === true || $this->settings['moderationmode'] === 'on' || $this->settings['moderationmode'] === 'true') && current_user_can('edit_posts' );

		$moderation_mode = isset ( $atts['doingModerationMode'] );
		if ( $moderation_mode ) {
			$this->settings['cols'] = 5;
			$this->settings['colsmobile'] = 3;

			$this->settings['num'] = 50;
			$this->settings['apinum'] = 50;
			$this->settings['minnum'] = 50;
			$this->settings['nummobile'] = 50;

			$this->settings['lightboxcomments'] = false;
			$this->settings['showlikes'] = false;
			$this->settings['showcaption'] = false;
			$this->settings['showheader'] = true;
			$this->settings['showbutton'] = true;
			$this->settings['showfollow'] = false;
			$this->settings['disablelightbox'] = true;
			$this->settings['sortby'] = 'none';
			$this->settings['doingModerationMode'] = true;
			$this->settings['offset'] = 0;
		}

		$this->settings['caching_type'] = $db['sbi_caching_type'];

		$feed_is_permanent = isset( $atts['permanent'] ) ? ($atts['permanent'] === 'true') : false;
		$white_list_is_permanent = false;
		if ( ! empty( $this->settings['whitelist'] ) ) {
			$permanent_white_lists = get_option( 'sb_permanent_white_lists', array() );
			if ( in_array( $this->settings['whitelist'], $permanent_white_lists, true ) ) {
				$white_list_is_permanent = true;
			}

			$this->settings['whitelist_ids'] = get_option( 'sb_instagram_white_lists_'.$this->settings['whitelist'], array() );
			$this->settings['whitelist_num'] = count( $this->settings['whitelist_ids'] );
		}

		if ( $feed_is_permanent || $white_list_is_permanent ) {
			$this->settings['backup_cache_enabled'] = true;
			$this->settings['alwaysUseBackup'] = true;
			$this->settings['caching_type'] = 'permanent';
		} else {
			global $sb_instagram_posts_manager;
			if ( $sb_instagram_posts_manager->are_current_api_request_delays() ) {
				$this->settings['alwaysUseBackup'] = true;
				$this->settings['caching_type'] = 'permanent';
			}
		}

		$this->settings['headeroutside'] = ($this->settings['headeroutside'] === true || $this->settings['headeroutside'] === 'on' || $this->settings['headeroutside'] === 'true');
		if ( $this->settings['showheader'] === 'false' ) {
			$this->settings['showheader'] = false;
		}

		if ( empty( $atts['layout'] ) && isset( $atts['carousel'] ) && $atts['carousel'] === 'true' ) {
			$this->settings['layout'] = 'carousel';
		}

		if ( $this->settings['layout'] === 'carousel'
		     && $this->settings['num'] <= $this->settings['cols'] ) {
			$this->settings['num'] = 2 * (int)$this->settings['cols'];
			$this->settings['minnum'] = 2 * (int)$this->settings['cols'];
		}

		$this->settings['ajax_post_load'] = isset( $db['sb_ajax_initial'] ) && ($db['sb_ajax_initial'] === 'on');

		$this->settings['isgutenberg'] = SB_Instagram_Blocks::is_gb_editor();
		if ( $this->settings['isgutenberg'] ) {
			$this->settings['ajax_post_load'] = false;
			$this->settings['disable_js_image_loading'] = true;
		}

		if ( (int)$this->settings['offset'] > 0 ) {
			$num = max( (int)$this->settings['minnum'], (int)$this->settings['apinum'] );
			$this->settings['apinum'] = $num + (int)$this->settings['offset'];
		}

		$this->connected_accounts = apply_filters( 'sbi_connected_accounts', $connected_accounts, $this->atts );

		$this->settings = apply_filters( 'sbi_feed_settings', $this->settings, $this->connected_accounts );
	}

	/**
	 * Sets the feed ID used to identify which posts to retrieve from the
	 * database among other important features. Uses a combination of the
	 * feed type, feed display settings, moderation settings, number
	 * settings, and post order. Can be set manually if two similar feeds
	 * share the same name and are causing conflicts.
	 *
	 * Pro - More factors used to create name (see above)
	 *
	 * @param string $transient_name
	 *
	 * @since 5.0
	 * @since 5.2 support for db query feed id setting, tagged
	 */
	public function set_transient_name( $transient_name = '' ) {

		if ( ! empty( $transient_name ) ) {
			$this->transient_name = $transient_name;
		} elseif ( ! empty( $this->settings['feedid'] ) ) {
			$this->transient_name = 'sbi_' . $this->settings['feedid'];
		} else {
			$feed_type_and_terms = $this->feed_type_and_terms;

			$sb_instagram_include_words = $this->settings['includewords'];
			$sb_instagram_exclude_words = $this->settings['excludewords'];
			$sbi_cache_string_include = '';
			$sbi_cache_string_exclude = '';

			//Convert include words array into a string consisting of 3 chars each
			if ( ! empty( $sb_instagram_include_words ) ) {
				$sb_instagram_include_words_arr = explode(',', $sb_instagram_include_words);

				foreach( $sb_instagram_include_words_arr as $sbi_word ){
					$sbi_include_word = str_replace( str_split(' #'), '', $sbi_word );
					$sbi_cache_string_include .= substr( str_replace('%','', urlencode( $sbi_include_word ) ), 0, 3 );
				}

			}

			//Convert exclude words array into a string consisting of 3 chars each
			if ( ! empty( $sb_instagram_exclude_words ) ) {
				$sb_instagram_exclude_words_arr = explode( ',', $sb_instagram_exclude_words );

				foreach( $sb_instagram_exclude_words_arr as $sbi_word ){
					$sbi_exclude_word = str_replace( str_split( ' #' ) , '', $sbi_word );
					$sbi_cache_string_exclude .= substr( str_replace( '%','', urlencode( $sbi_exclude_word ) ), 0, 3 );
				}

			}

			//Figure out how long the first part of the caching string should be
			$sbi_cache_string_include_length = strlen( $sbi_cache_string_include );
			$sbi_cache_string_exclude_length = strlen( $sbi_cache_string_exclude );
			$sbi_cache_string_length = 40 - min( $sbi_cache_string_include_length + $sbi_cache_string_exclude_length, 20 );

			isset( $this->settings[ 'whitelist' ] ) ? $sb_instagram_white_list = trim( $this->settings['whitelist'] ) : $sb_instagram_white_list = '';
			$sbi_transient_name = 'sbi_';
			$sbi_transient_name .= substr( $sb_instagram_white_list, 0, 3 );

			if ( $this->settings['media'] !== 'all' ) {
				$sbi_transient_name .= substr( $this->settings['media'], 0, 1 );
			}

			if ( $this->settings['sortby'] === 'likes' ) {
				$sbi_transient_name .= 'lsrt';
			}

			if ( isset( $feed_type_and_terms['users'] ) ) {
				foreach ( $feed_type_and_terms['users'] as $term_and_params ) {
					$user = $term_and_params['term'];
					$connected_account = $this->connected_accounts_in_feed[ $user ];
					if ( isset( $connected_account['type'] ) && $connected_account['type'] === 'business' ) {
						$sbi_transient_name .= $connected_account['username'];
					} else {
						$sbi_transient_name .= $user;
					}
				}
			}

			if ( isset( $feed_type_and_terms['hashtags_top'] ) || isset( $feed_type_and_terms['hashtags_recent'] ) ) {
				if ( isset( $feed_type_and_terms['hashtags_recent'] ) ) {
					$terms_params = $feed_type_and_terms['hashtags_recent'];
				} else {
					$terms_params = $feed_type_and_terms['hashtags_top'];
					$sbi_transient_name .= '+';
				}

				foreach ( $terms_params as $term_and_params ) {
					$hashtag = $term_and_params['hashtag_name'];
					$full_tag = str_replace('%','',urlencode( $hashtag ));
					$max_length = strlen( $full_tag ) < 20 ? strlen( $full_tag ) : 20;
					$sbi_transient_name .= strtoupper( substr( $full_tag, 0, $max_length ) );
				}
			}

			if ( isset( $feed_type_and_terms['tagged'] ) ) {
				$sbi_transient_name .= SBI_TAGGED_PREFIX;

				foreach ( $feed_type_and_terms['tagged'] as $term_and_params ) {
					$user = $term_and_params['term'];
					$connected_account = $this->connected_accounts_in_feed[ $user ];
					if ( isset( $connected_account['type'] ) && $connected_account['type'] === 'business' ) {
						$sbi_transient_name .= $connected_account['username'];
					} else {
						$sbi_transient_name .= $user;
					}
				}
			}

			$num = $this->settings['num'];

			if ( (int)$this->settings['offset'] > 0 ) {
				$num = $num . 'o' . (int)$this->settings['offset'];
			}

			$num_length = strlen( $num ) + 1;

			// add filter prefix and suffixes substr( $sb_instagram_white_list, 0, 3 )

			//Add both parts of the caching string together and make sure it doesn't exceed 45
			$this->settings['db_query_feed_id'] = substr( $sbi_transient_name, 0, $sbi_cache_string_length ) . $sbi_cache_string_include . $sbi_cache_string_exclude;

			$sbi_transient_name = substr( $sbi_transient_name, 0, $sbi_cache_string_length - $num_length ) . $sbi_cache_string_include . $sbi_cache_string_exclude;

			if ( isset( $feed_type_and_terms['hashtags_recent'] ) && isset( $this->settings['cache_all'] ) && $this->settings['cache_all'] ) {
				$existing_posts = SB_Instagram_Feed_Pro::get_post_set_from_db( $sbi_transient_name, 0, time(), 1 );
				if ( isset( $existing_posts[0] ) ) {
					if ( isset( $feed_type_and_terms['hashtags_top'] ) ) {
						unset( $feed_type_and_terms['hashtags_top'] );
						$this->feed_type_and_terms = $feed_type_and_terms;
					}
				}

			}

			if ( ! isset( $this->settings['doingModerationMode'] ) ) {
				$sbi_transient_name .= '#' . $num;
			}

			$this->transient_name = $sbi_transient_name;
		}

	}

	/**
	 * Based on the settings related to retrieving post data from the API,
	 * this setting is used to make sure all endpoints needed for the feed are
	 * connected and stored for easily looping through when adding posts
	 *
	 * Pro - More feed types supported (hashtag_recent, hashtag_top)
	 *
	 * @since 5.0
	 * @since 5.2 mixed feeds use shortcode settings only, support for
	 *  tagged feeds added
	 * @since 5.3 warnings and workarounds added for deprecated accounts
	 */
	public function set_feed_type_and_terms() {
		global $sb_instagram_posts_manager;

		$connected_accounts_in_feed = array();
		$feed_type_and_terms = array();
		$is_after_deprecation_deadline = sbi_is_after_deprecation_deadline();
		$is_using_access_token_in_shortcode = ! empty( $this->atts['accesstoken'] ) && strpos( $this->atts['accesstoken'], '.' ) !== false;
		$users_connected_to_old_api_only = array();
		$settings_link = '<a href="'.get_admin_url().'?page=sb-instagram-feed" target="_blank">' . __( 'plugin Settings page', 'instagram-feed' ) . '</a>';

		// if using an access token in the shortcode and after the deadline, try to use a connected account by collecting the user IDs
		if ( $is_after_deprecation_deadline && $is_using_access_token_in_shortcode ) {
			$error = '<p><b>' . __( 'Error: Cannot add access token directly to the shortcode.', 'instagram-feed' ) . '</b><br>' . sprintf( __( 'Due to recent Instagram platform changes, it\'s no longer possible to create a feed by adding the access token to the shortcode. Remove the access token from the shortcode and connect an account on the %s instead.', 'instagram-feed' ), $settings_link );

			$sb_instagram_posts_manager->add_frontend_error( 'deprecation_warning', $error );

			$this->settings['id'] = array();
			$access_tokens = explode( ',', str_replace( ' ', '', $this->atts['accesstoken'] ) );

			foreach ( $access_tokens as $access_token ) {
				$split_token = explode( '.', $access_token );
				$this->settings['id'][] = $split_token[0];
			}
		}
		if ( empty( $this->settings['id'] )
		     && empty( $this->settings['user'] )
		     && ! empty ( $this->connected_accounts ) ) {
			$set = false;
			foreach ( $this->connected_accounts as $connected_account ) {
				if ( ! $set ) {
					$set = true;
					$this->settings['user'] = $connected_account['username'];
				}
			}
		}

		if ( ! $is_after_deprecation_deadline && $is_using_access_token_in_shortcode ) {
			$error = '<p><b>' . __( 'Warning: Cannot add access token directly to the shortcode.', 'instagram-feed' ) . '</b><br>' . sprintf( __( 'Due to upcoming Instagram platform changes on June 1, 2020, it will no longer be possible for feeds to use access tokens directly in the shortcode. Remove the access token from the shortcode and connect an account on the %s instead.', 'instagram-feed' ), $settings_link );

			$sb_instagram_posts_manager->add_frontend_error( 'deprecation_warning', $error );

			$feed_type_and_terms = array(
				'users' => array()
			);

			$access_tokens = explode( ',', str_replace( ' ', '', $this->atts['accesstoken'] ) );

			foreach ( $access_tokens as $access_token ) {
				$split_token = explode( '.', $access_token );
				$connected_accounts_in_feed[ $split_token[0] ] = array(
					'access_token' => $access_token,
					'user_id' => $split_token[0],
					'username' => $split_token[0],
					'profile_picture' => ''
				);
				$feed_type_and_terms['users'][] = array(
					'term' => $split_token[0],
					'params' => array()
				);
			}

		} elseif ( $this->settings['type'] === 'user' ) {
			$feed_type_and_terms = array(
				'users' => array()
			);
			$usernames_included = array();

			if ( ! empty( $this->settings['user'] ) ) {
				$user_array = is_array( $this->settings['user'] ) ? $this->settings['user'] : explode( ',', str_replace( ' ', '',  $this->settings['user'] ) );
				foreach ( $user_array as $user ) {
					$term_for_this_user = array();
					$user_found = false;
					$user_for_deprecated_personal_account_only_found = false;
					$username_to_match = $user;

					if ( isset( $this->connected_accounts[ $user ] ) ) {
						if ( ! in_array( $this->connected_accounts[ $user ]['username'], $usernames_included, true ) ) {
							$term_for_this_user = array(
								'term' => $this->connected_accounts[ $user ]['user_id'],
								'params' => array()
							);
							if ( isset( $this->connected_accounts[ $user ]['private'] ) && sbi_private_account_near_expiration( $this->connected_accounts[ $user ] ) ) {
								$link_1 = '<a href="https://help.instagram.com/116024195217477/In">';
								$link_2 = '</a>';
								$sb_instagram_posts_manager->add_error( 'expiration_' . $this->connected_accounts[ $user ]['user_id'], array( 'Private Instagram Account Needs Reauthentication', sprintf( __( 'It looks like your Instagram account is private. Instagram requires private accounts to be reauthenticated every 60 days. Refresh your account to allow it to continue updating, or %smake your Instagram account public%s.', 'instagram-feed' ), $link_1, $link_2 ), 10 ) );

								$error = '<p><b>' . __( 'Error: Private Instagram Account.', 'instagram-feed' ) . '</b>';
								$cap = current_user_can( 'manage_instagram_feed_options' ) ? 'manage_instagram_feed_options' : 'manage_options';
								$cap = apply_filters( 'sbi_settings_pages_capability', $cap );
								if ( current_user_can( $cap ) ) {
									$error_link = '<p><a href="https://smashballoon.com/instagram-feed/docs/errors/#10">' . __( 'Click here to troubleshoot', 'instagram-feed' ) . '</a></p>';

								} else {
									$error_link = '';
									$link_1 = '';
									$link_2 = '';
								}
								$error .= '<p>' . sprintf( __( 'It looks like your Instagram account is private. Instagram requires private accounts to be reauthenticated every 60 days. Refresh your account to allow it to continue updating, or %smake your Instagram account public%s.', 'instagram-feed' ), $link_1, $link_2 ) . '</p>';

								$error .= $error_link;
								$sb_instagram_posts_manager->add_frontend_error( 'noposts', $error );
							}

							$connected_accounts_in_feed[ $this->connected_accounts[ $user ]['user_id'] ] = $this->connected_accounts[ $user ];
							$usernames_included[] = $this->connected_accounts[ $user ]['username'];
							$username_to_match = $this->connected_accounts[ $user ]['username'];
							$user_found = true;
							if ( ! isset( $this->connected_accounts[ $user ]['type'] ) || $this->connected_accounts[ $user ]['type'] === 'personal' ) {
								$user_for_deprecated_personal_account_only_found = true;
							}
						}
					}

					if ( ! $user_found || $user_for_deprecated_personal_account_only_found ) {
						foreach ( $this->connected_accounts as $connected_account ) {
							$account_type = isset( $connected_account['type'] ) ? $connected_account['type'] : 'personal';
							if ( strtolower( $username_to_match ) === strtolower( $connected_account['username'] ) ) {
								if ( $user_for_deprecated_personal_account_only_found || ! in_array( $connected_account['username'], $usernames_included, true ) ) {
									if ( $account_type !== 'personal' ) {
										$term_for_this_user      = array(
											'term' => $user,
											'params' => array()
										);
										$connected_accounts_in_feed[ $user ] = $connected_account;
										$user_for_deprecated_personal_account_only_found = false;
									} else {
										$term_for_this_user                              = array(
											'term' => $connected_account['user_id'],
											'params' => array()
										);
										$connected_accounts_in_feed[ $connected_account['user_id'] ] = $connected_account;

										if ( isset( $connected_account['private'] ) && sbi_private_account_near_expiration( $connected_account ) ) {
											$sb_instagram_posts_manager->add_error( 'expiration_' . $connected_account['user_id'], array( 'Access Token Error', __( 'An access token for one of your accounts expires soon. Reconnect the account to continue automatic feed updates.', 'instagram-feed' ), 10 ) );
										}
									}
									$usernames_included[] = $connected_account['username'];
									$user_found = true;
								}
							}
						}
					}

					if ( ! empty(  $term_for_this_user ) ) {
						$feed_type_and_terms['users'][] = $term_for_this_user;
					}

					if ( ! $user_found ) {
						$error = '<p><b>' . sprintf( __( 'Error: There is no connected account for the user %s.', 'instagram-feed' ), $user ) . ' ' . __( 'Feed will not update.', 'instagram-feed' ) . '</b>';

						$sb_instagram_posts_manager->add_frontend_error( 'no_connection_' . $user, $error );
					}

					if ( $user_for_deprecated_personal_account_only_found
					     && ! in_array( $user, $users_connected_to_old_api_only,  true ) ) {
						$users_connected_to_old_api_only[] = $connected_accounts_in_feed[ $user ]['username'];
					}

				}

			} elseif ( ! empty( $this->settings['id'] ) ) {
				$user_id_array = is_array( $this->settings['id'] ) ? $this->settings['id'] : explode( ',', str_replace( ' ', '',  $this->settings['id'] ) );

				foreach ( $user_id_array as $user ) {
					$user_found = false;
					$user_for_deprecated_personal_account_only_found = false;
					$term_for_this_user = array();
					$username_to_match = $user;

					if ( isset( $this->connected_accounts[ $user ] ) ) {
						if ( ! in_array( $this->connected_accounts[ $user ]['username'], $usernames_included, true ) ) {
							$term_for_this_user                                              = array(
								'term' => $this->connected_accounts[ $user ]['user_id'],
								'params' => array()
							);
							$connected_accounts_in_feed[ $this->connected_accounts[ $user ]['user_id'] ] = $this->connected_accounts[ $user ];
							$usernames_included[]                                                        = $this->connected_accounts[ $user ]['username'];
							$username_to_match = $this->connected_accounts[ $user ]['username'];
							$user_found = true;
							if ( ! isset( $this->connected_accounts[ $user ]['type'] ) || $this->connected_accounts[ $user ]['type'] === 'personal' ) {
								$user_for_deprecated_personal_account_only_found = true;
							}
						}

					}

					if ( ! $user_found || $user_for_deprecated_personal_account_only_found ) {

						foreach ( $this->connected_accounts as $connected_account ) {
							$account_type = isset( $connected_account['type'] ) ? $connected_account['type'] : 'personal';
							$old_id_matches = ($account_type === 'basic' && isset( $connected_account['old_user_id'] ) && (string)$connected_account['old_user_id'] === (string)$user);
							if ( $old_id_matches
							     || (strtolower( $username_to_match ) === strtolower( $connected_account['username'] )) ) {
								if ( $user_for_deprecated_personal_account_only_found || ! in_array( $connected_account['username'], $usernames_included, true ) ) {
									if ( $account_type !== 'personal' ) {
										$term_for_this_user      = array(
											'term' => $user,
											'params' => array()
										);
										$connected_accounts_in_feed[ $user ] = $connected_account;
										$user_for_deprecated_personal_account_only_found = false;
									} else {
										$term_for_this_user                              = array(
											'term' => $connected_account['user_id'],
											'params' => array()
										);
										$connected_accounts_in_feed[ $connected_account['user_id'] ] = $connected_account;
									}
									$usernames_included[] = $connected_account['username'];
									$user_found = true;
								}
							}
						}

						if ( ! empty(  $term_for_this_user ) ) {
							$feed_type_and_terms['users'][] = $term_for_this_user;
						}

						if ( ! $user_found ) {
							$error = '<p><b>' . sprintf( __( 'Error: There is no connected account for the user %s', 'instagram-feed' ), $user ) . ' ' . __( 'Feed will not update.', 'instagram-feed' ) . '</b>';

							$sb_instagram_posts_manager->add_frontend_error( 'no_connection_' . $user, $error );
						}

						if ( $user_for_deprecated_personal_account_only_found
						     && ! in_array( $user, $users_connected_to_old_api_only,  true ) ) {
							$users_connected_to_old_api_only[] = $connected_accounts_in_feed[ $user ]['username'];
						}

					}

				}

			}

			if ( empty( $feed_type_and_terms['users'] ) ) {
				$error = '<p><b>' . __( 'Error: No users set.', 'instagram-feed' ) . '</b> ' . __( 'Please visit the plugin\'s settings page to select a user account or add one to the shortcode - user="username".', 'instagram-feed' ) . '</p>';

				$sb_instagram_posts_manager->add_frontend_error( 'no_user_set', $error );
			}

		} elseif ( $this->settings['type'] === 'hashtag' ) {
			$hashtag_order_suffix = $this->settings['order'] === 'recent' ? 'recent' : 'top';
			if ( $this->settings['order'] === 'top' ) {
				$this->settings['sortby'] = 'api';
				$this->settings['apinum'] = 50;
			}
			$feed_type_and_terms[ 'hashtags_' . $hashtag_order_suffix ] = array();

			$hashtags = is_array( $this->settings['hashtag'] ) ? $this->settings['hashtag'] : explode( ',', str_replace( array( ' ', '#' ), '', $this->settings['hashtag'] ) );

			$saved_hashtag_ids = SB_Instagram_Settings_Pro::get_hashtag_ids();

			foreach ( $this->connected_accounts as $connected_account ) {

				if ( isset( $connected_account['type'] ) && $connected_account['type'] === 'business' ) {
					$this->business_accounts[] = $connected_account;
				}
			}

			if ( ! empty( $this->business_accounts ) ) {

				$new_hashtag_ids = array();
				$connected_business_account = $this->business_accounts[0];
				$valid_hashtag_found = false;
				foreach ( $hashtags as $hashtag ) {
					if ( ! empty( $hashtag ) ) {
						$hashtag_id = false;

						if ( isset( $saved_hashtag_ids[ $hashtag ] ) ) {
							$hashtag_id = $saved_hashtag_ids[ $hashtag ];
						} else {
							$i = 0;
							$error_that_isnt_18 = false;
							$new_id_set = false;
							$account_found = false;

							while ( isset( $this->business_accounts[ $i ] ) && ! $new_id_set && ! $error_that_isnt_18 ) {
								$connection = new SB_Instagram_API_Connect_Pro( $this->business_accounts[ $i ], 'recently_searched_hashtags', array( 'hashtag' => $hashtag ) );

								$connection->connect();

								$recently_searched_data = !$connection->is_wp_error() ? $connection->get_data() : false;

								$num_hashatags_remaining = $recently_searched_data && is_array( $recently_searched_data ) ? 30 - count( $recently_searched_data ) : 0;

								if ( $num_hashatags_remaining > 0 ) {
									$account_found = true;
									$maybe_hashtag_id = SB_Instagram_Settings_Pro::get_remote_hashtag_id_from_hashtag_name( $hashtag, $this->business_accounts[ $i ] );

									if ( $maybe_hashtag_id !== 'error_18' && $maybe_hashtag_id !== false ) {
										SB_Instagram_Settings_Pro::clear_hashtag_limit_reached( $this->business_accounts[ $i ] );
										$hashtag_id = $maybe_hashtag_id;
										$new_hashtag_ids[ $hashtag ] = $hashtag_id;
										$new_id_set = true;
										$connected_business_account = $this->business_accounts[ $i ];
									} else {
										if ( $maybe_hashtag_id === 'error_18' ) {
											$error_that_isnt_18 = false;
											$sb_instagram_posts_manager->add_frontend_error( 'hashtag_limit_reached', __( '<p><strong>Limit of 30 unique hashtags per week has been reached.</strong><br> Please allow up to 7 days to show a new hashtag feed. You can also try connecting a second business account.</p>', 'instagram-feed' ) );
										} else {
											$error_that_isnt_18 = true;
										}
									}

								}

								$i++;
							}

							if ( $account_found ) {
								$sb_instagram_posts_manager->remove_frontend_error( 'hashtag_limit_reached' );
							}
						}

						if ( $hashtag_id ) {

							$connected_accounts_in_feed[ $hashtag_id ] = $connected_business_account;
							$feed_type_and_terms[ 'hashtags_' . $hashtag_order_suffix ][] = array(
								'term' => $hashtag_id,
								'params' => array( 'hashtag_id' => $hashtag_id ),
								'hashtag_name' => $hashtag
							);

							if ( $hashtag_order_suffix === 'recent' && ! SB_Instagram_Posts_Manager::top_post_request_already_made( $hashtag ) ) {
								$this->settings['cache_all'] = true;
								$feed_type_and_terms['hashtags_top'][] = array(
									'term' => $hashtag_id,
									'params' => array( 'hashtag_id' => $hashtag_id ),
									'hashtag_name' => $hashtag,
									'one_time_request' => true
								);
							}

						}

					}
				}

				if ( ! empty( $new_hashtag_ids ) ) {
					SB_Instagram_Settings_Pro::update_hashtag_ids( $new_hashtag_ids );
				}

				if ( empty( $hashtags ) ) {
					$error = '<p><b>' . __( 'Error: No hashtags set.', 'instagram-feed' ) . '</b> ' . __( 'Please visit the plugin\'s settings page to enter a hashtag or add one to the shortcode - hashtag="example".', 'instagram-feed' ) . '</p>';

					$sb_instagram_posts_manager->add_frontend_error( 'no_hashtag_set', $error );
				}


			} else {
				$error = '<p><b>' . __( 'Error: There are no business accounts connected.', 'instagram-feed' ) . '</b> ' . sprintf( __( 'Please visit %s to learn how to connect a business account.', 'instagram-feed' ), '<a href="https://smashballoon.com/migrate-to-new-instagram-hashtag-api/">' . __( 'this page', 'instagram-feed' ) . '</a>' ) . '</p>';

				$sb_instagram_posts_manager->add_frontend_error( 'no_business_accounts', $error );
			}

		} elseif ( $this->settings['type'] === 'tagged' ) {
			$feed_type_and_terms['tagged'] = array();

			$tagged = is_array( $this->settings['tagged'] ) ? $this->settings['tagged'] : explode( ',', str_replace( array(
				' ',
				'@'
			), '', $this->settings['tagged'] ) );
			if ( count( $tagged ) > 1 ) {
				$this->settings['sortby'] = 'alternate';
			} else {
				$this->settings['sortby'] = 'api';
			}

			$usernames_included = array();

			if ( ! empty( $tagged ) ) {
				$user_array = is_array( $tagged ) ? $tagged : explode( ',', str_replace( ' ', '',  $tagged ) );
				foreach ( $user_array as $user ) {
					$user_found = false;
					if ( isset( $this->connected_accounts[ $user ] ) ) {
						$user_found = true;
						if ( ! in_array( $this->connected_accounts[ $user ]['username'], $usernames_included, true ) ) {
							$feed_type_and_terms['tagged'][] = array(
								'term' => $this->connected_accounts[ $user ]['user_id'],
								'params' => array()
							);
							$connected_accounts_in_feed[ $this->connected_accounts[ $user ]['user_id'] ] = $this->connected_accounts[ $user ];
							$usernames_included[] = $this->connected_accounts[ $user ]['username'];
						}
					} else {
						foreach ( $this->connected_accounts as $connected_account ) {
							if ( strtolower( $user ) === strtolower( $connected_account['username'] ) ) {
								if ( ! in_array( $connected_account['username'], $usernames_included, true ) ) {
									if ( isset( $connected_account['type'] ) && $connected_account['type'] === 'business' ) {
										$feed_type_and_terms['tagged'][]     = array(
											'term'   => $user,
											'params' => array()
										);
										$connected_accounts_in_feed[ $user ] = $connected_account;
										$usernames_included[]                = $connected_account['username'];
										$user_found                          = true;
									}

								}
							}
						}
					}

					if ( ! $user_found ) {
						$error = '<p><b>' . sprintf( __( 'Error: There is no connected business account for the user %s.', 'instagram-feed' ), $user ) . ' ' . __( 'Feed will not update.', 'instagram-feed' ) . '</b>';

						$sb_instagram_posts_manager->add_frontend_error( 'no_connection_' . $user, $error );
					}

				}

			}

		} elseif ( $this->settings['type'] === 'mixed' ) {

			$feed_type_and_terms = array(
				'users' => array()
			);
			$usernames_included = array();

			if ( ! empty( $this->atts['user'] ) ) {
				$user_array = is_array( $this->atts['user'] ) ? $this->atts['user'] : explode( ',', str_replace( ' ', '',  $this->atts['user'] ) );
				foreach ( $user_array as $user ) {
					$user_found = false;
					if ( isset( $this->connected_accounts[ $user ] ) ) {
						if ( ! in_array( $this->connected_accounts[ $user ]['username'], $usernames_included, true ) ) {
							$feed_type_and_terms['users'][] = array(
								'term' => $this->connected_accounts[ $user ]['user_id'],
								'params' => array()
							);
							$connected_accounts_in_feed[ $this->connected_accounts[ $user ]['user_id'] ] = $this->connected_accounts[ $user ];
							$usernames_included[] = $this->connected_accounts[ $user ]['username'];
						}
					} else {

						foreach ( $this->connected_accounts as $connected_account ) {
							if ( strtolower( $user ) === strtolower( $connected_account['username'] ) ) {
								if ( ! in_array( $connected_account['username'], $usernames_included, true ) ) {
									if ( isset( $connected_account['type'] ) && $connected_account['type'] === 'business' ) {
										$feed_type_and_terms['users'][]      = array(
											'term' => $user,
											'params' => array()
										);
										$connected_accounts_in_feed[ $user ] = $connected_account;
									} else {
										$feed_type_and_terms['users'][]                              = array(
											'term' => $connected_account['user_id'],
											'params' => array()
										);
										$connected_accounts_in_feed[ $connected_account['user_id'] ] = $connected_account;
									}
									$usernames_included[] = $connected_account['username'];
									$user_found = true;
								}
							}
						}

						if ( ! $user_found ) {
							$error = '<p><b>' . sprintf( __( 'Error: There is no connected account for the user %s.', 'instagram-feed' ), $user ) . ' ' . __( 'Feed will not update.', 'instagram-feed' ) . '</b>';

							$sb_instagram_posts_manager->add_frontend_error( 'no_connection_' . $user, $error );
						}

					}

				}

			} elseif ( ! empty( $this->atts['id'] ) ) {
				$user_id_array = is_array( $this->atts['id'] ) ? $this->atts['id'] : explode( ',', str_replace( ' ', '',  $this->atts['id'] ) );

				foreach ( $user_id_array as $user ) {
					$user_found = false;

					if ( isset( $this->connected_accounts[ $user ] ) ) {
						if ( ! in_array( $this->connected_accounts[ $user ]['username'], $usernames_included, true ) ) {
							$feed_type_and_terms['users'][]                                              = array(
								'term' => $this->connected_accounts[ $user ]['user_id'],
								'params' => array()
							);
							$connected_accounts_in_feed[ $this->connected_accounts[ $user ]['user_id'] ] = $this->connected_accounts[ $user ];
							$usernames_included[]                                                        = $this->connected_accounts[ $user ]['username'];
						}

					} else {

						foreach ( $this->connected_accounts as $connected_account ) {
							if ( strtolower( $user ) === strtolower( $connected_account['username'] ) ) {
								if ( ! in_array( $this->connected_accounts[ $user ]['username'], $usernames_included, true ) ) {
									if ( isset( $connected_account['type'] ) && $connected_account['type'] === 'business' ) {
										$feed_type_and_terms['users'][]      = array(
											'term' => $user,
											'params' => array()
										);
										$connected_accounts_in_feed[ $user ] = $connected_account;
									} else {
										$feed_type_and_terms['users'][]                              =  array(
											'term' => $connected_account['user_id'],
											'params' => array()
										);
										$connected_accounts_in_feed[ $connected_account['user_id'] ] = $connected_account;
									}
									$usernames_included[] = $this->connected_accounts[ $user ]['username'];
									$user_found           = true;
								}
							}
						}

						if ( ! $user_found ) {
							$error = '<p><b>' . sprintf( __( 'Error: There is no connected account for the user %s', 'instagram-feed' ), $user ) . ' ' . __( 'Feed will not update.', 'instagram-feed' ) . '</b>';

							$sb_instagram_posts_manager->add_frontend_error( 'no_connection_' . $user, $error );
						}

					}

				}

			}

			if ( ! empty( $this->atts['hashtags'] ) || ! empty( $this->atts['hashtag'] ) ) {


				$hashtag_order_suffix = $this->settings['order'] === 'recent' ? 'recent' : 'top';
				$feed_type_and_terms[ 'hashtags_' . $hashtag_order_suffix ] = array();

				$terms = ! empty( $this->atts['hashtags'] ) ? $this->atts['hashtags'] : $this->atts['hashtag'];

				$hashtags          = is_array( $terms ) ? $terms : explode( ',', str_replace( array(
					' ',
					'#'
				), '', $terms ) );
				$saved_hashtag_ids = SB_Instagram_Settings_Pro::get_hashtag_ids();

				foreach ( $this->connected_accounts as $connected_account ) {

					if ( isset( $connected_account['type'] ) && $connected_account['type'] === 'business' ) {
						$this->business_accounts[] = $connected_account;
					}
				}

				if ( ! empty( $this->business_accounts ) ) {
					$new_hashtag_ids            = array();
					$connected_business_account = $this->business_accounts[0];

					foreach ( $hashtags as $hashtag ) {
						if ( ! empty( $hashtag ) ) {
							$hashtag_id = false;

							if ( isset( $saved_hashtag_ids[ $hashtag ] ) ) {
								$hashtag_id = $saved_hashtag_ids[ $hashtag ];

							} else {
								$i                  = 0;
								$error_that_isnt_18 = false;
								$new_id_set         = false;
								$account_found      = false;

								while ( isset( $this->business_accounts[ $i ] ) && ! $new_id_set && ! $error_that_isnt_18 ) {
									$connection = new SB_Instagram_API_Connect_Pro( $this->business_accounts[ $i ], 'recently_searched_hashtags', array( 'hashtag' => $hashtag ) );

									$connection->connect();

									$recently_searched_data = ! $connection->is_wp_error() ? $connection->get_data() : false;

									$num_hashatags_remaining = $recently_searched_data && isset( $recently_searched_data ) && is_array( $recently_searched_data ) ? 30 - count( $recently_searched_data ) : 0;

									if ( $num_hashatags_remaining > 0 ) {
										$account_found    = true;
										$maybe_hashtag_id = SB_Instagram_Settings_Pro::get_remote_hashtag_id_from_hashtag_name( $hashtag, $this->business_accounts[ $i ] );
										if ( $maybe_hashtag_id !== 'error_18' && $maybe_hashtag_id !== false ) {
											SB_Instagram_Settings_Pro::clear_hashtag_limit_reached( $this->business_accounts[ $i ] );
											$hashtag_id                  = $maybe_hashtag_id;
											$new_hashtag_ids[ $hashtag ] = $hashtag_id;
											$new_id_set                  = true;
											$connected_business_account  = $this->business_accounts[ $i ];
										} else {
											if ( $maybe_hashtag_id === 'error_18' ) {
												$sb_instagram_posts_manager->add_frontend_error( 'hashtag_limit_reached', __( '<p><strong>Limit of 30 unique hashtags per week has been reached.</strong><br> Please allow up to 7 days to show a new hashtag feed. You can also try connecting a second business account.</p>', 'instagram-feed' ) );

												$error_that_isnt_18 = false;
											} else {
												$error_that_isnt_18 = true;
											}
										}

									}

									$i ++;
								}

								if ( $account_found ) {
									$sb_instagram_posts_manager->remove_frontend_error( 'hashtag_limit_reached' );
								}

							}

							if ( $hashtag_id ) {
								$connected_accounts_in_feed[ $hashtag_id ]                    = $connected_business_account;
								$feed_type_and_terms[ 'hashtags_' . $hashtag_order_suffix ][] = array(
									'term'         => $hashtag_id,
									'params'       => array( 'hashtag_id' => $hashtag_id ),
									'hashtag_name' => $hashtag
								);
							}
						}
					}

					if ( ! empty( $new_hashtag_ids ) ) {
						SB_Instagram_Settings_Pro::update_hashtag_ids( $new_hashtag_ids );
					}

					if ( empty( $hashtags ) ) {
						$error = '<p><b>' . __( 'Error: No hashtags set.', 'instagram-feed' ) . '</b> ' . __( 'Please visit the plugin\'s settings page to enter a hashtag or add one to the shortcode - hashtag="example".', 'instagram-feed' ) . '</p>';

						$sb_instagram_posts_manager->add_frontend_error( 'no_hashtag_set', $error );
					} elseif ( empty( $feed_type_and_terms[ 'hashtags_' . $hashtag_order_suffix ] ) ) {
						$error = '<p><b>' . __( 'Error: Hashtag limit of 30 unique hashtags per week has been reached.', 'instagram-feed' ) . '</b>';
						$error .= '<p>' . __( 'If you need to display more than 30 hashtag feeds on your site, consider connecting an additional business account from a separate Instagram and Facebook account.', 'instagram-feed' );

						$sb_instagram_posts_manager->add_frontend_error( 'hashtag_limit_reached', $error );
					}
				} else {
					$error = '<p><b>' . __( 'Error: There are no business accounts connected.', 'instagram-feed' ) . '</b> ' . sprintf( __( 'Please visit %s to learn how to connect a business account.', 'instagram-feed' ), '<a href="https://smashballoon.com/migrate-to-new-instagram-hashtag-api/">' . __( 'this page', 'instagram-feed' ) . '</a>' ) . '</p>';

					$sb_instagram_posts_manager->add_frontend_error( 'no_business_accounts', $error );
				}

			}

			if ( ! empty( $this->atts['tagged'] ) ) {
				$this->settings['sortby'] = 'alternate';
				$feed_type_and_terms['tagged'] = array();

				$tagged = is_array( $this->atts['tagged'] ) ? $this->atts['tagged'] : explode( ',', str_replace( array(
					' ',
					'@'
				), '', $this->atts['tagged'] ) );

				$usernames_included = array();

				if ( ! empty( $tagged ) ) {
					$user_array = is_array( $tagged ) ? $tagged : explode( ',', str_replace( ' ', '',  $tagged ) );
					foreach ( $user_array as $user ) {
						$user_found = false;

						foreach ( $this->connected_accounts as $connected_account ) {
							if ( strtolower( $user ) === strtolower( $connected_account['username'] ) ) {
								if ( ! in_array( $connected_account['username'], $usernames_included, true ) ) {
									if ( isset( $connected_account['type'] ) && $connected_account['type'] === 'business' ) {
										$feed_type_and_terms['tagged'][]      = array(
											'term' => $user,
											'params' => array()
										);
										$connected_accounts_in_feed[ $user ] = $connected_account;
										$usernames_included[] = $connected_account['username'];
										$user_found = true;
									}

								}
							}
						}

						if ( ! $user_found ) {
							$error = '<p><b>' . sprintf( __( 'Error: There is no connected business account for the user %s.', 'instagram-feed' ), $user ) . ' ' . __( 'Feed will not update.', 'instagram-feed' ) . '</b>';

							$sb_instagram_posts_manager->add_frontend_error( 'no_connection_' . $user, $error );
						}

					}

				}

			}

		} else {
			$error = '<p><b>' . __( 'Error: No feed type selected.', 'instagram-feed' ) . '</b> ' . sprintf( __( 'Please use a feed type of "user", "hashtag", or "mixed".', 'instagram-feed' ), '<a href="https://smashballoon.com/migrate-to-new-instagram-hashtag-api/">' . __( 'this page', 'instagram-feed' ) . '</a>' ) . '</p>';

			$sb_instagram_posts_manager->add_frontend_error( 'no_type', $error );
		}

		if ( ! empty( $users_connected_to_old_api_only ) ) {
			$total = count( $users_connected_to_old_api_only );
			if ( $total > 1 ) {
				$user_string = '';
				$i = 0;

				foreach ( $users_connected_to_old_api_only as $username ) {
					if ( ($i + 1) === $total ) {
						$user_string .= ' and ' . $username;
					} else {
						if ( $i !== 0 ) {
							$user_string .= ', ' . $username;
						} else {
							$user_string .= $username;
						}
					}
					$i++;
				}
			} else {
				$user_string = $users_connected_to_old_api_only[0];
			}

			if ( $is_after_deprecation_deadline ) {
				$error = '<p><b>' . sprintf( __( 'Error: The account for %s needs to be reconnected.', 'instagram-feed' ), '<em>'.$user_string.'</em>' ) . '</b><br>' . __( 'Due to recent Instagram platform changes this Instagram account needs to be reconnected in order to continue updating.', 'instagram-feed' ) . '<a href="'.get_admin_url().'?page=sb-instagram-feed" class="sb_frontend_btn"><i class="fa fa-cog" aria-hidden="true"></i> ' . __( 'Reconnect on plugin Settings page', 'instagram-feed' ) . '</a>';
			} else {
				$error = '<p><b>' . sprintf( __( 'Warning: The account for %s needs to be reconnected.', 'instagram-feed' ), '<em>'.$user_string.'</em>' ) . '</b><br>' . __( 'Due to Instagram platform changes on June 1, 2020, this Instagram account needs to be reconnected to allow the feed to continue updating.', 'instagram-feed' ) . '<a href="'.get_admin_url().'?page=sb-instagram-feed" class="sb_frontend_btn"><i class="fa fa-cog" aria-hidden="true"></i> ' . __( 'Reconnect on plugin Settings page', 'instagram-feed' ) . '</a>';
			}

			$sb_instagram_posts_manager->add_frontend_error( 'deprecation_warning', $error );
		}

		$this->connected_accounts_in_feed = $connected_accounts_in_feed;
		$this->feed_type_and_terms = $feed_type_and_terms;
	}

	/**
	 * Each hashtag has an ID associated with it. This must be retrieved first to
	 * get any posts associated with the hashtag.
	 *
	 * @param $hashtag
	 * @param $account
	 *
	 * @return bool|string
	 *
	 * @since 5.0
	 */
	public static function get_remote_hashtag_id_from_hashtag_name( $hashtag, $account ) {
		global $sb_instagram_posts_manager;

		if ( $sb_instagram_posts_manager->are_current_api_request_delays() ) {
			return false;
		}

		$connection = new SB_Instagram_API_Connect_Pro( $account, 'ig_hashtag_search', array( 'hashtag' => $hashtag ) );

		$connection->connect();

		if ( ! $connection->is_wp_error() && ! $connection->is_instagram_error() ) {
			$data = $connection->get_data();
			if ( isset( $data[0] ) ) {
				return $data[0]['id'];
			} else {
				return false;
			}
		} else {
			if ( $connection->is_wp_error() ) {
				SB_Instagram_API_Connect_Pro::handle_wp_remote_get_error( $connection->get_wp_error() );
			} else {
				SB_Instagram_API_Connect_Pro::handle_instagram_error( $connection->get_data(), $account, 'ig_hashtag_search' );
				$response = $connection->get_data();

				if ( (int)$response['error']['code'] === 24 ) {
					$sb_instagram_posts_manager->add_error( 'ig_no_posts_for_' . $hashtag, array( time(), $response['error']['error_user_msg'] ) );

					return false;

				} elseif ( (int)$response['error']['code'] === 18 ) {
					$sb_instagram_posts_manager->add_error( 'error_18', array( time(), $response['error']['error_user_msg'] ) );

					return 'error_18';
				} else {
					$sb_instagram_posts_manager->remove_error( 'ig_no_posts_for_' . $hashtag );
					$sb_instagram_posts_manager->remove_error( 'error_18' );
				}


			}

			return false;
		}

	}

	/**
	 * The plugin will output settings on the frontend for debugging purposes.
	 * Safe settings to display are added here.
	 *
	 * @return array
	 *
	 * @since 5.2
	 */
	public static function get_public_db_settings_keys() {
		$public = array(
			'sb_instagram_type',
			'sb_instagram_order',
			'sb_instagram_user_id',
			'sb_instagram_hashtag',
			'sb_instagram_user_id',
			'sb_instagram_ajax_theme',
			'enqueue_js_in_head',
			'disable_js_image_loading',
			'sb_instagram_disable_resize',
			'sb_instagram_favor_local',
			'sb_ajax_initial',
			'use_custom',
			'sb_instagram_layout_type',
			'sb_instagram_highlight_type',
			'sb_instagram_highlight_offset',
			'sb_instagram_highlight_factor',
			'sb_instagram_highlight_ids',
			'sb_instagram_highlight_hashtag',
			'sb_instagram_carousel',
			'sb_instagram_carousel_rows',
			'sb_instagram_carousel_loop',
			'sb_instagram_carousel_arrows',
			'sb_instagram_carousel_pag',
			'sb_instagram_carousel_autoplay',
			'sb_instagram_carousel_interval',
			'sb_instagram_offset',

			//Hover style
			'sb_hover_background',
			'sb_hover_text',
			'sbi_hover_inc_username',
			'sbi_hover_inc_icon',
			'sbi_hover_inc_date',
			'sbi_hover_inc_instagram',
			'sbi_hover_inc_location',
			'sbi_hover_inc_caption',
			'sbi_hover_inc_likes',
			'sb_instagram_cache_time',
			'sb_instagram_cache_time_unit',
			'sbi_caching_type',
			'sbi_cache_cron_interval',
			'sbi_cache_cron_time',
			'sbi_cache_cron_am_pm',
			'sb_instagram_width',
			'sb_instagram_width_unit',
			'sb_instagram_feed_width_resp',
			'sb_instagram_height',
			'sb_instagram_num',
			'sb_instagram_height_unit',
			'sb_instagram_cols',
			'sb_instagram_disable_mobile',
			'sb_instagram_image_padding',
			'sb_instagram_image_padding_unit',
			'sb_instagram_sort',
			'sb_instagram_background',
			'sb_instagram_show_btn',
			'sb_instagram_btn_background',
			'sb_instagram_btn_text_color',
			'sb_instagram_btn_text',
			'sb_instagram_image_res',
			//Header
			'sb_instagram_show_header',
			'sb_instagram_header_size',
			'sb_instagram_header_color',
			//Follow button
			'sb_instagram_show_follow_btn',
			'sb_instagram_folow_btn_background',
			'sb_instagram_follow_btn_text_color',
			'sb_instagram_follow_btn_text',
			'sb_instagram_disable_lightbox',
			'sb_instagram_captionlinks',
			'sb_instagram_exclude_words',
			'sb_instagram_include_words',
			'sb_instagram_lightbox_comments',
			'sb_instagram_num_comments',
			'sb_instagram_stories',
			'sb_instagram_stories_time',
			'sb_instagram_autoscroll',
			'sb_instagram_autoscrolldistance',
			//Misc
			'sb_instagram_cron',
			'sb_instagram_backup',
			'sbi_font_method',
			'sb_instagram_disable_awesome',
		);

		return $public;
	}

	/**
	 * Hashtag IDs are stored locally to avoid the extra API call
	 *
	 * @return array
	 *
	 * @since 5.0
	 */
	public static function get_hashtag_ids() {
		return get_option( 'sbi_hashtag_ids', array() );
	}

	/**
	 * Stores the retrieved hashtag ID locally using hashtag => hashtag ID
	 * key value pair
	 *
	 * @param $hashtag_name_id_pairs
	 *
	 * @since 5.0
	 */
	public static function update_hashtag_ids( $hashtag_name_id_pairs ) {
		$existing = get_option( 'sbi_hashtag_ids', array() );

		$new = array_merge( $existing, $hashtag_name_id_pairs );

		update_option( 'sbi_hashtag_ids', $new, false );
	}

	/**
	 * Clears the marker for the hashtag limit being reached for a connected account
	 * since this expires after a week.
	 *
	 * @param $account
	 *
	 * @since 5.0
	 */
	public static function clear_hashtag_limit_reached( $account ) {
		$options = get_option( 'sb_instagram_settings', array() );

		$connected_accounts =  isset( $options['connected_accounts'] ) ? $options['connected_accounts'] : array();

		foreach ( $connected_accounts as $key => $connected_account ) {
			if ( isset( $connected_accounts[ $key ]['hashtag_limit_reached'] ) ) {
				unset( $connected_accounts[ $key ]['hashtag_limit_reached'] );
			}
		}

		$options['connected_accounts'] = $connected_accounts;

		update_option( 'sb_instagram_settings', $options );
	}

	public static function default_settings() {
		$defaults = array(
			'sb_instagram_at'                   => '',
			'sb_instagram_type'                 => 'user',
			'sb_instagram_order'                => 'top',
			'sb_instagram_user_id'              => '',
			'sb_instagram_tagged_ids' => '',
			'sb_instagram_hashtag'              => '',
			'sb_instagram_type_self_likes'      => '',
			'sb_instagram_location'             => '',
			'sb_instagram_coordinates'          => '',
			'sb_instagram_preserve_settings'    => '',
			'sb_instagram_ajax_theme'           => false,
			'enqueue_js_in_head'                => false,
			'disable_js_image_loading'          => false,
			'sb_instagram_disable_resize'       => false,
			'sb_instagram_favor_local'          => true,
			'sb_instagram_cache_time'           => '1',
			'sb_instagram_cache_time_unit'      => 'hours',
			'sbi_caching_type'                  => 'page',
			'sbi_cache_cron_interval'           => '12hours',
			'sbi_cache_cron_time'               => '1',
			'sbi_cache_cron_am_pm'              => 'am',

			'sb_instagram_width'                => '100',
			'sb_instagram_width_unit'           => '%',
			'sb_instagram_feed_width_resp'      => false,
			'sb_instagram_height'               => '',
			'sb_instagram_num'                  => '20',
			'sb_instagram_nummobile'            => '',
			'sb_instagram_height_unit'          => '',
			'sb_instagram_cols'                 => '4',
			'sb_instagram_colsmobile'           => 'auto',
			'sb_instagram_image_padding'        => '5',
			'sb_instagram_image_padding_unit'   => 'px',

			//Layout Type
			'sb_instagram_layout_type'          => 'grid',
			'sb_instagram_highlight_type'       => 'pattern',
			'sb_instagram_highlight_offset'     => 0,
			'sb_instagram_highlight_factor'     => 6,
			'sb_instagram_highlight_ids'        => '',
			'sb_instagram_highlight_hashtag'    => '',

			//Hover style
			'sb_hover_background'               => '',
			'sb_hover_text'                     => '',
			'sbi_hover_inc_username'            => true,
			'sbi_hover_inc_icon'                => true,
			'sbi_hover_inc_date'                => true,
			'sbi_hover_inc_instagram'           => true,
			'sbi_hover_inc_location'            => false,
			'sbi_hover_inc_caption'             => false,
			'sbi_hover_inc_likes'               => false,
			// 'sb_instagram_hover_text_size'      => '',

			'sb_instagram_sort'                 => 'none',
			'sb_instagram_disable_lightbox'     => false,
			'sb_instagram_offset'               => 0,
			'sb_instagram_captionlinks'         => false,
			'sb_instagram_background'           => '',
			'sb_instagram_show_btn'             => true,
			'sb_instagram_btn_background'       => '',
			'sb_instagram_btn_text_color'       => '',
			'sb_instagram_btn_text'             => __( 'Load More', 'instagram-feed' ),
			'sb_instagram_image_res'            => 'auto',
			'sb_instagram_media_type'           => 'all',
			'sb_instagram_moderation_mode'      => 'manual',
			'sb_instagram_hide_photos'          => '',
			'sb_instagram_block_users'          => '',
			'sb_instagram_ex_apply_to'          => 'all',
			'sb_instagram_inc_apply_to'         => 'all',
			'sb_instagram_show_users'           => '',
			'sb_instagram_exclude_words'        => '',
			'sb_instagram_include_words'        => '',

			//Text
			'sb_instagram_show_caption'         => true,
			'sb_instagram_caption_length'       => '50',
			'sb_instagram_caption_color'        => '',
			'sb_instagram_caption_size'         => '13',

			//lightbox comments
			'sb_instagram_lightbox_comments'    => true,
			'sb_instagram_num_comments'         => '20',

			//Meta
			'sb_instagram_show_meta'            => true,
			'sb_instagram_meta_color'           => '',
			'sb_instagram_meta_size'            => '13',
			//Header
			'sb_instagram_show_header'          => true,
			'sb_instagram_header_color'         => '',
			'sb_instagram_header_style'         => 'standard',
			'sb_instagram_show_followers'       => true,
			'sb_instagram_show_bio'             => true,
			'sb_instagram_custom_bio' => '',
			'sb_instagram_custom_avatar' => '',
			'sb_instagram_header_primary_color'  => '517fa4',
			'sb_instagram_header_secondary_color'  => 'eeeeee',
			'sb_instagram_header_size'  => 'small',
			'sb_instagram_outside_scrollable' => false,
			'sb_instagram_stories' => true,
			'sb_instagram_stories_time' => 5000,

			//Follow button
			'sb_instagram_show_follow_btn'      => true,
			'sb_instagram_folow_btn_background' => '',
			'sb_instagram_follow_btn_text_color' => '',
			'sb_instagram_follow_btn_text'      => __( 'Follow on Instagram', 'instagram-feed' ),

			//Autoscroll
			'sb_instagram_autoscroll' => false,
			'sb_instagram_autoscrolldistance' => 200,

			//Misc
			'sb_instagram_custom_css'           => '',
			'sb_instagram_custom_js'            => '',
			'sb_instagram_requests_max'         => '5',
			'sb_instagram_minnum' => '0',
			'sb_instagram_cron'                 => 'unset',
			'sb_instagram_disable_font'         => false,
			'sb_instagram_backup' => true,
			'sb_ajax_initial' => false,
			'enqueue_css_in_shortcode' => false,
			'sb_instagram_disable_mob_swipe' => false,
			'sbi_font_method' => 'svg',
			'sbi_br_adjust' => true,
			'sb_instagram_media_vine' => false,
			'custom_template' => false,
			'disable_admin_notice' => false,
			'enable_email_report' => 'on',
			'email_notification' => 'monday',
			'email_notification_addresses' => get_option( 'admin_email' ),

			//Carousel
			'sb_instagram_carousel'             => false,
			'sb_instagram_carousel_rows'        => 1,
			'sb_instagram_carousel_loop'        => 'rewind',
			'sb_instagram_carousel_arrows'      => false,
			'sb_instagram_carousel_pag'         => true,
			'sb_instagram_carousel_autoplay'    => false,
			'sb_instagram_carousel_interval'    => '5000'

		);

		return $defaults;
	}
}