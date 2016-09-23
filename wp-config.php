<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'tmd');

/** MySQL database username */
define('DB_USER', 'wp');

/** MySQL database password */
define('DB_PASSWORD', 'qazplm');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         '@$%Wu4GXCKnf9/e?v;QcimSzO@TNmqxxY3k@lq&/RO5|9GbHCozj6`~XrO2$*JAx');
define('SECURE_AUTH_KEY',  'su18ui^/ix$M@L)5?gDe)rHdc|Jjdr(AF_TllR)RgwRaW2;3`4Tn:|KqieS*WYhk');
define('LOGGED_IN_KEY',    'M+RZc(z5nNKgnY0D3fe;Xfg%$rq9gxEyu`zc@?g*hPzz?*NjWR5Ml0`63:at+T%K');
define('NONCE_KEY',        '$onei*(?M8z+EJrVx8"(SJ`GB/ItbJ0D#6PZ7t^^z53y&qM6|"hMz5yWob/004RE');
define('AUTH_SALT',        '4iVfEnX(w`F(7rAy4N)VVITM^L35+~3AfBMIWs:7oE@T4qyXgO:sf`6wGa(jhUnX');
define('SECURE_AUTH_SALT', 'vgh?!IJk$`V%X:?F/)+Bg/Blo&GBincl8&cB&lHE/(ne8CfN;QL&_GO&c5@?jggJ');
define('LOGGED_IN_SALT',   'Befa5nLswAnOCpr^V?xDWp:1NNfG6R1&)794540A)kQYBGUYozj@|5E@x0!2GRE:');
define('NONCE_SALT',       'y$rp+ri!VUDu/6JOX+lYk9Jf~0cg)S5A+#r(`8A"Fe#BCDuZ8(lKF"Ey_xM3!|pT');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_3ejss3_';

/**
 * Limits total Post Revisions saved per Post/Page.
 * Change or comment this line out if you would like to increase or remove the limit.
 */
define('WP_POST_REVISIONS',  10);

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

