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
class UserForm extends ActiveRecord
{

    /**
     * Копия класса с интерфейсом IdentityInterface
     * @var object 
     */
    private $_user;

    /**
     * public static function tableName()
     * Возвращает имя таблицы пользователей
     * @return string
     */
    public static function tableName()
    {
        return 'oxford_test_users';
    }

    /**
     * Возвращает массив с аттрибутами для формы аутентификации
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Имя',
            'surname' => 'Фамилия',
            'age' => 'Возраст',
        ];
    }

    /**
     * Возвращает массив с правилами валидации данных о пользователе
     * @return array
     */
    public function rules()
    {
        return [
            [['username', 'surname', 'age'], 'required', 'message' => 'Поле не должно быть пустым.'],
            [['username', 'surname', 'age'], 'trim'],
            ['username', 'string', 'length' => [2, 30]],
            ['surname', 'string', 'length' => [1, 50]],
        ];
    }

    /**
     * Перед сохранением нового пользователя генерирует ключ
     * автоматического входа
     * @param object $insert
     * @return boolean
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }

    /**
     * Возвращает копию класса с интерфейсом IdentityInterface для
     * аутентификации пользователя
     * @param int $id
     * @return object
     */
    public function login($id)
    {
        $this->_user = Users::findIdentity($id);

        return $this->_user;
    }

}
