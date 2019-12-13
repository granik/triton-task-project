<?php

namespace app\modules\admin\models\user;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * searchSponsorType represents the model behind the search form of `app\models\SponsorType`.
 */
class SearchUser extends User
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email'], 'safe'],
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
        $query = User::find()->with('role');

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
        ]);

//        $nameParts = $this->parseFullname($this->fullname);
//        $first_name 

//        $query->andFilterWhere(['like', 'fullname', $this->fullname]);
        $query->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
