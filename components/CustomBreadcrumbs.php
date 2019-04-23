<?php

/*
* Кастомные хлебные крошки. Сделаны для совместимости с BootStrap-4
*/

namespace app\components;

use yii\base\{
    Widget,
    ErrorException
};

/**
 * Description of CustomBreadcrumbs
 *
 * @author Granik
 */
class CustomBreadcrumbs extends Widget {
    //put your code here
    
    public $content;
    public $options = array();
    protected $li;
    protected $html;
    
    public function init() {
        parent::init();
        
        if(!is_array($this->options)) {
            throw new ErrorException("Options must be an array!");
        }
        if(!is_array($this->content)) {
            throw new ErrorException("Content must be an array!");
        }
        foreach ($this->content as $arr) {
            if(!is_array($arr)) {
                throw new ErrorException("Content-array must contain only other arrays!");
            }
        }
        if(empty($this->content)) $this->content = ['#' => 'Кастомные хлебные крошки'];
        //опции по-умолчанию
        if(empty($this->options)) 
            $this->options['ol']['class'] = 'd-none d-sm-none d-md-flex bg-white';
        
    }
    
    public function run() {
        foreach($this->content as $item) {
            $this->li .= "<li class='breadcrumb-item {$this->options['li']['class']}'>
                <a href='{$item[0]}'>{$item[1]}</a>
                          </li>\n";
        }
        $this->html = "<nav {$this->options['nav']['class']} aria-label=\"breadcrumb\">"
            . "<ol class=\"breadcrumb {$this->options['ol']['class']}\">"
            . $this->li 
            . "</ol>\n";
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