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
define('DB_NAME', 'lifebook');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         '$<AmPy$#F,X&29A~bv2Ep<>flW$rF$L`>f!tRQs*tb!t=h{o(R@XJ?:^! ili|)!');
define('SECURE_AUTH_KEY',  'h)M4W{kz^6UJPm.$ubJ<r+pUn?Oe3vPil $Wg9s%pg`M;wra5~Nj0*)pEadJ>+G[');
define('LOGGED_IN_KEY',    '+5!Pa+bk]VmA~ =<SE295q4*<Fg7H^S/,f 1g.hs`WbnIz[LyHh)nf!AZ>&Kgr{X');
define('NONCE_KEY',        'hb:`Yu5bI1^!+*#W/%T8mlmPh6<bVlJsrT^~Q*Bp_(APU.1 iKOsLpqrO+[4C)4Y');
define('AUTH_SALT',        ',oI1452q[XK7i>|U{UNqVcsO-a?IUV6xOz+s`s3p){p+DvZAGrPefGc+sQE_^.oZ');
define('SECURE_AUTH_SALT', 'fu6[<gWLk9rPWDhnx:.F)0/H<JE > VQ!RC/!x3U.Q|=_qKhgRFoPF6+uUFKvKFM');
define('LOGGED_IN_SALT',   'E)j0gCoQS`jQUFu[W&kali<hm+cO&TNBvw2[:C^ysMFjp$b/ |=MrXA[3KT<1NO]');
define('NONCE_SALT',       ',8^yfQ41AfG&lWy*cew3ad6+0Ru`)(tGY!&%OV!@9DRNtc:pk}]ds@+{rM}wi,1M');

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
//define('WP_DEBUG', false);

ini_set('log_errors','On');
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('FS_METHOD','direct');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
