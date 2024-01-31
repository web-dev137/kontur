<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%message}}`.
 */
class m240122_122644_create_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%message}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'recipient' => $this->integer()->notNull(),
            'msg' => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%message}}');
    }
}
