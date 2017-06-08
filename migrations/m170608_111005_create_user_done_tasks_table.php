<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_done_tasks`.
 */
class m170608_111005_create_user_done_tasks_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%done_tasks}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'task_id' => $this->integer()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%done_tasks}}');
    }
}
