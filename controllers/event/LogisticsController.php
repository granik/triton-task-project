<?php

namespace app\controllers\event;

use Yii;
use app\controllers\AppController;
use app\models\event\logistics\LogisticFields;
use app\models\event\logistics\LogisticInfo;
use app\models\event\logistics\LogisticMeans;
use app\models\event\logistics\LogisticsForm;
use app\models\event\main\Event;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Контроллер, отвечающий за логистическую информацию
 * (на странице события)
 *
 * Class LogisticsController
 * @package app\controllers\event
 */
class LogisticsController extends AppController
{
    /**
     * Инстанс модели формы добавления/редактирования
     * логистической информации
     *
     * @var LogisticForm
     */
    protected $logistic_form;

    /**
     * LogisticsController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param LogisticForm $logistic_form
     */
    public function __construct($id, $module, $config = [], LogisticsForm $logistic_form)
    {
        $this->logistic_form = $logistic_form;
        parent::__construct($id, $module, $config);
    }

    /**
     * Страница с формой добавления
     *
     * @param $eventId ИД события
     * @return string|\yii\web\Response
     */
    public function actionCreate($eventId)
    {
        $title = "Добавить логистическую информацию";

        $event = Event::findOne($eventId);
        $form = $this->logistic_form;

        $fields = LogisticFields::findAll(['is_deleted' => 0]);
        $means = LogisticMeans::findAll(['is_deleted' => 0]);

        $means = ArrayHelper::map($means, 'id', 'name');
        $fields = ArrayHelper::map($fields, 'id', 'name');

        return $this->render('add',
            compact(
                'title',
                'form',
                'fields',
                'event',
                'means'
            )
        );
    }

    /**
     * Обработчик формы добавления логистической информации
     *
     * @param $eventId ИД события
     * @return \yii\web\Response
     * @throws ErrorException
     */
    public function actionStore($eventId)
    {
        $form = $this->logistic_form;
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $form->save();
            Event::findOne($eventId)->renewUpdatedOn();
            return $this->redirect(Url::toRoute(['event/main/show', 'eventId' => $eventId]));
        }
        throw new ErrorException('Ошибка обработки данных формы');
    }

    /**
     * Страница редактирования строки в таблице логистической информации
     *
     * @param $eventId ИД события
     * @param $itemId ИД строки в таблице логистического инфо
     * @return string
     */
    public function actionEdit($eventId, $itemId)
    {
        $title = "Править логистическую информацию";

        $event = Event::findOne($eventId);
        $form = $this->logistic_form;
        $where = ['id' => $itemId];
        $model = $form->findOne($where);

        $fields = LogisticFields::findAll(['is_deleted' => 0]);
        $means = LogisticMeans::findAll(['is_deleted' => 0]);

        $means = ArrayHelper::map($means, 'id', 'name');
        $fields = ArrayHelper::map($fields, 'id', 'name');

        return $this->render('edit',
            compact(
                'title',
                'form',
                'fields',
                'event',
                'means',
                'model'
            )
        );
    }

    /**
     * Обработчик формы редактирования
     *
     * @param $eventId ИД события
     * @param $itemId ИД записи в таблице логистического инфо
     * @return \yii\web\Response
     * @throws \yii\base\ErrorException
     */
    public function actionUpdate($eventId, $itemId)
    {
        $model = LogisticForm::findOne($itemId);
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->updateData($itemId)) {
                throw new \yii\base\ErrorException("Невозможно обновить данные!");
            }
            Event::findOne($eventId)->renewUpdatedOn();
        }
        return $this->redirect(Url::toRoute(['event/main/show', 'eventId' => $eventId]));
    }

    /**
     * Экшн удаления записи из таблицы логистического инфо
     *
     * @param $eventId ИД события
     * @param $itemId ИД строки
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($eventId, $itemId)
    {
        $currentRow = LogisticInfo::findOne($itemId);
        $currentRow->is_deleted = 1;
        $currentRow->update();
        Event::findOne($eventId)->renewUpdatedOn();
        return $this->redirect(Url::toRoute(['event/main/show', 'eventId' => $eventId]));
    }
}