<?php

namespace app\modules\admin\models\source;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\common\City;

/**
 * searchCity represents the model behind the search form of `app\models\City`.
 */
class SearchCity extends City
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
//            [['id', 'is_deleted'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = City::find()->where(['is_deleted' => 0]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
//        $query->andFilterWhere([
//            'id' => $this->id,
//            'map_left' => $this->map_left,
//            'map_top' => $this->map_top,
//            'is_deleted' => $this->is_deleted,
//        ]);
//        $query->andFilterWhere([
////            'is_deleted' => 0
//        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}