<?php

use uims\searchengine\modules\search\models\search\CrawlRequestSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var CrawlRequestSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */
$this->title = 'Requests';
?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6"><?= $this->title ?></div>
            <div class="col-md-6">
                <div class="text-right">
                    <?= Html::a('Request', ['request'], [
                        'class' => 'btn btn-primary'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'url',
                    'follow_hotlinks',
                    'status',
                    [
                        'attribute' => 'created_by',
                        'value' => function ($data) {
                            return $data->getCreated_by_user();
                        }
                    ],
                    [
                        'attribute' => 'updated_by',
                        'value' => function ($data) {
                            return $data->getUpdated_by_user();
                        }
                    ],
                    [
                        'label' => 'Action',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return Html::button('<i class="mdi mdi-delete"></i>', [
                                'class' => 'btn btn-danger'
                            ]);
                        }
                    ]
                ]
            ]);
            ?>
        </div>
    </div>
</div>
