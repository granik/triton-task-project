<?php

namespace app\modules\admin\controllers;

use yii\filters\VerbFilter;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use app\models\User;

class AppAdminController extends Controller
{

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'denyCallback' => function ($rule, $action) {
                    return $this->redirect('/');
                },
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            //МОЖНО ТОЛЬКО АДМИНАМ
                            return User::isUserAdmin(Yii::$app->user->identity->id);
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['DELETE'],
                    'store' => ['POST'],
                    'update' => ['PUT', 'PATCH']
                ],
            ],
        ];
    }


    protected function DieDump($var)
    {
        die("<pre>" . print_r($var, true) . "</pre>");
    }

}