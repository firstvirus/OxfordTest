<?php

namespace app\modules\OxfordTest\models;

use yii;
use yii\db\ActiveRecord;

/**
 * Описание модели Users
 * Вспомогательная модель для аутентификации пользователей.
 * 
 * @package OxfordTest
 * @version 0.1.63
 *
 * @author virus
 */
class Users extends ActiveRecord implements \yii\web\IdentityInterface {

    /**
     * public static function tableName()
     * Возвращает имя таблицы пользователей
     * @return string
     */
    public static function tableName() {
        return 'oxford_test_users';
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id) {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId() {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey) {
        return $this->auth_key === $authKey;
    }

    /**
     * Возвращает роль конкретного пользователя.
     * @param type $id
     * @return type
     */
    public function getRole($id) {
        $roles = Yii::$app->authManager->getRolesByUser($id);
        foreach ($roles as $role) {
            return $role->name;
        }
    }

}
