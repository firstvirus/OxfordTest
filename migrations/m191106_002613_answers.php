<?php

use yii\db\Migration;

/**
 * Class m191106_002613_oxford_test_answers
 */
class m191106_002613_answers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('oxford_test_answers', [
            'id' => $this->primaryKey(),
            'id_client' => $this->integer(),
            'id_question' => $this->integer(3),
            'answer' => $this->char(1),
            'date' => $this->date(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('oxford_test_answers');

        return true;
    }

}
