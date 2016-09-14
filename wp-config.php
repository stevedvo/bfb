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
define('DB_NAME', 'steve');

/** MySQL database username */
define('DB_USER', 'steve');

/** MySQL database password */
define('DB_PASSWORD', 'MySQLDV01511!');

/** MySQL hostname */
define('DB_HOST', 'cust-mysql-123-19');

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
define('AUTH_KEY',         '82tEn?lUKY*0<1.4wZk.W:#XuH;>JAiYl2M[GA]=A0A3mPf{]Kvc[eZpb(X0zd.y');
define('SECURE_AUTH_KEY',  'SbM1u%wrG{3?:.|GzmM!GMm)tbml_,GIV_t]5)qOsFs$(B|Gs0<kW +!8($`e=E|');
define('LOGGED_IN_KEY',    'C2^?hnhZnh^i93hUGVN-G= ocC2SBrP7F-)d;b1!EK%W@(})SKQu~Du9QpU)!1.u');
define('NONCE_KEY',        '9J16EpUmN.s|G1J&IYgNo^b*TD8sGwX/;PDeY_6rnppkI-^LgLuG;,8Jq$)&0D4s');
define('AUTH_SALT',        '){u;gFX,n}$~?Q)`xqaLm<ef)]<Lo1/DS-N@ jGJ=(Wn1~(o:3^Xhj-}ug{;/R=C');
define('SECURE_AUTH_SALT', ';]/8^bbA+PkD%&goh.!dEXrw.d4=3P!]EX0hR-i/[_>yiijATc.R@]_VvRKza?m/');
define('LOGGED_IN_SALT',   '+3&`#h[mXJ$z>yH@:Kei8~:!$e/eqn}>40j;odtG]W%n(>g#~@G5P}JbZ+.wo_x*');
define('NONCE_SALT',       'B~Psj7Zz}RllZ?`*}Pt:f1^09ZM0*AJlhA0w;hB6-$j^=0cO<pE}}^TyBbm&Q+;+');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'bfb_';

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
