<?php

namespace app\models\event\services;
/**
 * Модель формы добавления доп. услуг
 *
 * @author Granik
 */
class EventServiceForm extends EventService
{
    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            ['title', 'required'],
            ['title', 'string', 'min' => 2, 'max' => 30],
            ['customer', 'required'],
            ['customer', 'string', 'min' => 2, 'max' => 30],
            ['producer', 'required'],
            ['producer', 'string', 'min' => 2, 'max' => 30],
            ['city_id', 'integer'],
            ['city_name', 'string', 'min' => 2, 'max' => 30],
            ['date', 'required'],
            ['date', 'date', 'format' => 'php:Y-m-d'],
            ['people_amount', 'integer'],
            ['event_id', 'integer'],
            ['status', 'required'],
            ['status', 'integer']
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels()
    {
        return [
            'event_id' => '',
            'title' => 'Название',
            'customer' => 'Заказчик',
            'producer' => 'Исполнитель',
            'city_id' => 'Город отпр.',
            'city_name' => 'Город (не из списка)',
            'date' => 'Дата',
            'people_amount' => 'Кол-во чел.',
            'status' => 'Оплачен'
        ];
    }
}
