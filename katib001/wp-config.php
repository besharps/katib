<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'katib001_db');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'katib001_db_adm');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', 'katib001dbadm');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8mb4');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'uOl!,Y;6~{Tnf4G~oovVo(hd*y(4,@{;=|3U*q-}rTS%!8BYe<X^L}]~<}N^5P|z');
define('SECURE_AUTH_KEY', '[2K0X,t;[j,b/lTYbZH($|LEa1CX8kt<6u8F>bFht[UQv}ENif76Bc{g1F5SmozL');
define('LOGGED_IN_KEY', '[-P68p{o6/GP}x:h+d.oz-EjTQeLCR;6W1|=XR]1g7^mbKYEb0o0]v+@[RkyqK|]');
define('NONCE_KEY', 'WgQTV6ip[ZV]Vg*S8X@J|hjLc?CKa=!8A[7G9Jpa!d1/P7^U93wD+JT+rdScErR.');
define('AUTH_SALT', 'gg7A6Sui;}#zH+WRd_@`NUlJ@cv=ihe&4Q9SG{9Dv^}eR@[d=|@,GRf1;<dM[Ahv');
define('SECURE_AUTH_SALT', 'T{]iJ#g7WaIrYq^uE?fumk-T=o`TKTF{jI9Jr;Y.[4%5s>5MeAaWs`V.-M~|m8C6');
define('LOGGED_IN_SALT', '3TX8?S,w.%z|eu[$Z!Tr^OU17~+W!2Cas**9[:9ClhN(p_%M#f:-<8Jt$>.GnP-I');
define('NONCE_SALT', 'DSl!e.){-f673x`JFP Y9/2vQm%v9{Sjjf&~eR6ln(>)+1{7{[O._p;O~##)]5<Q');

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'kt001_';


/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');


/** cambios para usar proxy 
define('WP_PROXY_HOST', 'proxyad2.agea.com.ar');
define('WP_PROXY_PORT', '8080');
define('WP_PROXY_USERNAME', 'pvivona');
define('WP_PROXY_PASSWORD', 'P$BL=012');
define('WP_PROXY_BYPASS_HOSTS', '*agea.com.ar, *agea.sa');
**/


