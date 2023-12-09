<?php

namespace uims\searchengine\modules\search;

use app\components\Constant;
use uims\searchengine\modules\search\models\MenuItems;
use Yii;
use yii\web\UnauthorizedHttpException;

/**
 * Search Engine module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'uims\searchengine\modules\search\controllers';
    public $defaultRoute = 'dashboard';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if (Constant::isSiteStudent()) {
            Yii::$app->getResponse()->redirect('/site')->send();
        }
        if (Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'uims\searchengine\modules\search\commands';
            Yii::configure($this, require __DIR__ . '/config/config.php');
        } else {
//            if (Yii::$app->getUser()->getIsGuest()) {
//                throw new UnauthorizedHttpException('Please login to view this section');
//            }
            Yii::$app->params['left-side-menu-item'] = MenuItems::getItems();
        }
    }
}