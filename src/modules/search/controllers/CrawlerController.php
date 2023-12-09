<?php

namespace uims\searchengine\modules\search\controllers;

use app\models\SecurityHelper;
use uims\searchengine\modules\search\models\CrawlRequest;
use uims\searchengine\modules\search\models\search\CrawlRequestSearch;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class CrawlerController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $searchModel = new CrawlRequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionRequest()
    {
        $model = new CrawlRequest();
        if ($model->load(Yii::$app->getRequest()->post())) {
            if (!$model->validate()) {
                Yii::$app->getSession()->setFlash('message', Html::errorSummary($model));
                return $this->render('request', [
                    'model' => $model
                ]);
            }

            if (false === $model->save(false)) {
                Yii::$app->getSession()->setFlash('message', 'Could not save data, please try again.');
                return $this->refresh();
            }

            return $this->render('view', [
                'model' => $model
            ]);
        }
        return $this->render('request', [
            'model' => $model
        ]);
    }

    public function actionView($id)
    {
        $referrer = Yii::$app->getRequest()->getReferrer();

        if (empty($referrer)) {
            $referrer = Url::to(['/search/crawer']);
        }

        $idd = SecurityHelper::validateData($id);

        if (!$idd) {
            Yii::$app->getSession()->setFlash('message', 'Invalid Id');
            return $this->redirect($referrer);
        }

        $model = CrawlRequest::find()->where(['id' => $idd])->one();

        if (empty($model)) {
            Yii::$app->getSession()->setFlash('message', 'Data not found.');
            return $this->redirect($referrer);
        }

        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionDebug()
    {
        return $this->renderPartial('@app/runtime/debug-crawl.html');
    }
}