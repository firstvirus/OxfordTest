<?php

use yii\db\Migration;

/**
 * Класс m191107_113127_init_rbac
 * Создает роли пользователя и админа. Назначает дефолтного
 * админа на должность.
 */
class m191107_113127_init_rbac extends Migration {

    public function up() {
        $auth = Yii::$app->authManager;

        $user = $auth->createRole('user');
        $auth->add($user);

        $admin = $auth->createRole('admin');
        $auth->add($admin);

        $auth->assign($admin, 1);
    }

    public function down() {
        $auth = Yii::$app->authManager;

        $auth->removeAll();
    }

}
