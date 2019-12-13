<?php


namespace app\controllers\event;

use app\modules\admin\models\user\CreateUserForm;
use Yii;
use app\controllers\AppController;
use app\models\event\main\EditFieldForm;
use app\models\event\main\Event;
use app\models\event\main\EventInfo;
use app\models\event\main\EventMainFields;
use app\models\mossem\MossemFieldForm;
use app\models\mossem\MossemFields;
use app\models\mossem\MossemInfo;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * Class FieldController
 * @package app\controllers\event
 * 
 * Контроллер, отвечающий за поля таблицы с основным инфо
 * на странице события
 */
class FieldController extends AppController
{

    /**
     * Инстанс модели формы редактирования поля
     *
     * @var EditFieldForm
     */
    protected $field_edit_form;
    /**
     * Инстанс модели формы редактирования поля
     * (мос. семинары)
     *
     * @var MossemFieldForm
     */
    protected $mossem_field_form;

    /**
     * FieldController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param EditFieldForm $field_edit_form
     * @param MossemFieldForm $mossem_field_form
     */
    public function __construct($id, $module, $config = [], EditFieldForm $field_edit_form, MossemFieldForm $mossem_field_form)
    {
        $this->field_edit_form = $field_edit_form;
        $this->mossem_field_form = $mossem_field_form;
        parent::__construct($id, $module, $config);
    }

    /**
     * Редактирование значения поля
     * 
     * @param $eventId ID события
     * @param $fieldId ID поля
     * @return string|\yii\web\Response
     * @throws \yii\base\ErrorException
     */
    public function actionEdit($eventId, $fieldId)
    {
        $title = "Редактирование";
        $where = ['event_id' => $eventId, 'field_id' => $fieldId];
        //получаем данные указанного поля
        $field = EventMainFields::find()
            ->with(['type', 'field' => function (\yii\db\ActiveQuery $query) use ($eventId) {
                $query->andWhere('event_id = ' . $eventId);
            }])
            ->where(['id' => $fieldId])
            ->one();

        $model = EditFieldForm::findOne($where);
        if (!$model) {
            $model = $this->field_edit_form;
        }
        //получаем данные о событии
        $event = Event::findOne($eventId);
        //отдача шаблона
        return $this->render('edit',
            compact(
                'title',
                'model',
                'field',
                'event'
            )
        );
    }

    /**
     * Обработчик формы редактирования значения поля
     * 
     * @param $eventId ID события
     * @param $fieldId ID поля
     * @return \yii\web\Response
     * @throws \yii\base\ErrorException
     */
    public function actionUpdate($eventId, $fieldId)
    {
        $model = $this->field_edit_form;
        $where = ['event_id' => $eventId, 'field_id' => $fieldId];
        //получаем данные указанного поля
        $field = EventMainFields::find()
            ->with(['type', 'field' => function (\yii\db\ActiveQuery $query) use ($eventId) {
                $query->andWhere('event_id = ' . $eventId);
            }])
            ->where(['id' => $fieldId])
            ->one();
        if ($model->load(Yii::$app->request->post())) {
            if ($field->type->name == 'file') {
                //пришел файл
                $file = UploadedFile::getInstance($model, 'file_single');
                if (empty($file)) {
                    //пришло пустое файловое поле
                    $model->updateOnlyComment($eventId, $fieldId);

                    return $this->redirect(Url::toRoute(['event/main/show', 'eventId' => $eventId]));
                }

                if (!$model->uploadFile($file, $eventId, $fieldId)) {
                    throw new \yii\base\ErrorException("Невозможно загрузить файл!");
                }

                return $this->redirect(
                    Url::toRoute([
                            'event/main/show',
                            'eventId' => $eventId
                        ]
                    )
                );
            }

            if (null == EventInfo::find()->where($where)->one()) {
                //если поле ранее не было заполнено
                if (!$model->createNew($eventId, $fieldId)) {
                    throw new \yii\base\ErrorException("Невозможно вставить данные!");
                }

            } else if (!$model->updateData($eventId, $fieldId)) {
                //обновляем существующее
                throw new \yii\base\ErrorException("Невозможно обновить данные!");
            }

            Event::findOne($eventId)->renewUpdatedOn();

        }
        return $this->redirect(Url::toRoute(['event/main/show', 'eventId' => $eventId]));
    }

