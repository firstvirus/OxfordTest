<?php

namespace app\modules\OxfordTest\models;

use yii\db\ActiveRecord;

/**
 * Модель для работы с таблицей ответов oxford_test_answers
 *
 * @package OxfordTest
 * @version 0.1.63
 * 
 * @author virus
 */
class Answers extends ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'oxford_test_answers';
    }

    public function formName() {
        return 'answer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_client', 'id_question', 'answer'], 'required'],
            [['id_client'], 'integer', 'min' => 1],
            [['id_question'], 'integer', 'max' => 200, 'min' => 1],
            ['answer', function ($attribute, $params) {
                    if (!in_array($this->$attribute, ['Y', 'M', 'N'])) {
                        $this->addError($attribute, 'Ответ может быть или Y, или M, или N');
                    }
                }],
        ];
    }

}
