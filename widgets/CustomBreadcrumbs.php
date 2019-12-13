<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\base\ErrorException;
use yii\helpers\Html;
use yii\helpers\Url;


/**
 * Хлебные крошки
 *
 * @author Granik
 */
class CustomBreadcrumbs extends Widget
{
    public $content;
    public $options = array();
    protected $li = '';
    protected $html = '';

    public function init()
    {
        parent::init();

        if (!is_array($this->options)) {
            throw new ErrorException("Options must be an array!");
        }
        if (!is_array($this->content)) {
            throw new ErrorException("Content must be an array!");
        }
        foreach ($this->content as $arr) {
            if (!is_array($arr)) {
                throw new ErrorException("Content-array must contain only other arrays!");
            }
        }
        if (empty($this->content)) $this->content = ['#' => 'Кастомные хлебные крошки'];
        //опции по-умолчанию
        if (empty($this->options))
            $this->options['ol']['class'] = 'd-none d-sm-none d-md-flex bg-white';

    }

    public function run()
    {
        foreach ($this->content as $item) {
            if(empty($item[0])) {
                $item[0] = array_merge([Yii::$app->controller->route], Yii::$app->request->getQueryParams());
            }
            $this->li .= "<li class='breadcrumb-item'>";
            $this->li .= Html::a($item[1], Url::toRoute($item[0]));
            $this->li .= "</li>\n";
        }

        $this->html .= '<nav aria-label="breadcrumb">';
        $this->html .= '<ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">';
        $this->html .= $this->li;
        $this->html .= '</ol>';
        $this->html .= '</nav>';

        return $this->html;
    }
}

/* EXAMPLE
 * 
 * echo CustomBreadCrumbs::widget(['content' =>[
 *     ['/', 'Главная'],
 *     ['/admin', 'Администрирование'],
 *     ['/admin/info-fields', 'Поля таблиц']
 *   ],
 *   'options' => [...],
 * ]);
 * 
*/