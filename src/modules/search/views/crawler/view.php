<?php

use uims\searchengine\modules\search\models\CrawlRequest;
use yii\widgets\DetailView;

/**
 * @var CrawlRequest $model
 */
?>

<div class="card">
    <div class="card-header">

    </div>
    <div class="card-body">
        <?php
        echo DetailView::widget([
            'model' => $model
        ]);
        ?>
    </div>
</div>