    /**
     * Редактирования полей московского семинара
     * (доп. полей, доступных только на странице мос. семинара)
     * 
     * @param $mossemId ИД московского семинара
     * @param $fieldId ИЛ поля
     * @return string|\yii\web\Response
     * @throws \yii\base\ErrorException
     */
    public function actionEditMossem($mossemId, $fieldId)
    {
        $title = "Редактирование поля";
        $field = MossemFields::findOne($fieldId);
        $where = ['mossem_id' => $mossemId, 'field_id' => $fieldId];
        $form = $this->mossem_field_form;
        $model = $form->findOne($where);
        if (!$model) {
            $model = $form;
        }
        //получаем данные о событии (для breadcrumbs и title)
        $event = Event::findOne($mossemId);
        //отдача шаблона
        return $this->render('editMossem',
            compact(
                'title',
                'model',
                'field',
                'event'
            )
        );
    }

    /**
     * Обработчик формы при обновлении значения поля
     * (московский семинар)
     *
     * @param $mossemId ID московского семинара
     * @param $fieldId ID поля
     * @return \yii\web\Response
     * @throws \yii\base\ErrorException
     */
    public function actionUpdateMossem($mossemId, $fieldId)
    {
        //получаем данные указанного поля
        $field = MossemFields::find()
            ->with(['type', 'field' => function (\yii\db\ActiveQuery $query) use ($mossemId) {
                $query->andWhere('mossem_id = ' . $mossemId);
            }])
            ->where(['id' => $fieldId])
            ->one();
        $model = $this->mossem_field_form;
        if ($model->load(Yii::$app->request->post())) {

            if ($field->type->name == 'file') {
                //пришел файл
                $file = UploadedFile::getInstance($model, 'file_single');
                if (empty($file)) {
                    //пришло пустое файловое поле
                    $model->updateOnlyComment($mossemId, $fieldId);

                    return $this->redirect(Url::toRoute(['event/main/show', 'eventId' => $mossemId]));
                }

                if (!$model->uploadFile($file, $mossemId, $fieldId)) {
                    throw new \yii\base\ErrorException("Невозможно загрузить файл!");
                }

                return $this->redirect(Url::toRoute(['event/main/show', 'eventId' => $mossemId]));

            }
            if (null == MossemInfo::findOne([
                    'mossem_id' => $mossemId,
                    'field_id' => $fieldId
                ])
            ) {
                //если поле ранее не было заполнено
                if (!$model->createNew($mossemId, $fieldId)) {
                    throw new \yii\base\ErrorException("Невозможно вставить данные!");
                }

            } else if (!$model->updateData($mossemId, $fieldId)) {
                //обновляем существующее
                throw new \yii\base\ErrorException("Невозможно обновить данные!");
            }

            Event::findOne($mossemId)->renewUpdatedOn();

            return $this->redirect(Url::toRoute(['event/main/show', 'eventId' => $mossemId]));
        }
    }

    /**
     * Очистить поле
     *
     * @param $eventId ID события
     * @param $fieldId ID поля
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionClear($eventId, $fieldId)
    {
        $fields = EventInfo::findAll([
            'event_id' => $eventId,
            'field_id' => $fieldId
        ]);

        foreach($fields as $field) {
            $field->delete();
        }

        Event::findOne($eventId)->renewUpdatedOn();
        return $this->redirect(Url::toRoute([
                'event/main/show',
                'eventId' => $eventId
            ])
        );
    }

    public function actionClearMossem($mossemId, $fieldId)
    {
        $fields = MossemInfo::findAll([
            'mossem_id' => $mossemId,
            'field_id' => $fieldId
        ]);

        foreach($fields as $field) {
            $field->delete();
        }

        Event::findOne($mossemId)->renewUpdatedOn();
        return $this->redirect(Url::toRoute(
            [
                'event/main/show',
                'eventId' => $mossemId
            ])
        );
    }

    /**
     * Удалить загруженный файл с сервера
     * 
     * @param $eventId ИД события
     * @param $fieldId ИД поля
     * @return \yii\web\Response
     * @throws \yii\base\ErrorException
     */
    public function actionUnlinkFile($eventId, $fieldId)
    {
        $field = EventMainFields::find()
            ->with(['type', 'field' => function (\yii\db\ActiveQuery $query) use ($eventId) {
                $query->andWhere('event_id = ' . $eventId);
            }])
            ->where(['id' => $fieldId])
            ->one();
        $fileField = EventInfo::findOne([
            'event_id' => $eventId,
            'field_id' => $fieldId
        ]);

        if ($field->type->name != 'file') {
            throw new \yii\base\ErrorException("Данное поле не имеет тип 'file'!");
        }
        @unlink(Yii::$app->params['pathUploads'] . 'event_files/' . $eventId . '/' . $fileField->value);
        $fileField->value = null;
        $fileField->save();

        Event::findOne($eventId)->renewUpdatedOn();

        return $this->redirect(Url::toRoute(
            [
                'event/main/show',
                'eventId' => $eventId
            ]
        )
        );

    }
}