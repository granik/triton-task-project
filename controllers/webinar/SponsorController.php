<?php

namespace app\controllers\webinar;

use app\controllers\AppController;
use app\models\event\main\Event;
use app\models\event\sponsor\SponsorForm;
use app\models\event\sponsor\Sponsor;
use app\models\event\sponsor\SponsorType;
use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Url;

/**
 * Контроллер, отвечающий за спонсоров к вебинару
 * 
 * Class SponsorController
 * @package app\controllers\webinar
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
     * @param SponsorForm $sponsorForm
     */
    public function __construct($id, $module, $config = [], SponsorForm $sponsorForm)
    {
        $this->sponsor_form = $sponsorForm;
        parent::__construct($id, $module, $config);
    }

    /**
     * Страница с формой добавления спонсора к вебинару
     * 
     * @param $webinarId
     * @return string|\yii\web\Response
     */
    public function actionCreate($webinarId)
    {
        $title = "Добавить спонсора";
        $webinar =  Event::findOne(['id' => $webinarId]);
        $types = SponsorType::findAll(['is_deleted' => 0]);
        $typeOpts = ArrayHelper::map($types, 'id', 'name');
        $form = $this->sponsor_form;

        return $this->render('create',
            compact(
                'title',
                'form',
                'typeOpts',
                'webinar'
            )
        );
    }

    /**
     * Обработчик формы добавления спонсора к вебинару
     *
     * @param $webinarId
     * @return \yii\web\Response
     */
    public function actionStore($webinarId)
    {
        $model = $this->sponsor_form;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            Event::findOne($webinarId)->renewUpdatedOn();
            return $this->redirect(Url::toRoute(['webinar/main/show', 'webinarId' => $webinarId]));
        }
    }

    /**
     * Экшн удаления спонсора
     *
     * @param $sponsorId
     * @param $webinarId
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($sponsorId, $webinarId)
    {
        $sponsor = Sponsor::findOne(['id' => $sponsorId, 'event_id' => $webinarId]);
        $sponsor->is_deleted = 1;
        $sponsor->update();
        Event::findOne($webinarId)->renewUpdatedOn();
        return $this->redirect(Url::toRoute(['webinar/main/show', 'webinarId' => $webinarId]));

    }
}