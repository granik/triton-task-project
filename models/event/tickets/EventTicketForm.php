<?php

namespace app\models\event\tickets;

use Yii;

/**
 * Модель формы добавления электронных билетов
 *
 * @author Granik
 */
class EventTicketForm extends EventTicket
{
    /**
     * Файл с электронным билетом
     */
    public $ticket_file;

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            ['position', 'required'],
            ['position', 'integer'],
            ['ticket_file', 'file', 'extensions' => 'pdf, jpeg, jpg, png, bmp', 'maxFiles' => 10, 'skipOnEmpty' => true]
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels()
    {
        return [
            'ticket_file' => 'Загрузить билет(ы)',
            'position' => ''
        ];
    }

    /**
     * Загрузить файлы билетов на сервер
     *
     * @param $files
     * @param $eventId
     * @return bool
     * @throws \yii\base\ErrorException
     * @throws \yii\db\Exception
     */
    public function uploadFiles($files, $eventId)
    {
        if (!$this->validate()) {
            throw new \yii\base\ErrorException('Ошибка вадидации данных');
        }
        //пришел файл
        $path = Yii::$app->params['pathUploads'] . 'event_files/' . $eventId . '/';
        if (!is_dir($path)) {
            mkdir($path, 0755);
        }
        $rows = [];
        $position = $this->position;
        foreach ($files as $file) {
            $baseName = $file->getBaseName();
            $ext = $file->getExtension();
            $filename = $baseName . '.' . $ext;
            $i = 1;
            while (file_exists($path . $filename)) {
                //если файл существует - добавляем цифру в имя
                $filename = $baseName . "($i)." . $ext;
                $i++;
            }
            $file->saveAs($path . $filename);
            $rows[] = [$eventId, $filename, $position++];
        }
        if (empty($rows)) {
            return false;
        }
        //вставляем
        $command = Yii::$app->db->createCommand();
        $command->batchInsert(self::tableName(), ['event_id', 'filename', 'position'], $rows)->execute();

        return true;
    }
}
