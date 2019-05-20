<?php

namespace app\models;
use yii\db\ActiveRecord;
use app\models\EventType;
use app\models\City;
use app\models\EventCategory;
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
    
    public function getCity() {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }
    
    public function setCity($value)
    {
        $this->city = $value;
    }
    
    public function getCategory() {
        return $this->hasOne(EventCategory::className(), ['id' => 'category_id']);
    }
    
    public function getEventAsArray($id) {
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
}
