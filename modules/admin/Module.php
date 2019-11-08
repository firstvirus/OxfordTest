<?php

namespace app\modules\OxfordTest\modules\admin;

/**
 * OxfordTest module definition class
 */
class Module extends \yii\base\Module {

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\OxfordTest\modules\admin\controllers';
    public $defaultRoute = 'admin';
    public $moduleTitle = 'Оксфордский тест личности : Панель администратора';

    /**
     * {@inheritdoc}
     */
    public function init() {
        parent::init();
    }

}
