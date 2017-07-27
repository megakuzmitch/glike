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
            'need_count' => $this->integer()->defaultValue(0),
            'counter' => $this->integer()->defaultValue(0),
            'user_id' => $this->integer()->notNull(),
        ], $tableOptions);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%task}}');
    }
}
