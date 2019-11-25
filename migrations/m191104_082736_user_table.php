<?php

use yii\db\Migration;

/**
 * Class m191104_082736_user_table
 */
class m191104_082736_user_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('oxford_test_users', [
            'id' => $this->primaryKey(),
            'username' => $this->string(30),
            'surname' => $this->string(50),
            'age' => $this->integer(3),
            'answer' => $this->text(),
            'date' => $this->date(),
            'last_question' => $this->integer(3)->defaultValue(0),
            'auth_key' => $this->string(255),
        ]);
        // Вставка дефолтного админа
        $this->insert('oxford_test_users', [
            'username' => 'admin',
            'surname' => 'admin',
            'age' => 0,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('oxford_test_users');

        return true;
    }

}
