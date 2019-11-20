<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\event\tickets;
use yii\db\ActiveRecord;
/**
 * Description of EventTicket
 *
 * @author Granik
 */
class EventTicket extends ActiveRecord {
    
    public static function tableName() {
        return 'event_ticket';
    }
}
