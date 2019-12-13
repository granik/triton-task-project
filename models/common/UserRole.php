<?php

namespace app\models\common;

use yii\db\ActiveRecord;

/**
 * Модель ролей пользователей
 *
 * @author Granik
 */
class UserRole extends ActiveRecord
{
    public static function tableName()
    {
        return 'user_role';
    }
}
