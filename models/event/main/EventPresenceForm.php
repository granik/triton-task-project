<?php

namespace app\models\event\main;

use yii\base\Model;

/**
 * Модель формы итоговой явки
 *
 * @author Granik
 */
class EventPresenceForm extends Model
{
    /**
     * Итоговая явка
     * @var int
     */
    public $presence;
    /**
     * Комментарий
     * @var string
     */
    public $presence_comment;

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'presence' => 'Итоговая явка',
            'presence_comment' => 'Итоговый комментарий'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['presence', 'required'],
            ['presence', 'integer', 'min' => 0, 'max' => 10000000],
            ['presence_comment', 'string', 'min' => 2, 'max' => 150]
        ];
    }

    /**
     * Обновить итоговую явку
     *
     * @param $event_id
     * @return bool
     * @throws \yii\base\ErrorException
     */
    public function updateEventPresence($event_id)
    {
        if (!$this->validate()) {
            throw new \yii\base\ErrorException("Ошибка валидации данных формы!");
        }
        $event = new Event();
        $model = $event->findOne($event_id);
        $model->presence = $this->presence;
        $model->presence_comment = $this->presence_comment;
        return $model->save() ? true : false;
    }
}
