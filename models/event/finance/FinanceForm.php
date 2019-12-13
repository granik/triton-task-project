<?php

namespace app\models\event\finance;

use yii\base\ErrorException;

/**
 * Форма добавления финансового инфо
 *
 * @author Granik
 */
class FinanceForm extends FinanceInfo
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['exist', 'status'], 'safe'],
            ['comment', 'string', 'min' => 1, 'max' => 50],
            [['event_id', 'type_id'], 'integer'],
            [['event_id', 'type_id'], 'required']
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'comment' => 'Примечание',
            'status' => 'Статус',
            'type_id' => 'Тип',
            'exist' => 'Наличие',
            'event_id' => ''
        ];
    }

    /**
     * Обновить финансовую информацию
     *
     * @param $id ID строки в таблице финансового инфо
     * @return bool
     * @throws ErrorException
     */
    public function updateData($id)
    {
        if (!$this->validate()) {
            throw new ErrorException("Ошибка валидации данных!");
        }

        $row = $this->findOne($id);
        $row->type_id = $this->type_id;
        $row->exist = $this->exist;
        $row->status = $this->status;
        $row->comment = $this->comment;

        return $row->save();
    }
}
