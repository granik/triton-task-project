<?php

namespace app\models\event\main;

use yii\data\ActiveDataProvider;
use app\components\Functions;

/**
 * Модель формы поиска событий
 */
class SearchEvent extends Event
{
    /**
     * ИД типа события
     * @var int
     */
    public $type_id;
    /**
     * ИД категории события
     * @var int
     */
    public $category_id;
    /**
     * Конфиг
     * @var array
     */
    public $config;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $config = ['is_archive' => false])
    {
        $this->config = $config;
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            [['type', 'city', 'date', 'category', 'type_id', 'category_id'], 'safe'],
            ['date', 'date', 'format' => 'mm.dd.yyyy']
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'type_id' => 'Тип события',
            'city' => 'Город',
            'category_id' => 'Категория'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Event::scenarios();
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
        if ($this->config['is_archive']) {
            $query = Event::find()
                ->with(['type', 'city', 'category'])
                ->where("event.date < CURDATE() OR is_cancel = 1")
                ->orderBy(['date' => SORT_DESC]);
        } else {
            $query = Event::find()
                ->with(['type', 'city', 'category'])
                ->where("event.date >= CURDATE() AND is_cancel = 0")
                ->orderBy(['date' => SORT_ASC]);
        }


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
            'category_id' => $this->category_id,
            'type_id' => $this->type_id,
        ]);
        $query->andFilterWhere(['like', 'date', Functions::toDBdate($this->date)]);
        $query->andFilterWhere([
            'event.is_deleted' => 0
        ]);


        $query->joinWith('city');
        $query->andFilterWhere(['like', 'city.name', $this->city]);


        return $dataProvider;
    }
}