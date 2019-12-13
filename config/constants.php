<?php
/*Константы веб-приложения*/
include_once __DIR__ . '/debug.php';
//папка с загруженными на сервер файлами
define('UPLOADS_DIR', realpath(dirname(__FILE__)).'/../web/uploads/');
//название сайта
define('SITE_TITLE', "Система управления событиями ООО \"Тритон\"");
//почта админа
define('ADMIN_EMAIL', 'plazahotel@ya.ru');
//Правила маршрутизации
define('ROUTE_RULES', serialize(
         require_once 'routes.php'
        ) 
);


