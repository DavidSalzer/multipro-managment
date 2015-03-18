<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'multipro');

/** MySQL database username */
define('DB_USER', 'multipro');

/** MySQL database password */
define('DB_PASSWORD', 'hivsybjwdoqlextcmpag');

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
define('AUTH_KEY',         '`wIj6`777=<Lo@<|6p>5iT:0Vh.QU<F-EP4jXA|C]DkG2&}+DtGFH9q?t;,Ya[Dd');
define('SECURE_AUTH_KEY',  'TwIz+1-9v#M5%Cfn#K(i/YIMKQqT7~Ck+3$xPqg0CN[#JV w`wHi|H]GQ{0jIc[!');
define('LOGGED_IN_KEY',    'y:e,7z|6JqCH}~Z--(+ +E0miA>>?^*-&Mfv17FX),rn1d@AP_+i|=Nd6g(7L*V`');
define('NONCE_KEY',        'gw+cMK4D)opC#*diW:5jxzMa?z vH&w^|z*XR|W38lg*.7~t?a#Sl[O0_gV_-0Yj');
define('AUTH_SALT',        'ooT%+R5eWgOo;,:vILM-u6<.b/DY_r&9#cSS+[+3$=x,|Cf-_3zPgg.hB)Qg1BmB');
define('SECURE_AUTH_SALT', 'o[*21I-jhiX?40,VDw29;>--rl[[ga}@_5z!|B[+WP+1{n97a,Dk1sml|5b1Xwdz');
define('LOGGED_IN_SALT',   ';D*igwR<#woah9G(~=)ey)x3|VQ-|f+k]Tj9If]%s/L.6Kb&s{YRNXf0-!-Bvv=X');
define('NONCE_SALT',       ')|L5@N+vq]sI)9URIQC.3Z~d4L9/9t5y5k&kYZ|CmAh2^^%$T_uL9+u})iS5%4t7');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
