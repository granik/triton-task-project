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
         [
                'admin/users' => 'admin/user/list',
                'admin/users/create' => 'admin/user/create',
                'admin/city' => 'admin/city/list',
                'admin/category' => 'admin/category/list',
                'admin/event-type' => 'admin/event-type/list',
                'admin/fields/info' => 'admin/info-fields/index',
                'admin/sponsor-type' => 'admin/sponsor-type/index',
                'admin/fields/logistics' => 'admin/logistic-fields/index',
                '' => 'events',
                'event/<id:\d+>' => 'events/event',
                'event/add' => 'events/add',
                'event/<event_id:\d+>/edit' => 'events/change-data',
                'event/<event_id:\d+>/add-sponsor' => 'events/add-sponsor',
                'event/<event_id:\d+>/remove-sponsor/<id:\d+>' => 'events/remove-sponsor',
                'event/<event_id:\d+>/add-logistics' => 'events/add-logistics',
                'event/<event_id:\d+>/edit-logistics/<item_id:\d+>' => 'events/edit-logistics',
                'event/<event_id:\d+>/edit/<field_id:\d+>' => 'events/edit-field',
                'event/<event_id:\d+>/add-finance' => 'events/add-finance',
                'event/<event_id:\d+>/edit-finance/<item_id:\d+>' => 'events/edit-finance',
                'event/<event_id:\d+>/cancel' => 'events/cancel',
                'event/<event_id:\d+>/abort-cancel' => 'events/abort-cancel',
                'profile' => 'profile/index',
            ]
        ) 
);


