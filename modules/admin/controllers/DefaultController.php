<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\admin\controllers;

use app\modules\admin\controllers\AppAdminController;

/**
 * Description of AdminController
 *
 * @author Granik
 */
class DefaultController extends AppAdminController {
    
    public function actionIndex() {
        $title = "Администрирование";
        $msg = "Вы находитесь в панели администрирования";
        return $this->render('index', compact('title', 'msg'));
    }
    //put your code here
}
