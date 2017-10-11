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
define('DB_NAME', 'myvacayvalet_staging');

/** MySQL database username */
define('DB_USER', 'shinesan_wp2');

/** MySQL database password */
define('DB_PASSWORD', 'E[EG0gBxQL71[[3');

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
define('AUTH_KEY',         '96JbU-Rp>F87;k37wA7)GkLS=x9mq!yRlNy7gx?k<K.<?hknX#C3ItDP>@3ycREt');
define('SECURE_AUTH_KEY',  'uQ~LQ:<V9H( M7bag};b9awqG3#CuW#$X}Q2NO-YUpd66Z<7`/ CLGPVD7~eJ0LW');
define('LOGGED_IN_KEY',    'bGU~]%|u_3cAklzCR=uq=h @cam5|Swb/:18)&|CW8qBB6J-1pmn]UW]LgODg}S+');
define('NONCE_KEY',        '}u~]EdnnQvXm|6(_*7sAM9jOhj^^W/P`^kxC1M?#/`zDM<v}!!D%baAtq-2$yBU[');
define('AUTH_SALT',        'ivBl10Bjp,43O(>[;SXU0yl@G{e&q3W[g@-J7Tm&RtH~6(@dMWt#an>y+(vEz8N9');
define('SECURE_AUTH_SALT', '[MJU<Jh(SBS>o8zfc%(Lg+WUd8IO&gu9Tl}?kJZQF7%~P[VFw(PsNqe$=KW#;<sI');
define('LOGGED_IN_SALT',   'p5Bsn$?`MJ+p?vD@uO@r0BP}($LAAIrs&Xk36O~Kkv;xshyN(5.),U).+${S^rG[');
define('NONCE_SALT',       '5fgfHq}[3ze9`4KwC,!Uu%EEnO^Y$;S#O]$<9mgrLm>vac.0I!=RvvZvWbJ+Xza{');
define( 'WP_MEMORY_LIMIT', '256M' );
define( 'WP_POST_REVISIONS', 5 );


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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
