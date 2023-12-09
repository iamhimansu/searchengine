<?php

namespace uims\searchengine\modules\search\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\web\Application;

/**
 * This is the model class for table "crawl_request".
 *
 * @property int $id
 * @property string|null $url
 * @property string|null $follow_hotlinks
 * @property string|null $status
 * @property string|null $updated
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $ip
 */
class CrawlRequest extends ModuleActiveRecord
{

    const FOLLOW_HOT_LINKS_YES = 'YES';
    const FOLLOW_HOT_LINKS_NO = 'NO';

    const STATUS_DRAFT = 'DRAFT';
    const STATUS_CRAWLING = 'CRAWLING';
    const STATUS_FAILED = 'FAILED';
    const STATUS_INITIATED = 'INITIATED';
    const STATUS_SUCEESS = 'SUCEESS';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'crawl_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'follow_hotlinks', 'status'], 'string'],
            [['url', 'follow_hotlinks'], 'required'],
            [['updated'], 'safe'],
            [['created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['ip'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'url' => Yii::t('app', 'Url'),
            'follow_hotlinks' => Yii::t('app', 'Follow Hotlinks'),
            'status' => Yii::t('app', 'Status'),
            'updated' => Yii::t('app', 'Updated'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'ip' => Yii::t('app', 'Ip'),
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(), //BlameableBehavior automatically fills the specified attributes with the current user ID.
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    public function beforeSave($insert)
    {
        $parent = parent::beforeSave($insert);
        if (Yii::$app instanceof Application) {
            $this->ip = Yii::$app->getRequest()->getUserIP();
        }
        return $parent;
    }

    public static function getFollowHotLinksOptions()
    {
        return [
            self::FOLLOW_HOT_LINKS_YES => self::FOLLOW_HOT_LINKS_YES,
            self::FOLLOW_HOT_LINKS_NO => self::FOLLOW_HOT_LINKS_NO
        ];
    }

    public static function resolveFollowHotLinks($id)
    {
        $status = self::getFollowHotLinksOptions();
        if (isset($status[$id])) {
            return $status[$id];
        }
        return 'UNKNOWN';
    }
}