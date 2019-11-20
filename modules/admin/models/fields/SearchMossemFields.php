<?php

namespace app\modules\admin\models\fields;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\mossem\MossemFields;

/**
 * SearchWebinarFields represents the model behind the search form of `app\models\WebinarFields`.
 */
class SearchMossemFields extends MossemFields
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type_id', 'has_comment', 'position', 'is_deleted'], 'integer'],
            [['name', 'options'], 'safe'],
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
        $query = MossemFields::find()->where(['is_deleted' => 0])->orderBy(['position' => SORT_ASC]);

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
        $query->andFilterWhere([
            'id' => $this->id,
            'type_id' => $this->type_id,
            'has_comment' => $this->has_comment,
            'position' => $this->position,
            'is_deleted' => $this->is_deleted,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'options', $this->options]);

        return $dataProvider;
    }
}
