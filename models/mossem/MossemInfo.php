<?php

namespace app\models\mossem;

use yii\db\ActiveRecord;

/**
 * Модель осногвного инфо о московском семинаре
 *
 * @author Granik
 */
class MossemInfo extends ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return 'mossem_info';
    }
}
