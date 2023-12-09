<?php
namespace uims\searchengine\modules\search\models\search;

use uims\searchengine\modules\search\models\CrawlRequest;
use yii\data\ActiveDataProvider;

class CrawlRequestSearch extends CrawlRequest
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'follow_hotlinks', 'status'], 'string'],
            [['updated'], 'safe'],
            [['created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['ip'], 'string', 'max' => 32],
        ];
    }

    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,

        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere([
            'follow_hotlinks' => $this->follow_hotlinks,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->url]);

        return $dataProvider;
    }
}