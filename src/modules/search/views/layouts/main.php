<?php
/* @var $this \yii\web\View */

/* @var $content string */

?>
<!DOCTYPE html>
<?php $this->beginPage() ?>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?= $this->title ?>
</head>
<body>
<?php $this->beginBody() ?>
<?= $content ?>
<?php
/** @var array $assetBundles
 * Toggling asset bundle as we dont require them
 */
$this->assetBundles = [];
$this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
