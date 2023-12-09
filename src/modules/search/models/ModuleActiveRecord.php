<?php

namespace uims\searchengine\modules\search\models;

use uims\user\modules\jiuser\models\User;
use Yii;
use yii\db\ActiveRecord;

/**
 *
 * @property mixed $created_at_time
 * @property mixed $updated_at_time
 * @property mixed $updated_by_user
 * @property mixed $created_by_user
 */
class ModuleActiveRecord extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->db_uims;
    }

    public function getCreated_by_user()
    {
        return User::getUserInformation($this->created_by);
    }

    public function getUpdated_by_user()
    {
        return User::getUserInformation($this->updated_by);
    }

    public function getCreated_at_time()
    {
        return Yii::$app->formatter->asDatetime($this->created_at);
    }

    public function getUpdated_at_time()
    {
        return Yii::$app->formatter->asDatetime($this->updated_at);
    }


}