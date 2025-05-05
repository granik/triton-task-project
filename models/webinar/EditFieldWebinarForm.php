<?php

namespace app\models\webinar;

use Yii;

/**
 * Модель формы редактирования значения поля
 * (главное инфо о вебинаре)
 *
 * @author Granik
 */
class EditFieldWebinarForm extends WebinarInfo
{
    /**
     * Файл (если есть поле с файлом)
     */
    public $file_single;

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            ['value', 'required'],
            ['value', 'trim'],
            ['value', 'string', 'max' => 100],
            ['comment', 'string', 'max' => 100],
            ['comment', 'trim'],
            ['file_single', 'file', 'extensions' => 'pdf, doc, docx, txt',
                'skipOnEmpty' => true],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels()
    {
        return ['value' => 'Значение',
            'comment' => 'Комментарий',
            'file_single' => 'Загрузить файл: '];
    }

    /**
     * Заполнить значение поля
     *
     * @param $webinar_id
     * @param $field_id
     * @return bool
     * @throws \yii\base\ErrorException
     */
    public function createNew($webinar_id, $field_id)
    {
        if (!$this->validate()) {
            throw new \yii\base\ErrorException("Ошибка валидации данных!");
        }
        // @todo  переписать через typehints
        $model = new WebinarInfo();
        $model->field_id = $field_id;
        $model->webinar_id = $webinar_id;
        $model->value = $this->value;
        $model->comment = $this->comment;

        return $model->save();
    }

    /**
     * Обновить значение поля
     *
     * @param $webinar_id
     * @param $field_id
     * @return bool
     * @throws \yii\base\ErrorException
     */
    public function updateData($webinar_id, $field_id)
    {
        if (!$this->validate()) {
            throw new \yii\base\ErrorException("Ошибка валидации данных!");
        }
        $field = $this->findOne(compact('webinar_id', 'field_id'));
        if (empty($field)) {
            $field = $this;
            $field->field_id = $field_id;
            $field->webinar_id = $webinar_id;
        }
        $field->value = $this->value;
        $field->comment = $this->comment;


        return $field->save();
    }

    /**
     * Обновить только комментарий
     *
     * @param $webinar_id
     * @param $field_id
     * @return bool
     * @throws \yii\base\ErrorException
     */
    public function updateOnlyComment($webinar_id, $field_id)
    {
        //для файловых полей
        if (!$this->validate()) {
            throw new \yii\base\ErrorException("Ошибка валидации данных!");
        }
        $field = $this->findOne(compact('webinar_id', 'field_id'));
        if (empty($field)) {
            $field = $this;
            $field->field_id = $field_id;
            $field->webinar_id = $webinar_id;

        }
        $field->comment = $this->comment;

        return $field->save();
    }

    /**
     * Загрузить файл на сервер
     *
     * @param $file
     * @param $webinar_id
     * @param $field_id
     * @return bool
     * @throws \yii\base\ErrorException
     */
    public function uploadFile($file, $webinar_id, $field_id)
    {

        if (!$this->validate()) {
            throw new \yii\base\ErrorException("Ошибка валидации данных");
        }
        //пришел файл
        $path = Yii::$app->params['pathUploads'] . 'event_files/' . $webinar_id . '/';
        if (!is_dir($path)) {
            mkdir($path, 0755);
        }
        $model = new WebinarInfo();
        $field = $model->findOne(compact('webinar_id', 'field_id'));


        if (empty($field)) {
            $field = $model;
        } else if (null !== $field->value) {
            @unlink($path . $field->value);
        }

        $baseName = $file->getBaseName();
        $ext = $file->getExtension();
        $field->webinar_id = $webinar_id;
        $field->field_id = $field_id;
        $field->value = $baseName . '.' . $ext;
        $field->comment = $this->comment;
        $i = 1;
        while (file_exists($path . $field->value)) {
            $field->value = $baseName . "($i)." . $ext;
            $i++;
        }

        return $field->save() && $file->saveAs($path . $field->value);
    }

}
