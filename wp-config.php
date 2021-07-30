<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
//define( 'DB_NAME', 'hipstamp' );
define('DB_NAME', 'woocommerce');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'sudip');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '_Q<sCQp#{5M^fe8i)Th|E*az_)irFo&o5Ag.N1t9z@.gLH]*9T<JZ4xX-rrR|c8M');
define('SECURE_AUTH_KEY',  'viS<y9G3cp%p{/31fa%L4ioj6mX4_F)Q6.A$E)cl?,2iKx3}K$pKtc|!QUl`uS2}');
define('LOGGED_IN_KEY',    'ZtK<`@#r-h~Q:y:-=v3e4,+&AF!z0},o4hMz5XV7WUPx7Yr%X(|oi`|Yc=)zHq4B');
define('NONCE_KEY',        'xKA<[%u_|KU5%5cgNQQi1LT#tent4bMd%%nZ+]cdwXLF*0)gtDP*E^XDt!_#%jal');
define('AUTH_SALT',        '5DcQ_AOx1qKPa%WD?DY|hN60Jw17;}g7&79G|P[AuxIL[R25f/Wp%~qGP8_a962c');
define('SECURE_AUTH_SALT', '0c,Uz%Q2.!=i_Z?nz5QN%da;vQ[l+g|0B?rrwBB;;p4eO+%-v&j)oI||o_zT_Kyk');
define('LOGGED_IN_SALT',   '~BV+AFt@K7[HQlroTOc&7[u=Q~RJ|$|&N3]ZaY%Rs2xjYKqEE+.TXA[nEWhm{J<%');
define('NONCE_SALT',       '|Bfw{Jdf~n2[sWTZu:JA1[n8M^OdkruS!IPWz*Gi9aIcVFaFVwscxCPUV>@#K^h~');

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */

define('FS_METHOD', 'direct');


define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);
@ini_set('display_errors', E_ALL);
define('WP_DEBUG', true);
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
	define('ABSPATH', dirname(__FILE__) . '/');
}

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
