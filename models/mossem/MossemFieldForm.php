<?php

namespace app\models\mossem;

use Yii;

/**
 * Модель формы полей московского семинара
 *
 * @author Granik
 */
class MossemFieldForm extends MossemInfo
{

    /**
     * Файл (если есть поле типа file)
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
     * @param $mossemId
     * @param $fieldId
     * @return bool
     * @throws \yii\base\ErrorException
     */
    public function createNew($mossemId, $fieldId)
    {
        if (!$this->validate()) {
            throw new \yii\base\ErrorException("Ошибка валидации данных!");
        }
        
        $this->field_id = $fieldId;
        $this->mossem_id = $mossemId;

        return $this->save();
    }

    /**
     * Обновить значение поля
     *
     * @param $mossem_id
     * @param $field_id
     * @return bool
     * @throws \yii\base\ErrorException
     */
    public function updateData($mossem_id, $field_id)
    {
        if (!$this->validate()) {
            throw new \yii\base\ErrorException("Ошибка валидации данных!");
        }
        $field = $this->findOne(compact('mossem_id', 'field_id'));
        if (empty($field)) {
            $field = $this;
            $field->field_id = $field_id;
            $field->mossem_id = $mossem_id;
        }
        $field->value = $this->value;
        $field->comment = $this->comment;

        return $field->save() ? true : false;
    }

    /**
     * Обновить только значение комментария
     *
     * @param $mossemId
     * @param $fieldId
     * @return bool
     * @throws \yii\base\ErrorException
     */
    public function updateOnlyComment($mossem_id, $field_id)
    {
        //для файловых полей
        if (!$this->validate()) {
            throw new \yii\base\ErrorException("Ошибка валидации данных!");
        }
        $field = $this->findOne(compact('mossem_id', 'field_id'));
        if (empty($field)) {
            $field = $this;
            $field->field_id = $field_id;
            $field->mossem_id = $mossem_id;

        }
        $field->comment = $this->comment;

        return $field->save() ? true : false;
    }

    /**
     * Загрузить файл на сервер
     * (если есть поле с файлом)
     * @param $file
     * @param $mossemId
     * @param $fieldId
     * @return bool
     * @throws \yii\base\ErrorException
     */
    public function uploadFile($file, $mossem_id, $field_id)
    {

        if (!$this->validate()) {
            throw new \yii\base\ErrorException("Ошибка валидации данных");
        }
        //пришел файл
        $path = Yii::$app->params['pathUploads'] . 'event_files/' . $mossem_id . '/';
        if (!is_dir($path)) {
            mkdir($path, 0755);
        }
        $field = $this->findOne(compact('mossem_id', 'field_id'));

        if (empty($field)) {
            $field = $this;
        } else if (null !== $field->value) {
            @unlink($path . $field->value);
        }

        $baseName = $file->getBaseName();
        $ext = $file->getExtension();
        $field->mossem_id = $mossem_id;
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
