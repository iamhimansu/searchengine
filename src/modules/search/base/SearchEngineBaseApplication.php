<?php

namespace uims\searchengine\modules\search\base;

namespace uims\searchengine\modules\search\base;

use Yii;
use yii\base\Controller;
use yii\base\InvalidRouteException;
use yii\web\Application;

class SearchEngineBaseApplication extends Application
{

    public $layout = false;

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    /**
     * Runs a controller action specified by a route.
     * This method parses the specified route and creates the corresponding child module(s), controller and action
     * instances. It then calls [[Controller::runAction()]] to run the action with the given parameters.
     * If the route is empty, the method will use [[defaultRoute]].
     * @param string $route the route that specifies the action.
     * @param array $params the parameters to be passed to the action
     * @return mixed the result of the action.
     * @throws InvalidRouteException if the requested route cannot be resolved into an action successfully.
     */
    public function runAction($route, $params = [], $layout = true)
    {
        $parts = $this->createController($route);

        if (is_array($parts)) {
            /* @var $controller Controller */
            list($controller, $actionID) = $parts;
            $oldController = Yii::$app->controller;
            $controller->module->layout = Yii::getAlias('@enginelayout');
            if (!$layout) {
                /**
                 * Switching off layout of the whole module
                 * as we do not want any scripts, style
                 */
                $controller->module->layout = false;
            }
            Yii::$app->controller = $controller;
            $result = $controller->runAction($actionID, $params);
            if ($oldController !== null) {
                Yii::$app->controller = $oldController;
            }

            return $result;
        }

        $id = $this->getUniqueId();
        throw new InvalidRouteException('Unable to resolve the request "' . ($id === '' ? $route : $id . '/' . $route) . '".');
    }
}