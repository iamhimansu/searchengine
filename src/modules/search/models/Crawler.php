<?php

namespace uims\searchengine\modules\search\models;

use Yii;

/**
 * This is the model class for table "crawler".
 *
 * @property int $id
 * @property string|null $module_id
 * @property string|null $url
 * @property string|null $content_hash
 * @property string|null $keywords
 * @property string|null $description
 */
class Crawler extends ModuleActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'crawler';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'content_hash', 'keywords', 'description'], 'string'],
            [['module_id'], 'string', 'max' => 192],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'module_id' => Yii::t('app', 'Module ID'),
            'url' => Yii::t('app', 'Url'),
            'content_hash' => Yii::t('app', 'Content Hash'),
            'keywords' => Yii::t('app', 'Keywords'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

}