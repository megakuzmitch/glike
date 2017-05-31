<?php

use yii\db\Migration;

/**
 * Handles the creation of table `task`.
 */
class m170531_131523_create_task_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%task}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'description' => $this->string(),
            'link' => $this->string()->notNull(),
            'points' => $this->integer()->notNull()->defaultValue(0),
            'service_type' => $this->smallInteger()->notNull(),
            'task_type' => $this->smallInteger()->notNull(),
            'user_id' => $this->integer()->notNull()
        ], $tableOptions);

        $this->addForeignKey('ifk_task_user', '{{%task}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('ifk_task_user', '{{%task}}');
        $this->dropTable('{{%task}}');
    }
}
