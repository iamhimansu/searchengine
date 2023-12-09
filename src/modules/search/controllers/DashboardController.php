<?php
namespace uims\searchengine\modules\search\controllers;

use yii\web\Controller;

class DashboardController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}