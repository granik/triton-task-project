<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;
use app\models\EventTicket;
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
            ['ticket_file', 'file', 'extensions' => 'pdf, jpeg, jpg, png, bmp', 'skipOnEmpty' => true]
        ];
    }
    
    public function attributeLabels() {
        return [
            'ticket_file' => 'Загрузить файл'
        ];
    }
    
    public function uploadFile($file, $event_id) {
        if(!$this->validate() ) {
            throw new \yii\base\ErrorException('Ошибка вадидации данных');
        }
        //пришел файл
        $path = Yii::$app->params['pathUploads'] . 'event_files/' . $event_id . '/';
        if(!is_dir($path)) {
            mkdir($path, 0755);
        }
        $baseName = $file->getBaseName();
        $ext = $file->getExtension();
        $this->event_id = $event_id;
        $this->filename = $baseName . '.' . $ext;
        $i = 1;
        while(file_exists($path . $this->filename)) {
            $this->filename = $baseName . "($i)." . $ext;
            $i++;
        }
        
        return $this->save() && $file->saveAs( $path . $this->filename ) ? true : false;
    }
}
