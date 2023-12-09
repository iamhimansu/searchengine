<?php

use uims\searchengine\modules\search\models\CrawlRequest;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var CrawlRequest $model
 */
$this->title = 'Add Crawl Request';
?>

<div class="card">
    <div class="card-header">
        <?= $this->title ?>
    </div>
    <div class="card-body">
        <?php
        $form = ActiveForm::begin();
        ?>
        <?= $form->field($model, 'url'); ?>
        <?= $form->field($model, 'follow_hotlinks')->dropDownList(CrawlRequest::getFollowHotLinksOptions(), [
            'prompt' => 'Select',
        ]); ?>

        <?= Html::submitButton('Save', [
            'class' => 'btn btn-primary'
        ]) ?>
        <?php ActiveForm::end();
        ?>
    </div>
</div>