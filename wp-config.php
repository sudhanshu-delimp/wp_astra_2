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
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'astra' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
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
define( 'AUTH_KEY',         't 10Sr%9~B?)aRm*z$bGb{ 2q*cZ187O{yA]Xi?*fHmXnlT;HbNuXc}Pu>McWale' );
define( 'SECURE_AUTH_KEY',  ',TzeUYG3hnPLtNNqbf9NnV,%Do]Mq#j9fa@QufnjLeB=bFpb;M%V$5Ek6~ZL#RRW' );
define( 'LOGGED_IN_KEY',    '#j{N Me[rbnXf]T!(`xt@Z#xY`g7nh/Ocb=XCl1Cq>23JFO9U#*>^,;jt8h/|1xS' );
define( 'NONCE_KEY',        'og.6U$ENu$}b+~K}=Te8D^5Hr ~k^;*rEIn$J*_CV6jC~{&Y3 aT(W,iCStVJs^k' );
define( 'AUTH_SALT',        '^f+jrjc.*LV]Xde+VmB!A|&Lg1KK,PX|Xa_]- <B&M|q`UshH,[WZZX?;Mdqil~2' );
define( 'SECURE_AUTH_SALT', '|.&_qSw%J/qy#g<wzRyLTax:`jT(vypPB|Hyutg,XZ(unWVTbRm18h(1!96:zk|,' );
define( 'LOGGED_IN_SALT',   '0uuf.p t*m#rp3W%BnD&XkZLM|2WO?BWuZ*&!wi!YJ$(# )5s/0S5ZyqP.Pvs*RY' );
define( 'NONCE_SALT',       '2Njv`s_;<f/YD4}U!!5~H#-wJ5OLT8;Qdg4bcg|xhh7PyAG(%-6BH<3FMM0*t119' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
