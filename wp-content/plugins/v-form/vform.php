<?php
/*
 * Plugin Name:       Vform
 * Plugin URI:        /
 * Description:       World first full free form builder for wordpress.
 * Version:           2.0
 * Requires at least: 5.6
 * Author:            Vikas Ratudi
 * Author URI:        https://www.instagram.com/ratudi_vikas/?r=nametag
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       vform
 * Tags:              form, wordpress form, contact form, very simple form, drag drop form, v form, allinone form, secure form, vform, simple contact form, free form builder, free contact form, contact form plugin, forms, form builder, custom form, contact button, contact me, custom contact form, form manager, form, forms builder, forms creator, captcha, recaptcha, Akismet, email form, web form, feedback form, payment form, survey form, donation form, email submit form, message form, mailchimp, mailchimp form, aweber, aweber form, paypal, paypal form, stripe, stripe form, getresponse, getresponse form, email subscription, contact form widget, user registration form, wordpress registration, wordpress login form, feedback
*/

defined('ABSPATH') || die("You Can't Access this File Directly");

define('VFORM_PLUGIN_PATH',plugin_dir_path(__FILE__));
define('VFORM_PLUGIN_URL',plugin_dir_url(__FILE__));
define('VFORM_PLUGIN_FILE', __FILE__);
include VFORM_PLUGIN_PATH."inc/db.php";


	add_action('wp_enqueue_scripts','vform_wp_scripts');

	function vform_wp_scripts(){
		wp_enqueue_script('jquery');
		wp_enqueue_style('vform_dev_style', VFORM_PLUGIN_URL."assets/css/style.css");
		wp_enqueue_script('vform_dev_script', VFORM_PLUGIN_URL."assets/js/custom.js", array(),'1.0.0',true);
		wp_localize_script('vform_dev_script','ajax_object',admin_url("admin-ajax.php"));
	}

	add_action('admin_enqueue_scripts','vform_admin_enqueue_scripts');

	function vform_admin_enqueue_scripts(){
		wp_enqueue_script('jquery');
		wp_enqueue_style('vform_dev_style', VFORM_PLUGIN_URL."assets/css/style.css");
		wp_enqueue_style('vform_dev_style2', VFORM_PLUGIN_URL."assets/css/fontawesome.css");
		wp_enqueue_script('vform_dev_script', VFORM_PLUGIN_URL."assets/js/custom.js", array(),'1.0.0',false);
		wp_enqueue_script('vform_dev_script2', VFORM_PLUGIN_URL."assets/js/jquery-ui.min.js", array(),'1.0.0',false);
		wp_localize_script('vform_dev_script','ajax_object',admin_url("admin-ajax.php"));
	}

	//ADMIN MENU
	add_action('admin_menu','vform_plugin_menu');
	function vform_plugin_menu(){

		add_menu_page('Vform','vform','manage_options','vform','vform_options_func',$icon_url=VFORM_PLUGIN_URL."assets/images/vform-icon1.svg",$position=null);

	}

	function vform_options_func(){
		include VFORM_PLUGIN_PATH."inc/vformadmin.php";		
	}

	add_action('init', 'vform_init');

	function vform_init(){
		add_shortcode('vform','vform_my_shortcode');
	}

	function vform_my_shortcode($atts){

		$atts = shortcode_atts(array(
			'id' => '',
		), $atts, 'vform');

		ob_start();
		include VFORM_PLUGIN_PATH."inc/vformstructure.php";
		return ob_get_clean();
	}

	// form save

	function vformchkretundata($vfmvl){
		if($vfmvl=='{admin_email}'){
			return sanitize_email($vfmvl);
		}else if(substr($vfmvl,0, 9)=='{email_id'){
			return sanitize_email($vfmvl);
		}else{
			return sanitize_email($vfmvl);
		}
	}

	add_action('wp_ajax_myvformsave','myvformsave');

	function myvformsave(){
		if($_REQUEST['param']=='save_vform'){

			global $wpdb;
			$prefix = $wpdb->prefix;
			$table = $prefix.'vform';
			$data = array(
			"formname"=>sanitize_text_field($_REQUEST['formname']),
				"formdescription"=>sanitize_text_field($_REQUEST['formdescription']),
				"formbody"=>sanitize_text_field( htmlentities($_REQUEST['formbody'])),
				"confirmation"=>sanitize_text_field($_REQUEST['selectedinfo']),
				"confirmation_value"=>sanitize_text_field( htmlentities($_REQUEST['wherego'])),
				"status"=>sanitize_text_field($_REQUEST['formstatus']),
				"notification_mode"=>sanitize_text_field($_REQUEST['notification_mode']),
				"send_to"=>vformchkretundata($_REQUEST['send_to']),
				"email_subject"=>sanitize_text_field($_REQUEST['email_subject']),
				"from_name"=>sanitize_text_field($_REQUEST['from_name']),
				"from_email"=>vformchkretundata($_REQUEST['from_email']),
				"reply_to"=>vformchkretundata($_REQUEST['reply_to']),
				"message"=>sanitize_text_field($_REQUEST['message'])
			);

			$edtid = sanitize_text_field($_REQUEST['editid']);
			$where = array( 'id' => $edtid);
			$wpdb->update($table, $data,$where);
			echo json_encode(array("status"=>1,"message"=>"Data update successful","id"=>esc_html($edtid)));

		}
		wp_die();

	}

	// form save

	// create form

	add_action('wp_ajax_myvformcreate','myvformcreate');

	function myvformcreate(){
		if($_REQUEST['param']=='create_vform'){

			global $wpdb;
			$prefix = $wpdb->prefix;
			$table = $prefix.'vform';
			$data = array(
				"formname"=>"Untitled Form",
				"formdescription"=>"",
				"formbody"=> "&lt;div class=\&quot;form-all vform-mainfields-inside\&quot;&gt; &lt;/div&gt;",
				"confirmation"=> "message",
				"confirmation_value"=> "Thanks for contacting us! We will be in touch with you shortly.",
				"status"=> "true",
				"notification_mode"=> "1",
				"send_to"=> "",
				"email_subject"=> "New Entry",
				"from_name"=> "Admin",
				"from_email"=> "{admin_email}",
				"reply_to"=> "",
				"message"=> "{all_fields}"

			);
			$wpdb->insert($table, $data);

			$getid = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}vform ORDER BY id DESC LIMIT 1", OBJECT );

			foreach ( $getid as $keyid=>$valueid ) {
				$idget = $getid[$keyid]->id;
			}
			echo json_encode(array("status"=>1,"message"=>"create successful","id"=>esc_html($idget)));
		}
		wp_die();

	}

	// create form

	//for delete
	
	add_action('wp_ajax_myvformdelete','myvformdelete');

	function myvformdelete(){
		if($_REQUEST['param']=='save_vform'){

			global $wpdb;
			$prefix = $wpdb->prefix;
			$table = $prefix.'vform';
			$where = array( 'id' => sanitize_text_field($_REQUEST['id']) );
			$wpdb->delete($table, $where);
			echo json_encode(array("status"=>1,"message"=>"Data Delete successful"));
		}
		wp_die();

	}

	//for delete

	//clone form

	add_action('wp_ajax_myvformclone','myvformclone');

	function myvformclone(){
		if($_REQUEST['param']=='clone_vform'){

			global $wpdb;
			$prefix = $wpdb->prefix;
			$table = $prefix.'vform';
			
			$getid = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}vform where id='".$_REQUEST['id']."'", OBJECT );

			foreach ( $getid as $keyid=>$valueid ) {
				$data = array(
					"formname"=> $getid[$keyid]->formname.' copy',
					"formdescription"=>$getid[$keyid]->formdescription,
					"formbody"=> $getid[$keyid]->formbody,
					"confirmation"=> $getid[$keyid]->confirmation,
					"confirmation_value"=> $getid[$keyid]->confirmation_value,
					"status"=> $getid[$keyid]->status,
					"notification_mode"=> $getid[$keyid]->notification_mode,
					"send_to"=> $getid[$keyid]->send_to,
					"email_subject"=> $getid[$keyid]->email_subject,
					"from_name"=> $getid[$keyid]->from_name,
					"from_email"=> $getid[$keyid]->from_email,
					"reply_to"=> $getid[$keyid]->reply_to,
					"message"=> $getid[$keyid]->message
	
				);
			}

			$wpdb->insert($table, $data);

			echo json_encode(array("status"=>1,"message"=>"clone successful"));

		}
		wp_die();

	}

	//clone form


	// fontend save

	add_action('wp_ajax_myvformfrontsave','myvformfrontsave');
	add_action('wp_ajax_nopriv_myvformfrontsave','myvformfrontsave');

	function vformgeneratearrformat($tags){
		if (is_array($tags)) {
			$tag = implode("~",$tags);
		}
		return sanitize_text_field($tag);
	}

	function vformgeneratearrformatemail($tags){
		if (is_array($tags)) {
					$tag = implode("~",$tags);
		}
		return sanitize_email($tag);
	}

	function vformhtml_entity_decode($v1){
		foreach ($v1 as $key => $value) {
				$v1 .= $value[$key].",";
		}
		$v1 = rtrim($v1,",");
		return $v1;
	}

	function myvformfrontsave(){

		if(!isset($_REQUEST['vfm-nonce']) || !wp_verify_nonce($_REQUEST['vfm-nonce'],'myvformfrontsave') ){
			wp_send_json_error([
				'status'=>'0'
			]);
		}

		if($_REQUEST['param']=='save_vform'){

			$idd = 'multiplechoice'.sanitize_text_field($_REQUEST['formid']);
			$multiplechce = sanitize_text_field($_REQUEST[$idd]);

			global $wpdb;
			$prefix = $wpdb->prefix;
			$table = $prefix.'vform_userinput';

			$fieldsinput = array(
				'firstname', 'middlename', 'lastname', 'singleline', 'paragraph', 'dropdown',
				'multiplechoice', 'checkbox', 'number', 'email', 'websiteurl', 'full_address',
				'city_name', 'state_name', 'zip_code', 'shipping_country', 'phone', 'password',
				'datetime', 'termscondition', 'date', 'time', 'month', 'week', 'color'
			);

			$mainbodycnt = array();
			foreach ($fieldsinput as $k => $vi) {
				$mainbodycnt[$vi] = vformgeneratearrformat($_REQUEST[$vi]);
			}
			$mainbodycnt = http_build_query($mainbodycnt);

			$data = array(
			"formid"=>sanitize_text_field($_REQUEST['formid']),

				"maindatabody"=>$mainbodycnt,
				"ip"=>sanitize_text_field($_REQUEST['ip']),
				"browser"=>sanitize_text_field($_REQUEST['browser']),
				"currentdate"=>sanitize_text_field($_REQUEST['currentdate']),
				"timezone"=>sanitize_text_field($_REQUEST['timezone']),
				"currentdate_part2"=>sanitize_text_field($_REQUEST['currentdate_part2']),
				"usertimetakes"=>sanitize_text_field($_REQUEST['usertimetakes']),
			);
			$wpdb->insert($table, $data);

			$ffmid = sanitize_text_field($_REQUEST['formid']);
			$getdata = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}vform WHERE id='".$ffmid."'", OBJECT );

					foreach ( $getdata as $keydata=>$valuedata ) {
						$data_conf1 = $valuedata->confirmation;
						$data_conf2 = $valuedata->confirmation_value;

						$mlsendinp1 = $valuedata->notification_mode;
						$mlsendinp2 = $valuedata->send_to;
						$mlsendinp3 = $valuedata->email_subject;
						$mlsendinp4 = $valuedata->from_name;
						$mlsendinp5 = $valuedata->from_email;
						$mlsendinp6 = $valuedata->reply_to;
						$mlsendinp7 = $valuedata->message;
					}

					if($mlsendinp1=='1'){
						$admin_email = get_option( 'admin_email' );
						if($mlsendinp2==''){
							$mlsendinp2='{admin_email}';
						}
							if($mlsendinp2=='{admin_email}'){
								$to = $admin_email;
							}else if(substr($mlsendinp2,0, 9)=='{email_id'){

								$to = is_array($_REQUEST['email']) ? vformgeneratearrformatemail($_REQUEST['email'])[0] : vformgeneratearrformatemail($_REQUEST['email']);

							}else{
								$to = $admin_email;
							}

							$subject = $mlsendinp3;
							$message ="";
							if(substr($mlsendinp5,0, 9)=='{email_id'){

								$to2 = is_array($_REQUEST['email']) ? vformgeneratearrformatemail($_REQUEST['email'])[0] : vformgeneratearrformatemail($_REQUEST['email']);

							}else{
								$to2 = "info@".substr(get_site_url(),8);
							}
							$headers[] = 'From: '.$mlsendinp4.' <'.$to2.'>';

							if($mlsendinp7=='{all_fields}'){


								$fields = array(
									'firstname', 'middlename', 'lastname', 'singleline', 'paragraph', 'dropdown',
									'multiplechoice', 'checkbox', 'number', 'email', 'websiteurl', 'full_address',
									'city_name', 'state_name', 'zip_code', 'shipping_country', 'phone', 'password',
									'datetime', 'termscondition', 'date', 'time', 'month', 'week', 'color', 'hidden'
								);
								
								foreach ($fields as $field) {
									${'v'.$field} = vformgeneratearrformat($_REQUEST[$field]);
									if (is_array(${'v'.$field})) {
										${'v'.$field} = vformhtml_entity_decode(${'v'.$field});
									}
								}
								
								
								for($i=1; $i<=25; $i++) {
									if(!empty(${'v'.$i})) {
									   $message .= ${'v'.$i}."<br>";
									}
								 }
								

							}else if(strpos($mlsendinp7, '{') !== false){

								if(strpos($mlsendinp7, '{name_id=') !== false){
									$prefix = '{name_id=';
									$newvl = vformgeneratearrformat($_REQUEST['firstname']).' '.vformgeneratearrformat($_REQUEST['middlename']).' '. vformgeneratearrformat($_REQUEST['lastname']);
									$newvl = str_replace('~', '', $newvl);
									$mlsendinp7 = substr_replace($mlsendinp7, $newvl, strpos($mlsendinp7, $prefix), 15);
								}
								
								if(strpos($mlsendinp7, '{address_id=') !== false){

									$prefix = '{address_id=';
									$newvl = vformgeneratearrformat($_REQUEST['full_address'])."<br>"
											.vformgeneratearrformat($_REQUEST['city_name'])."<br>"
											.vformgeneratearrformat($_REQUEST['state_name'])."<br>"
											.vformgeneratearrformat($_REQUEST['zip_code'])."<br>"
											.vformgeneratearrformat($_REQUEST['shipping_country'])."<br>";
								
									if(strpos($newvl, '~') !== false) {
										$updtnewvl = implode("<br>", array_map(function($x) {return trim($x);}, explode("~", $newvl)));
									} else {
										$updtnewvl = $newvl;
									}
									
									$mlsendinp7 = substr_replace($mlsendinp7, $updtnewvl, strpos($mlsendinp7, $prefix), 18);
									
								}

								$types = ['singleline', 'paragraph', 'dropdown', 'multiplechoice', 'checkbox', 'number', 'websiteurl','phone','password','datetime','hidden','termscondition','date','time','month','week','color'];
								$valuespr = [20,20,19,25,19,17,21,16,19,19,17,25,15,15,16,15,16];

								foreach ($types as $index => $type) {
									$prefix = '{' . $type . '_id=';
									if (strpos($mlsendinp7, $prefix) !== false) {
										$newvl = vformgeneratearrformat($_REQUEST[$type]);
										$newvl = str_replace('~', '', $newvl);
										$mlsendinp7 = substr_replace($mlsendinp7, $newvl, strpos($mlsendinp7, $prefix), $valuespr[$index]);
									}
								}

								if($mlsendinp7==''){
									$prefix = '{email_id=';
									$newvl = vformgeneratearrformatemail($_REQUEST['email']);

									$newvl = str_replace('~', '', $newvl);
									$mlsendinp7 = substr_replace($mlsendinp7, $newvl, strpos($mlsendinp7, $prefix), 16);
								}

								$message = $mlsendinp7;

							}else{

								$fields = array('firstname', 'middlename', 'lastname', 'singleline', 'paragraph', 'dropdown', 'multiplechoice', 'checkbox', 'number', 'email', 'websiteurl', 'full_address', 'city_name', 'state_name', 'zip_code', 'shipping_country', 'phone', 'password', 'datetime', 'termscondition', 'date', 'time', 'month', 'week', 'color', 'hidden');
								foreach ($fields as $field) {
									$var = vformgeneratearrformat($_REQUEST[$field]);
									if (is_array($var)) {
										$var = vformhtml_entity_decode($var);
									}
									${'v'.$field} = $var;
								}

								for($i=1; $i<=25; $i++) {
									if(!empty(${'v'.$i})) {
									   $message .= ${'v'.$i}."<br>";
									}
								 }

								$mlsendinp7 = str_replace("{all_fields}",$message,$mlsendinp7);
								$message = $mlsendinp7;
							}
							// $headers[] = 'Cc: John Q Codex <jqc@wordpress.org>';

							function vform_set_content_type(){
								return "text/html";
							}
							add_filter( 'wp_mail_content_type','vform_set_content_type' );

							$tst = wp_mail( $to, $subject, $message, $headers, array() );
							$tst2 = array($to,$subject,$message,$headers);
					}




			echo json_encode(array("status"=>1,"message"=>"Data inserted successful","confirmation"=>esc_html($data_conf1),"confirmation_value"=>esc_html($data_conf2),"mailsent"=>esc_html($tst),"mailgo"=>esc_html($tst2)));
		}
		wp_die();

	}

	// formfront save


	
	add_action('wp_ajax_myvformsend','myvformsend');

	function myvformsend(){
		if($_REQUEST['param']=='save_vform'){

			$to = 'vforminfo@gmail.com';
			$admin_email = get_option( 'admin_email' );
			$where = substr(get_site_url(),8);
			$headers[] = 'From: Wordpress <info@'.$where.'>';
			$subject  = 'Subscription For VFORM';
			$message .= 'Email: '.$admin_email.' | Email2: '.get_bloginfo( 'admin_email' ).' | Name: '.get_bloginfo( 'name' ).' | Description: '.get_bloginfo( 'description' ).' | Wpurl: '.get_bloginfo( 'wpurl' ).' | Url: '.get_bloginfo( 'url' ).' | version: '.get_bloginfo( 'version' );

			function vform_set_content_type(){
				return "text/html";
			}
			add_filter( 'wp_mail_content_type','vform_set_content_type' );

			$tst = wp_mail( $to, $subject, $message, $headers, array() );

			global $wpdb;
			$prefix = $wpdb->prefix;
			$table = $prefix.'vfsubscr';
			$data = array(
			"subscription"=>1
			);
			$wpdb->insert($table, $data);

			echo json_encode(array("status"=>1,"message"=>"Data inserted successful"));
		}
		wp_die();

	}
