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
define('DB_NAME', 'occasions-db');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'Jt[{Q*{OTg oGXR^@vNyneY{Mbd5gn /PxEOMLwNU%)5;EyXz8T01DHFuO qQmHt');
define('SECURE_AUTH_KEY',  'i.>;Od0Y%m3Yq!IOc!uX9x0Rr!lkp!qfQat[`JA{ac^gklIm-:(LCr9S7,p5,=%q');
define('LOGGED_IN_KEY',    '0hMq1jntr|{O%Pvsae]W/j^=&~ZVySwt`;+>q$o!B&-]!7875*YFwB56qzKz^~RU');
define('NONCE_KEY',        'h;EGgJ#;!NCG+Th!=NuXMK_1r7=)X.;d?U]5;^g`a9NmYe3zTdZA8>8f1b :z0.G');
define('AUTH_SALT',        '3!b.Z]Zus{|Z[<xN9,a9<3;Y}IP9DqCZQEJQm6U4_?p(@*1*3?w|M50>7}JPY@z{');
define('SECURE_AUTH_SALT', 'E_+|}.NW?OVI@V:?j} {y-0Q,4WH6V(+<N/5I}*}8d*y[=}fdmjb[N;6f~tC+C,{');
define('LOGGED_IN_SALT',   'Yw1Y.UBTu;96zbDM,$9 U1cbKZai_*,|}n>qc,o+V}]^hXdAC.<k2w6x:O[RK%6U');
define('NONCE_SALT',       ']xJ@aH9[I3cazXAVg?.WG-2l3Mbe9eg<i/aNK$nim`=1zO,|1/a?R?R?/$?*?xh%');

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
