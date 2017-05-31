<?php

use yii\db\Migration;

/**
 * Handles the creation of table `services`.
 */
class m170530_143610_create_service_table extends Migration
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

        $this->createTable('{{%service}}', [
            'id' => $this->primaryKey(),
            'identity_id' => $this->integer()->notNull(),
            'service_name' => $this->string()->notNull(),
            'user_id' => $this->integer()->notNull()
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%service}}');
    }
}
