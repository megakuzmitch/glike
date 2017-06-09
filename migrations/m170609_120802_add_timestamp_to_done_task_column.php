<?php

use yii\db\Migration;

class m170609_120802_add_timestamp_to_done_task_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%done_tasks}}', 'created_at', $this->integer());
        $this->addColumn('{{%done_tasks}}', 'updated_at', $this->integer());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%done_tasks}}', 'created_at');
        $this->dropColumn('{{%done_tasks}}', 'updated_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170609_120802_add_timestamp_to_done_task_column cannot be reverted.\n";

        return false;
    }
    */
}
