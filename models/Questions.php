<?php

namespace app\modules\OxfordTest\models;

use yii\db\ActiveRecord;

/**
 * Модель для работы с таблицей вопросов oxford_test_questions.
 *
 * @package OxfordTest
 * @version 0.1.63
 * 
 * @author virus
 */
class Questions extends ActiveRecord
{

    /**
     * Количество вопросов задается тут
     * @const
     */
    const QUESTIONS_COUNT = 200;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'oxford_test_questions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['question'], 'required'],
            [['question'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'question' => 'Question',
        ];
    }

}
