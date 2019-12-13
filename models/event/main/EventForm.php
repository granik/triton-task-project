<?php

namespace app\models\event\main;

/**
 * Форма добавления/редактирования события
 *
 * @author Granik
 */
class EventForm extends Event
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            ['id', 'integer'],
            [['title', 'type_id', 'date', 'city_id', 'category_id'], 'required'],
            [['title'], 'string', 'min' => 3, 'max' => 200],
            [['type_id', 'city_id', 'category_id'], 'integer'],
            ['type_custom', 'string', 'min' => 2, 'max' => 20],
            ['date', 'date', 'format' => 'php:Y-m-d'],
            [['notes'], 'string', 'min' => 2, 'max' => 30]
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '',
            'title' => 'Название',
            'type_id' => 'Тип события',
            'type_custom' => 'Тип (если другое)',
            'date' => 'День проведения',
            'city_id' => 'Город',
            'category_id' => 'Категория',
            'notes' => 'Примечания'
        ];
    }

    /**
     * Обновить данные события
     *
     * @return bool
     * @throws \yii\base\ErrorException
     */
    public function updateData()
    {
        if (!$this->validate()) {
            throw new \yii\base\ErrorException("Ошибка валидации данных!");
        }

        if (!empty($this->type_custom)) {
            $this->type_id = EventType::findOne(['name' => 'Другое'])->id;
        } else {
            $this->type_custom = null;
        }
        //time of the last update
        $this->updated_on = time();

        return $this->update() !== false;
    }

    /**
     * Добавить новое событие в БД
     *
     * @return bool
     * @throws \yii\base\ErrorException
     */
    public function insertEvent()
    {
        if (!$this->validate()) {
            throw new \yii\base\ErrorException("Ошибка валидации данных!");
        }
        return $this->save();
    }
}
