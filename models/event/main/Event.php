<?php

namespace app\models\event\main;
use yii\db\ActiveRecord;
use app\models\common\City;
/**
 * Description of EventModel
 *
 * @author Granik
 */
class Event extends ActiveRecord {
    //put your code here
    public static function tableName() {
        return 'event';
    }
    
    public function getType() {
        return $this->hasOne(EventType::className(), ['id' => 'type_id']);
    }
    
    public function getInfo() {
        return $this->hasOne(EventInfo::className(), ['event_id' => 'id']);
    }
    
    public function getCity() {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    public function getTickets()
    {
        return $this->hasMany(EventTicket::className(), ['event_id' => 'id']);
    }
    
    public function setCity($value)
    {
        $this->city = $value;
    }
    
    public function getCategory() {
        return $this->hasOne(EventCategory::className(), ['id' => 'category_id']);
    }
    
    public function getEventAsArray($id) {
        //ToDo: переписать этот бред
        return $this->find()
            ->select(['event.*', 'event_type.name as type', 'event.type_custom', 'event_category.name as category', 'city.name as city'])
            ->innerJoin('event_type', 'event.type_id = event_type.id')
            ->innerJoin('event_category', 'event.category_id = event_category.id')
            ->leftJoin('city', 'event.city_id = city.id')
            ->where(['event.id' => $id])
            ->asArray()
            ->one();
    }
    
    public static function setLastUpdateTime($eventId) {
        $event = self::findOne($eventId);
        $event->updated_on = time();
        $event->save();
    }
    
    public static function isEventPast($id) {
        $event = self::findOne($id);
        return $event->date < date("Y-m-d");
    }
}
