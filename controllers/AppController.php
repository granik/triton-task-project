<?php

namespace app\controllers;

use yii\rest\Controller;
use Yii;
use yii\filters\AccessControl;
use app\models\User;
use yii\filters\VerbFilter;
use yii\web\ErrorAction;

/**
 * Class AppController
 * @package app\controllers
 * 
 * Корневой контроллер приложения. 
 */
class AppController extends Controller
{
    /**
     * @inheritDoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::className(),
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // разрешаем аутентифицированным пользователям
                    [
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
                    'store' => ['POST'],
                    'delete' => ['DELETE'],
                    'abortCancel' => ['POST'],
                    'update' => ['PUT','PATCH'],
                    'updateMossem' => ['PUT','PATCH'],
                    'clear' => ['POST'],
                    'unlink-file' => ['DELETE']
                ],
            ],

        ];
    }

    /**
     * Замена стандартному var_dump для отладки
     * 
     * @param $var переменная
     */
    protected function Dumper($var)
    {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
    }

    /**
     * Отладочная функция
     * 
     * @param $var переменная
     */
    protected function DieDump($var)
    {
        die("<pre>" . print_r($var, true) . "</pre>");
    }

    /**
     * Экшн, отдающий на скачивание файл с сервера
     *
     * @param $name имя файла
     * @param string $section раздел (пока только event_files)
     * @param $eventId ИД события
     * @throws \yii\base\ErrorException
     */
    public function actionDownload($name, $section = 'event_files', $eventId)
    {
        $path = Yii::$app->params['pathUploads'];
        $file = $path . '/' . $section . '/' . $eventId . '/' . $name;

        if (file_exists($file)) {
            //чтобы файл открывался в браузере
            $mimetype = mime_content_type($file);
            header("Content-Type: {$mimetype}");
            header('Content-Disposition: inline; filename=' . $name);
            if ($fd = fopen($file, 'rb')) {
                while (!feof($fd)) {
                    print fread($fd, 1024);
                }
                fclose($fd);
            }
            exit;
        }
        throw new \yii\base\ErrorException('Файл не найден!');
    }

}