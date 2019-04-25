<?php
/**
 * Created by PhpStorm.
 * User: Granik
 * Date: 13.02.2019
 * Time: 10:04
 */

namespace app\controllers;

use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use app\models\User;
use yii\filters\VerbFilter;

class AppController extends Controller
{
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // разрешаем аутентифицированным пользователям
                    [
//                       'actions' => ['about'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                           return !User::isUserBlocked(Yii::$app->user->identity->id);
                       },
                   ],
                    // всё остальное по умолчанию запрещено
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'cancel' => ['POST'],
                    'no-cancel' => ['POST'],
                    'remove-sponsor' => ['POST'],
                    'unlink-file' => ['POST'],
                    'truncate-field' => ['POST']
                ],
            ],
                                
        ];
    }
    
    protected function Dumper($var) {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
    }
    
    protected function DieDump($var) {
        die("<pre>" . print_r($var, true) . "</pre>");
    }
    
    protected function toRussianDate($date_string) {
        $pattern = "/^(\d\d\d\d)-(\d\d)-(\d\d)$/";
        preg_match($pattern, $date_string, $matches);
        unset($matches[0]);
        $result = implode(array_reverse($matches), '.');
        return $result;
    }
    
    public function actionDownload($name, $section = 'event_files', $event_id) {
        $path = Yii::$app->params['pathUploads'];
        $file = $path . '/' . $section . '/' . $event_id . '/' . $name;

        if (file_exists($file)) {
            return Yii::$app->response->sendFile($file);
        } 
        throw new \yii\base\ErrorException('Файл не найден!');
    }

}