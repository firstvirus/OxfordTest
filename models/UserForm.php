<?php

namespace app\modules\OxfordTest\models;

use app\modules\OxfordTest\models\Users;
use yii\db\ActiveRecord;

/**
 * Описание UserForm
 * Основная модель работы с пользователями.
 *
 * @package OxfordTest
 * @version 0.1.63
 * 
 * @author virus
 */
class UserForm extends ActiveRecord {

    /**
     * Копия класса с интерфейсом IdentityInterface
     * @var type 
     */
    private $_user;

    /**
     * public static function tableName()
     * Возвращает имя таблицы пользователей
     * @return string
     */
    public static function tableName() {
        return 'oxford_test_users';
    }

    /**
     * Возвращает массив с аттрибутами для формы аутентификации
     * @return type
     */
    public function attributeLabels() {
        return [
            'username' => 'Имя',
            'sirname' => 'Фамилия',
            'age' => 'Возраст',
        ];
    }

    /**
     * Возвращает массив с правилами валидации данных о пользователе
     * @return type
     */
    public function rules() {
        return [
            [['username', 'sirname', 'age'], 'required', 'message' => 'Поле не должно быть пустым.'],
            [['username', 'sirname', 'age'], 'trim'],
            ['username', 'string', 'length' => [2, 30]],
            ['sirname', 'string', 'length' => [1, 50]],
        ];
    }

    /**
     * Перед сохранением нового пользователя генерирует ключ
     * автоматического входа
     * @param type $insert
     * @return boolean
     */
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }

    /**
     * Возвращает копию классас интерфейсом IdentityInterface для
     * аутентификации пользователя
     * @param type $id
     * @return type
     */
    public function login($id) {
        $this->_user = Users::findIdentity($id);

        return $this->_user;
    }

}
