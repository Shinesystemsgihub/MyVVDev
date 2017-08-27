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
define('DB_NAME', 'myvacayvalet_dev');

/** MySQL database username */
define('DB_USER', 'myvv');
// define('DB_USER', 'shinesan_wp2');
// 
/** MySQL database password */
define('DB_PASSWORD', '8h4z95');
// define('DB_PASSWORD', 'E[EG0gBxQL71[[3');

/** MySQL hostname */
define('DB_HOST', 'localhost');
// define('DB_HOST', 'myvacayvalet.com');

define('WP_ALLOW_REPAIR', true);

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
define('AUTH_KEY',         '+eb?{^BP_^5d(I#Pk@+CBs3~iSmr+ 9(~Tm@2 G-:@3)JW^xMR+7yv432^?cyLPo');
define('SECURE_AUTH_KEY',  'd0Q&g3*tv2i[k@8:{a:WJrXRKffYc> v,#nr*5Q.K#8t3YV87D(pH6$L NsOd,-[');
define('LOGGED_IN_KEY',    ' 6&fm9a{[%`Fp[6N!Wq:07s)mWgK-T/x!1&[MP?j]x8|MA25]2hZ7!998w,(h_(X');
define('NONCE_KEY',        '8_vPT[k,,^CGX`y-8mzoS`*MlPR3mdq5l|tA@8F9)jS2cpN{c%M|W,*Jp`D*F`?s');
define('AUTH_SALT',        '{mdr+HyFIzKh*.8xcCWde3QhLBMY#hbiA0Gk/)W3$!u84qi7>:oAy{DkI0AjRP1.');
define('SECURE_AUTH_SALT', '_ZWEsYp4wu)XJJPY]vh>{JlrP-<Oitn Ek)OaQOAob.}^b)<D!i8~4VIb%WJ?g6Y');
define('LOGGED_IN_SALT',   'J0& A8!Npbo+Y&.^n(~44x}p@<2=.>)a;.+t.NTi~#+|((7}m&cjP(/o:6+gl.aZ');
define('NONCE_SALT',       '(eTh,J^fX9@,m3ld>K-KsEfD`|*:~0* %qW)c{[m|q>2S>0P;:Z#9y,Hss[6vt0V');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'mvvp_';

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
define('WP_DEBUG', true);

/** defing('WP_ALLOW_REPAIR', true); */

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
