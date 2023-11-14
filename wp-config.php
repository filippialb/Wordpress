<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'database1' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'BlZ`^vXh:um(GGTrdSzu;%7:aH8nN[^s;cr:bwE*u/$2h+iWPEy#~yh:=2<C#xC,' );
define( 'SECURE_AUTH_KEY',  '0O`>,IS-3v(evWpm@UP^v~~*&C7HrgAaG<7HKOz2^.<r QX!Co%n+TFqEt14(HR!' );
define( 'LOGGED_IN_KEY',    '|ArUlU++amDz{;ueo{6eve#lo1zk/pu `&ZU*s$1l];GU*-0@HA<+H(.CyiEL3VY' );
define( 'NONCE_KEY',        '.iP/h&!x>@`sLE$EZ_8Tu*$(wvUT:Q~Ll6B]HNX-vN6am?rtK~Uyb,!seLJrUzYn' );
define( 'AUTH_SALT',        '3iaaRTSaA.I^n*7xVt53;(3=mtU8qz%5W0xb=3RO K97QCmT+t6~Lpf b<om@r !' );
define( 'SECURE_AUTH_SALT', 'xxpT:sL>S 3qKj5IcWu_+!0UnPf-c}cW8xNt_3qj+0~[Bd>f~/f rzY7ZHl.KA_%' );
define( 'LOGGED_IN_SALT',   'V;l0_]kU>u9)T=bGrkPptle:1h:e6m4n-?!-d)*&]+&<TGRuit)DNdwL9Q<ntrG0' );
define( 'NONCE_SALT',       '>emF?c9>@:}YZ]*}ARa@}9H8@lfLI/DTp0*wSB(1.33CK@viU[i(=E>Dk57U|VXD' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
