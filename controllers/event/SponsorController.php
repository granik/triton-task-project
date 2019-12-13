<?php

namespace app\controllers\event;

use Yii;
use app\controllers\AppController;
use app\models\event\main\Event;
use app\models\event\sponsor\SponsorForm;
use app\models\event\sponsor\Sponsor;
use app\models\event\sponsor\SponsorType;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Контроллер, отвечающий за спонсоров
 * (на странице события)
 *
 * Class SponsorController
 * @package app\controllers\event
 */
class SponsorController extends AppController
{
    /**
     * Инстанс модели формы добавления спонсора
     *
     * @var SponsorForm
     */
    protected $sponsor_form;

    /**
     * SponsorController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param SponsorForm $sponsor_form
     */
    public function __construct($id, $module, $config = [], SponsorForm $sponsor_form)
    {
        $this->sponsor_form = $sponsor_form;
        parent::__construct($id, $module, $config);
    }

    /**
     * Страница с формой добавления спонсора
     *
     * @param $eventId ID события
     * @return string|\yii\web\Response
     */
    public function actionCreate($eventId)
    {
        $title = "Добавить спонсора";
        $event = Event::findOne($eventId);
        $sponsorTypes = SponsorType::findAll(['is_deleted' => 0]);
        $typeOpts = ArrayHelper::map($sponsorTypes, 'id', 'name');
        $form =  $this->sponsor_form;

        return $this->render('create',
            compact(
                'title',
                'form',
                'typeOpts',
                'event'
            )
        );
    }

    /**
     * Обработчик формы добавления спонсора
     *
     * @param $eventId Id события
     * @return \yii\web\Response
     */
    public function actionStore($eventId)
    {
        $form =  $this->sponsor_form;
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $form->save();
            Event::findOne($eventId)->renewUpdatedOn();
        }
        return $this->redirect(Url::toRoute(['event/main/show', 'eventId' => $eventId]));
    }

    /**
     * Экшн удаления спонсора
     *
     * @param $sponsorId Id спонсора
     * @param $eventId Id события
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($sponsorId, $eventId)
    {
        $sponsor = Sponsor::findOne($sponsorId);
        $sponsor->is_deleted = 1;
        $sponsor->update();
        Event::findOne($eventId)->renewUpdatedOn();

        return $this->redirect(Url::toRoute(['event/main/show', 'eventId' => $eventId]));

    }
}