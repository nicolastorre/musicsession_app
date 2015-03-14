<?php
/**
 * Music Session App index
 *
 * Index to launch the Music Session app 
 *
 * PHP version 5
 *
 *
 * @category   Test
 * @package    MusicSessionApp
 * @author     Nicolas Torre <nico.torre.06@gmail.com>
 * @copyright  2015 Nicolas Torre
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    1.0
 * @since      1.0
 */

/**
 * include the Classloader component.
 */
require_once 'Component/ClassLoader/ClassLoader.php';

$router = new Router(); // instanciation of the router
$router->queryRouter(); // routing the request

// php phpDocumentor.phar -d . -t docs/api --ignore "*/swiftmailer-master/,Twig/"

?>