<?php

use yii\db\Migration;

/**
 * Handles adding points to table `user`.
 */
class m170602_091344_add_points_column_to_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%user}}', 'points', $this->integer()->defaultValue(0));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%user}}', 'points');
    }
}
