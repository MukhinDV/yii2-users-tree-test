<?php

namespace app\models;

use app\models\query\UserQuery;
use creocoder\nestedsets\NestedSetsBehavior;
use Yii;

/**
 * This is the model class for table "user_tree".
 *
 * @property int $id
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 * @property int $user_id
 *
 * @property User $user
 */
class UserTree extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::class,
                //'treeAttribute' => 'tree',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_tree';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lft', 'rgt', 'depth', 'user_id'], 'required'],
            [['lft', 'rgt', 'depth', 'user_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
