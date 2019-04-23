<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers;
use app\controllers\AppController;

/**
 * Description of ProfileController
 *
 * @author Granik
 */
class ProfileController extends AppController {
    
    public function actionIndex() {
        $title = "Профиль";
        return $this->render('profile', compact('title'));
    }
}
