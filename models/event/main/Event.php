<?php

namespace app\models\event\main;

use app\models\event\finance\FinanceInfo;
use app\models\event\logistics\LogisticInfo;
use app\models\event\services\EventService;
use app\models\event\sponsor\Sponsor;
use yii\db\ActiveRecord;
use app\models\common\City;
use app\models\event\tickets\EventTicket;
use app\components\Functions;

/**
 * Модель событий
 *
 * @author Granik
 */
class Event extends ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * Связь с типом события
     *
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(EventType::className(), ['id' => 'type_id']);
    }

    /**
     * Связь с основным инфо
     *
     * @return \yii\db\ActiveQuery
     */
    public function getData()
    {
        return $this->hasOne(EventInfo::className(), ['event_id' => 'id']);
    }

    /**
     * Связь с городом
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * Связь с эл.билетами
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTickets()
    {
        return $this->hasMany(EventTicket::className(), ['event_id' => 'id']);
    }

    /**
     * Связь со спонсорами
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSponsors()
    {
        return $this->hasMany(Sponsor::className(), ['event_id' => 'id']);
    }

    /**
     * Связь с финансовой информацией
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFinances()
    {
        return $this->hasMany(FinanceInfo::className(), ['event_id' => 'id']);
    }

    /**
     * Связь с доп. услугами
     *
     * @return \yii\db\ActiveQuery
     */
    public function getServices()
    {
        return $this->hasMany(EventService::className(), ['event_id' => 'id']);
    }

    /**
     * Установить день недели
     *
     * @param $weekday день недели (строкой)
     */
    public function setWeekday($weekday)
    {
        $this->weekday = $weekday;
    }

    /**
     * Связь с логистической информацией
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogistics()
    {
        return $this->hasMany(LogisticInfo::className(), ['event_id' => 'id']);
    }

    /**
     * Установить город (строкой)
     *
     * @param $value
     */
    public function setCity($value)
    {
        $this->city = $value;
    }

    /**
     * Связь с категорией
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(EventCategory::className(), ['id' => 'category_id']);
    }

    /**
     * Установить время последнего обновления
     *
     * @param $eventId
     */
//    public static function setLastUpdateTime($eventId)
//    {
//        $event = self:;
//
//        $event->save();
//    }

    /**
     * Прошло ли событие
     *
     * @return bool
     */
    public function getIsPast()
    {
        return strtotime($this->date) < strtotime(date("Y-m-d"));
    }

    /**
     * Дата в российском формате
     *
     * @return string
     */
    public function getRegularDate()
    {
        return Functions::toSovietDate($this->date);
    }

    /**
     * Время последнего обновления
     *
     * @return false|string
     */
    public function getUpdateTime()
    {
        if(empty($this->updated_on))
            return '-';
        return date('d.m.Y H:i', $this->updated_on + 3*3600);
    }

    public function renewUpdatedOn()
    {
        $this->updated_on = time();
        $this->update();
    }
}
