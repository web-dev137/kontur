<?php

use app\models\Message;
use yii\db\Migration;

/**
 * Class m240130_171719_add_parent_msg_id_column
 */
class m240130_171719_add_parent_msg_id_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Message::tableName(),'parent_msg_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Message::tableName(),'parent_msg_id');
    }

}
