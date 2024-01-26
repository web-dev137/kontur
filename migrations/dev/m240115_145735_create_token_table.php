<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%token}}`.
 */
class m240115_145735_create_token_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%token}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'expired_at' => $this->integer(),
            'token' => $this->string()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%token}}');
    }
}
