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
define('DB_NAME', 'dgzhlbvq_appsthergo_web');

/** MySQL database username */
define('DB_USER', 'dgzhlbvq_appsrot');

/** MySQL database password */
define('DB_PASSWORD', 'ZVLkC1-wpUJf');

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
define('AUTH_KEY',         'C+[>3_I1_a+U;ORr$}*NQg_sUP+ _> 23*q87aLq.g8+l:A<?|[S`GZ#Y;+;HC#|');
define('SECURE_AUTH_KEY',  'bbsn: 6,zM-xjL51=)&-05VvyG_J)Q-/)[DV}rE>{Y+2w}g|W$[B|3cFUE<|[qo(');
define('LOGGED_IN_KEY',    '7]SFSUPL}coi:W+suRTa5LdGW>wl|[kBuj%Wx8q&#(9!>Kry>]R`}Mkrb5+]!ZSu');
define('NONCE_KEY',        '!ap)[&y0.U8)c%J.Nu%Qwz[ T#z,iW+=Q8EYmnrBy]{s8o1Y7/b?Xd5~/^Z:^ww9');
define('AUTH_SALT',        's60(xZM-UUcN96^%0/nPG9wwY.VnS^Q#--xkzqH97m7ux:dXt@o^m}i0FZMIz-GN');
define('SECURE_AUTH_SALT', 'nm6mh:)a]_`OZhO&Im>K^:j!PQkl7_oH]m10~ u-DhZR9TRt%*BDM1}h]`eqtz~C');
define('LOGGED_IN_SALT',   'v[nk86*HR]HWB`[5p>E!+|e<U?7>SM7+dg`TObP:/HN`#Q^:8P 7UHbXM]},69DC');
define('NONCE_SALT',       'd&GO4nqM5]v{4|+bS+k1@vFLM__`0%OHNk,L.wZ_p9[?mvjj|W=munT/CmI/1=Rn');

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
