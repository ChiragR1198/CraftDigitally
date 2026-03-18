<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'Yv.xIPgwaXMhZOMN)m7i8m9E%+hO;lem$JjJYJho*$}Xxr!r2)&(kMNF6MBN!]Bb' );
define( 'SECURE_AUTH_KEY',   '*n9t=-{E8!~.((QT13*|ecs%wd|_>/Yf~W3PRO6G}7c!x^0~O1)g1<>8UayD`(BD' );
define( 'LOGGED_IN_KEY',     'NTGS8/DRLR)xgP} BH=ipLw`IoF>Z*^q%<2T|L!!<TdGumkUW:lH&N 5X1$~21sq' );
define( 'NONCE_KEY',         '[ZM]h1xX151* ,!IiHNjOOSf46V~O1Of?AV{oC_`;)o6lE/BDfSO>>QOq|SwXSq+' );
define( 'AUTH_SALT',         'tZHZQ BfX)CTO>]GsFYCXu+eKC]k]HO.e[GW}Tx>1bTh{-oQF>xObDX=vr>NiL}1' );
define( 'SECURE_AUTH_SALT',  'dPKi8i*%;Df6FZi6,Dq~Yr7x`3VfPd$)-/eWxPpuG4Wi<t6wdaQ}5<~;DS~,b&TR' );
define( 'LOGGED_IN_SALT',    'lT^>G)39M7lH{BmF~[S66_gF=Xp42aV< 2o uvfMV-n(`X4TL()8TUgZ>.dbrLcV' );
define( 'NONCE_SALT',        'P[[&lqmORts5F`r!mJR;lKXws!Y@|*gceE,KakV/z*20T*&QI0VOe8$ipNg]LsDr' );
define( 'WP_CACHE_KEY_SALT', '.{{31E!Xp bfd1Y.*jA2.uLR5&f[<Qb;?FFQ1QnPiiM`2M9q`Fp`3IiN]%6AZ8{a' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
