<?php

use yii\db\Migration;

/**
 * Class m231212_172357_create_rbac_tables
 */
class m231212_172357_create_rbac_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        try {
            (new yii\console\controllers\MigrateController('migrate', Yii::$app))
                ->runAction('up', ['migrationPath' => '@yii/rbac/
                migrations/', 'interactive' => false]);
        } catch (\yii\base\InvalidRouteException | \yii\console\Exception $e) {
            echo $e->getMessage();
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231212_172357_create_rbac_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231212_172357_create_rbac_tables cannot be reverted.\n";

        return false;
    }
    */
}
