<?php
defined('ABSPATH') || die("Nice try");


register_activation_hook(VFORM_PLUGIN_FILE, function(){
	global $wpdb;

	$table_name = $wpdb->prefix . 'vform';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id int(11) NOT NULL AUTO_INCREMENT,
		formname varchar(200) NOT NULL,
		formdescription varchar(1000) NOT NULL,
		formbody longtext NOT NULL,
		status varchar(50) NOT NULL,
		confirmation varchar(200) NULL NULL,
		confirmation_value mediumtext NULL NULL,
		notification_mode varchar(100) NULL NULL,
		send_to varchar(200) NULL NULL,
		email_subject varchar(500) NULL NULL,
		from_name varchar(200) NULL NULL,
		from_email varchar(200) NULL NULL,
		reply_to varchar(200) NULL NULL,
    message longtext NULL NULL,
    datesubmit timestamp NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";


  // secondtable

  $table_name2 = $wpdb->prefix . 'vform_userinput';
	$charset_collate2 = $wpdb->get_charset_collate();

	$sql2 = "CREATE TABLE $table_name2 (
		id int(11) NOT NULL AUTO_INCREMENT,
		formid varchar(100) NOT NULL,
		maindatabody mediumtext NOT NULL,
		ip varchar(300) NULL NULL,
        browser varchar(300) NULL NULL,
        currentdate varchar(300) NULL NULL,
        timezone varchar(300) NULL NULL,
        currentdate_part2 varchar(300) NULL NULL,
        usertimetakes varchar(300) NULL NULL,
		PRIMARY KEY  (id)
	) $charset_collate2;";


	// thirdtable

	$table_name3 = $wpdb->prefix . 'vfsubscr';
	$charset_collate3 = $wpdb->get_charset_collate();

	$sql3 = "CREATE TABLE $table_name3 (
		id int(11) NOT NULL AUTO_INCREMENT,
		subscription int(11) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate3;";



	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  	dbDelta( $sql );
	dbDelta( $sql2 );
	dbDelta( $sql3 );
});

