<?php

namespace app\modules\OxfordTest;

/**
 * OxfordTest module definition class
 */
class Module extends \yii\base\Module {

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\OxfordTest\controllers';
    public $defaultRoute = 'user';
    public $moduleTitle = 'Оксфордский тест личности';

    /**
     * {@inheritdoc}
     */
    public function init() {
        parent::init();

        $this->modules = [
            'admin' => [
                // здесь имеет смысл использовать более лаконичное пространство имен
                'class' => 'app\modules\OxfordTest\modules\admin\Module',
            ],
        ];



        // custom initialization code goes here
    }

}
