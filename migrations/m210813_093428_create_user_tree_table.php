<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_tree}}`.
 */
class m210813_093428_create_user_tree_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_tree}}', [
            'id' => $this->primaryKey(),
            'lft' => $this->integer(),
            'rgt' => $this->integer(),
            'depth' => $this->integer(),
            'user_id' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-user-tree-user_id', 'user_tree', 'user_id',
            'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_tree}}');
    }
}
