<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass(DashedRoute::class);
Router::extensions(['json', 'xml']);
//Router::extensions('json', 'xml');
Router::scope('/', function (RouteBuilder $routes) {
    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Users', 'action' => 'index', 'home']);

    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);
//    $routes->connect('/ProjectConfig', array('controller' => 'Projectmaster', 'action' => 'index'));
//    $routes->connect('/ProjectConfig/index/*', array('controller' => 'Projectmaster', 'action' => 'index'));
    $routes->connect('/ProjectType', array('controller' => 'Projecttypemaster', 'action' => 'index'));
    $routes->connect('/ProjectType/edit/*', ['controller' => 'Projecttypemaster', 'action' => 'edit']);
    $routes->connect('/ProductionFieldsMapping', array('controller' => 'ProductionFieldsMapping', 'action' => 'index'));
    $routes->connect('/OptionMaster', array('controller' => 'OptionMaster', 'action' => 'index'));
    $routes->connect('/OptionMasterMapping', array('controller' => 'OptionMasterMapping', 'action' => 'index'));
    $routes->connect('/OptionMaster/index/*', ['controller' => 'OptionMaster', 'action' => 'index']);
    $routes->connect('/OptionMasterMapping/index/*', ['controller' => 'OptionMasterMapping', 'action' => 'index']);
    $routes->connect('/OutputMapping', array('controller' => 'OutputMapping', 'action' => 'index'));
    $routes->connect('/AutoSuggestion', array('controller' => 'AutoSuggestion', 'action' => 'index'));
    $routes->connect('/UniqueIdFields', array('controller' => 'UniqueIdFields', 'action' => 'index'));
    $routes->connect('/UniqueIdFields/index/*', ['controller' => 'UniqueIdFields', 'action' => 'index']);
    $routes->connect('/Getjobnoncoreview/index/*', ['controller' => 'Getjobnoncoreview', 'action' => 'index']);
    $routes->connect('/Getjobcoreview/index/*', ['controller' => 'Getjobcoreview', 'action' => 'index']);
    $routes->connect('/Getjobhooview/index/*', ['controller' => 'Getjobhooview', 'action' => 'index']);
    //$routes->connect('/getjobnoncoreview', ['controller' => 'ProductionView', 'action' => 'index']);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks(DashedRoute::class);
});
/**
 * Load all plugin routes.  See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
