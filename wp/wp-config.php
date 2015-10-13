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
define('DB_NAME', 'foreverframedb');

/** MySQL database username */
define('DB_USER', 'theforeverframe');

/** MySQL database password */
define('DB_PASSWORD', 'IWD4ever');

/** MySQL hostname */
define('DB_HOST', 'mysql.foreverframe.com');

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
define('AUTH_KEY',         '*TWMj-uMwUggEvxgT]uh7_{X|A8WvcN&Ru@;n,O@pat6Q$Z9NLY:#-1jX-JXUFtT');
define('SECURE_AUTH_KEY',  'M0%W7Rw]OV#+cg)SM]89q8!BI%;6w,D{tl^k.9SwHg)3eByO^9UFTV%-`gv~z*_W');
define('LOGGED_IN_KEY',    '`[>B5>YNi?@h=Uyx@@S+DQ7kWm{g[wNc>8*.Xh|-!!#b-B$ *R)SX4H_C<g[37i#');
define('NONCE_KEY',        '9tW2$5W^9S`24K!Rv1%RUEHh5%w0xa+7*1[Hv<F,:H*FM=;x0]3a,}sPaS0KC CQ');
define('AUTH_SALT',        '+J*&gCkj0LA3z4e$-,%Q.L-WT|-wLH,V345I/%H!U(^iO*?17l6QuE=m4GH>&ZI[');
define('SECURE_AUTH_SALT', 'FMKB]?7@j+H4*K*|ejaY+A^so1wl7:-/~L|pE[#;W6xbzY=8Q-k*/yQ-!^p4L=u@');
define('LOGGED_IN_SALT',   'Nuk%cl|2tf)m9I!7=GFf$dkMoqEn^|/JU=j~iZcBt0%NDt&<Mi<hExfe|x8G&xyk');
define('NONCE_SALT',       '1JnmH78o9L%JwcB4|#J404ubjWQ8WmGg`_3^VzSjy.I5HFg21L=$TD5OlGWpbg|9');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
