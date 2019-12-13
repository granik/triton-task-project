<?php

namespace app\modules\admin\controllers;

/**
 * Description of AdminController
 *
 * @author Granik
 */
class DefaultController extends AppAdminController
{

    public function actionIndex()
    {
        $title = "Администрирование";
        $msg = "Вы находитесь в панели администрирования";
        return $this->render('index', compact('title', 'msg'));
    }
}
