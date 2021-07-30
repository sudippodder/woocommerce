<?php
ini_set('memory_limit', '1024M');

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

define('DB_NAME', 'cnp670_tonykassel');

/** MySQL database username */
define('DB_USER', 'tonykassel');

/** MySQL database password */
define('DB_PASSWORD', 'Hjyt$s##dervb679');

/** MySQL hostname */
define('DB_HOST', 'database01.crv8lsudjvy5.us-east-2.rds.amazonaws.com');
/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
//define('UPLOADS', 'https://s3.us-east-2.amazonaws.com/hipstamp/');

define( 'AS3CF_SETTINGS', serialize( array(
    'provider' => 'aws',
    'access-key-id' => 'AKIAJZFME6DPPLT6TSJQ',
    'secret-access-key' => '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',
) ) );
	
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'lOpx+pvK5dik<<QW]`Ks(,an!!A3~7Q@{CP{[S_+#ew(K/t}RSF_ABk.&9s^PG`c');
define('SECURE_AUTH_KEY',  '2D|!q^T8*F_`7`QXUHSq>8!fb1An5Q.[Sw_<kRn&ZMO_G9Ud =%TAr:GCN+?`so0');
define('LOGGED_IN_KEY',    'LH{x<DGmz3lTlW4Oj$qZ3B[0$;)C7FyJfb{Oo|+rqqcR`dqe1$NhQ]f1Pr[uH#<)');
define('NONCE_KEY',        'l^z,qR`U}L;uY W8}[SrZ2* 8OZd=P[iC%Bw8e6BZC%fJSVXB$,D(Jm&U1^Fd1s=');
define('AUTH_SALT',        'vc!eiG-*/;J4Q]!DJ/OjZq[ru?c,F[C*kTcIP,n d5V$+N,-zzP,8sKD0v/yZAhH');
define('SECURE_AUTH_SALT', 'Y<uK_?S%MV/v`MQ/e4dr+sKatANZleS[%oUVULQh%L+GZqz6{c[Tcf{XZp6F(Svj');
define('LOGGED_IN_SALT',   'iFqI|4 8&TS2{$l?yB^5q_]gdL0:Uxe(}EGsv@#QO8UXAwdn65m8c,miwQ8G]04}');
define('NONCE_SALT',       '!rs!FRu0M`>+14=rIH^NT~;|olHR%wX``i@ZJDqR%:!w^U`TF-5yQUq` _.Wf>~y');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);
define('FS_METHOD', 'direct');
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

