<?php


namespace app\models\event\logistics;

use yii\db\ActiveRecord;

/**
 * Модель средств передвижения
 *
 * @author Granik
 */
class LogisticMeans extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return 'logistic_means';
    }
}
