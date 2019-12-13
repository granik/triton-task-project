<?php

namespace app\controllers\webinar;

use Yii;
use app\controllers\AppController;
use app\models\event\main\Event;
use app\models\webinar\EditFieldWebinarForm;
use app\models\webinar\WebinarField;
use app\models\webinar\WebinarInfo;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * Контроллер, отвечающий за поля таблицы основного инфо
 * (на странице события-вебинара)
 * 
 * Class FieldController
 * @package app\controllers\webinar
 */
class FieldController extends AppController
{
    /**
     * Инстанс модели формы редактирования полей
     * основного инфо вебинара
     *
     * @var EditFieldWebinarForm
     */
    protected $edit_field_form;

    /**
     * FieldController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param EditFieldWebinarForm $edit_field_form
     */
    public function __construct($id, $module, $config = [], EditFieldWebinarForm $edit_field_form)
    {
        $this->edit_field_form = $edit_field_form;
        parent::__construct($id, $module, $config);
    }

    /**
     * Страница с формой редактироания вебинара
     * 
     * @param $webinarId
     * @param $fieldId
     * @return string|\yii\web\Response
     * @throws \yii\base\ErrorException
     */
    public function actionEdit($webinarId, $fieldId)
    {
        $title = "Редактирование";
        $where = ['webinar_id' => $webinarId, 'field_id' => $fieldId];

        //получаем данные указанного поля
        $field = WebinarField::find()
            ->with(['field' => function (\yii\db\ActiveQuery $query) use ($webinarId) {
                $query->andWhere('webinar_id = ' . $webinarId);
            }])
            ->where(['id' => $fieldId])
            ->one();
        $form = $this->edit_field_form;
        $model = $form->findOne($where);
        if (!$model) {
            $model = $form;
        }
        //получаем данные о событии (для breadcrumbs и title) 
        $event = Event::findOne($webinarId);
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
     * Обработчик формы редактирования вебинара
     *
     * @return \yii\web\Response
     * @throws \yii\base\ErrorException
     */
    public function actionUpdate($webinarId, $fieldId)
    {
        $where = ['webinar_id' => $webinarId, 'field_id' => $fieldId];
        $field = WebinarField::find()
            ->with(['field' => function (\yii\db\ActiveQuery $query) use ($webinarId) {
                $query->andWhere('webinar_id = ' . $webinarId);
            }])
            ->where(['id' => $fieldId])
            ->one();
        $model = $this->edit_field_form;
        if ($model->load(Yii::$app->request->post())) {

            if ($field->type->name == 'file') {
                //пришел файл
                $file = UploadedFile::getInstance($model, 'file_single');
                if (empty($file)) {
                    //пришло пустое файловое поле
                    $model->updateOnlyComment($webinarId, $fieldId);

                    return $this->redirect(Url::toRoute(['webinar/main/show', 'webinarId' => $webinarId]));
                }

                if (!$model->uploadFile($file, $webinarId, $fieldId)) {
                    throw new \yii\base\ErrorException("Невозможно загрузить файл!");
                }

                return $this->redirect(Url::toRoute(['webinar/main/show', 'webinarId' => $webinarId]));

            }

            if (null == WebinarInfo::find()->where($where)->one()) {
                //если поле ранее не было заполнено
                if (!$model->createNew($webinarId, $fieldId)) {
                    throw new \yii\base\ErrorException("Невозможно вставить данные!");
                }

            } else if (!$model->updateData($webinarId, $fieldId)) {
                //обновляем существующее
                throw new \yii\base\ErrorException("Невозможно обновить данные!");
            }

            Event::findOne($webinarId)->renewUpdatedOn();
        }
        return $this->redirect(Url::toRoute(['webinar/main/show', 'webinarId' => $webinarId]));
    }

    /**
     * Экшн удаления с сервера загруженного файла
     *
     * @param $webinarId
     * @param $fieldId
     * @return \yii\web\Response
     * @throws \yii\base\ErrorException
     */
    public function actionUnlinkFile($webinarId, $fieldId)
    {
        $field = InfoFields::find()
            ->with(['type', 'field' => function (\yii\db\ActiveQuery $query) use ($webinarId) {
                $query->andWhere('webinar_id = ' . $webinarId);
            }])
            ->where(['id' => $fieldId])
            ->one();
        $fileField = WebinarInfo::findOne(['webinar_id' => $webinarId, 'field_id' => $fieldId]);

        if ($field->type->name != 'file') {
            throw new \yii\base\ErrorException("Данное поле не имеет тип 'file'!");
        }


        @unlink(Yii::$app->params['pathUploads'] . 'event_files/' . $webinarId . '/' . $fileField->value);
        $fileField->value = null;
        $fileField->save();

        return $this->redirect(Url::toRoute(['webinar/main/show', 'webinarId' => $webinarId]));

    }

    /**
     * Очистить поле
     *
     * @param $webinarId
     * @param $fieldId
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionClear($webinarId, $fieldId)
    {
        $fields = WebinarInfo::findAll([
            'webinar_id' => $webinarId,
            'field_id' => $fieldId
        ]);
        foreach($fields as $field) {
            $field->delete();
        }

        return $this->redirect(Url::toRoute(['webinar/main/show', 'webinarId' => $webinarId]));
    }
}