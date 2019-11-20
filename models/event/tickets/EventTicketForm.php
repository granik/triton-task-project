<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\event\tickets;
use Yii;
/**
 * Description of EventTicketForm
 *
 * @author Granik
 */
class EventTicketForm extends EventTicket {
    //put your code here
    public $ticket_file;
    
    public function rules() {
        return [
            ['ticket_file', 'file', 'extensions' => 'pdf, jpeg, jpg, png, bmp', 'maxFiles' => 10, 'skipOnEmpty' => true]
        ];
    }
    
    public function attributeLabels() {
        return [
            'ticket_file' => 'Загрузить билет(ы)'
        ];
    }
    
    public function uploadFiles($files, $event_id) {
        if(!$this->validate() ) {
            throw new \yii\base\ErrorException('Ошибка вадидации данных');
        }
        //пришел файл
        $path = Yii::$app->params['pathUploads'] . 'event_files/' . $event_id . '/';
        if(!is_dir($path)) {
            mkdir($path, 0755);
        }
        $rows = [];
        foreach($files as $file) {
            $baseName = $file->getBaseName();
            $ext = $file->getExtension();
            $filename = $baseName . '.' . $ext;
            $i = 1;
            while(file_exists($path . $filename)) {
                //если файл существует - добавляем цифру в имя
                $filename = $baseName . "($i)." . $ext;
                $i++;
            }
            $file->saveAs( $path . $filename );
            $rows[] = [$event_id, $filename];
        }
        if(empty($rows)) {
            return false;
        }
        //вставляем
        $command = Yii::$app->db->createCommand();
            $command->batchInsert(self::tableName(),['event_id','filename'], $rows)->execute();
        
        return true;
    }
}
