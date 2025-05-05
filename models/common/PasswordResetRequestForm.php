<?php
namespace app\models\common;
 
use app\models\User;
use Yii;
use yii\base\Model;
 
/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;
 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\app\models\User',
                'filter' => ['is_deleted' => 0],
                'message' => 'Нет пользователя с таким E-mail или пользователь декактивирован'
            ],
        ];
    }
    
    public function attributeLabels() {
        return ['email' => 'E-mail'];
    }
 
    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'is_deleted' => 0,
            'email' => $this->email,
        ]);
 
        if (!$user) {
            return false;
        }
 
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }
 
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' (робот)'])
            ->setTo($this->email)
            ->setSubject('Восстановление пароля ' . Yii::$app->name)
            ->send();
    }
 
}