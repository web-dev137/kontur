<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%reply}}`.
 */
class m240203_104743_create_reply_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%reply}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'reply' => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%reply}}');
    }
}
