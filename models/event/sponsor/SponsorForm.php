<?php

namespace app\models\event\sponsor;

/**
 * Форма модели добавления спонсора
 *
 * @author Granik
 */
class SponsorForm extends Sponsor
{
    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            [['name', 'type_id'], 'required'],
            ['name', 'string', 'min' => 3, 'max' => 50],
            [['event_id', 'type_id'], 'integer']
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя спонсора',
            'type_id' => 'Тип спонсора',
            'event_id' => ''
        ];
    }
}
