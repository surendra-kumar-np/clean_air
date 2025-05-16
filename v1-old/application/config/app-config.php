<?php

defined('BASEPATH') or exit('No direct script access allowed');
/*
* --------------------------------------------------------------------------
* Base Site URL
* --------------------------------------------------------------------------
*
* URL to your CodeIgniter root. Typically this will be your base URL,
* WITH a trailing slash:
*
*   http://example.com/
*
* If this is not set then CodeIgniter will try guess the protocol, domain
* and path to your installation. However, you should always configure this
* explicitly and never rely on auto-guessing, especially in production
* environments.
*
*/

define('APP_BASE_URL', getenv('APP_BASE_URL'));

/*
* --------------------------------------------------------------------------
* Encryption Key
* IMPORTANT: Do not change this ever!
* --------------------------------------------------------------------------
*
* If you use the Encryption class, you must set an encryption key.
* See the user guide for more info.
*
* http://codeigniter.com/user_guide/libraries/encryption.html
*
* Auto added on install
*/
define('APP_ENC_KEY', 'f57bb50ea2267eeae602eb18711cae16');

/**
 * Database Credentials
 * The hostname of your database server
 */
//define('APP_DB_HOSTNAME', 'localhost:12345');

define('APP_DB_HOSTNAME', getenv('DB_HOSTNAME'));
/**
 * The username used to connect to the database
 */
//define('APP_DB_USERNAME', 'apagst_dbu');
define('APP_DB_USERNAME', getenv('DB_USERNAME'));
/**
 * The password used to connect to the database
 */

//define('APP_DB_PASSWORD', 'Tun8fb1z!D');

define('APP_DB_PASSWORD', getenv('DB_PASSWORD'));
/**
 * The name of the database you want to connect to
 */
//define('APP_DB_NAME', 'apagst_db');

define('APP_DB_NAME', getenv('DB_DATABASE'));

/**
 * The name of the database you want to connect to
 */

/**
 * @since  2.3.0
 * Database charset
 */
define('APP_DB_CHARSET', 'utf8');
/**
 * @since  2.3.0
 * Database collation
 */
define('APP_DB_COLLATION', 'utf8_general_ci');

/**
 *
 * Session handler driver
 * By default the database driver will be used.
 *
 * For files session use this config:
 * define('SESS_DRIVER', 'files');
 * define('SESS_SAVE_PATH', NULL);
 * In case you are having problem with the SESS_SAVE_PATH consult with your hosting provider to set "session.save_path" value to php.ini
 *
 */
//define('SESS_DRIVER', 'database');
//define('SESS_SAVE_PATH', 'sessions');

define('SESS_DRIVER', 'files');
define('SESS_SAVE_PATH', NULL);

/**
 * Enables CSRF Protection
 */
define('APP_CSRF_PROTECTION', false);
