<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m210812_140659_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull()->unique()->comment('E-mail'),
            'password' => $this->string()->notNull()->comment('Пароль'),
            'partner_id' => $this->string()->notNull()->unique()->comment('Партнер id'),

            'created_at' => $this->integer()->notNull()->comment('Дата создания'),
            'updated_at' => $this->integer()->comment('Дата обновления'),
        ]);

        $this->createIndex('idx-user-email', 'user', 'email');

        $this->insert('user', [
            'id' => 1,
            'email' => 'admin@mail.ru',
            'password' => Yii::$app->getSecurity()->generatePasswordHash(Yii::$app->params['admin_password']),
            'partner_id' => Yii::$app->getSecurity()->generateRandomString(10),

            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
