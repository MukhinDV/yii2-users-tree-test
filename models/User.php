<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Exception;
use yii\web\IdentityInterface;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $email E-mail
 * @property string $password Пароль
 * @property string $partner_id Партнер id
 * @property int $created_at Дата создания
 * @property int|null $updated_at Дата обновления
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @var string
     */
    public ?string $parent_partner_id = null;

    const SCENARIO_REGISTER = 'register';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /** {@inheritdoc} */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email'], 'required', 'on' => self::SCENARIO_REGISTER],
            [['email', 'password'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['email', 'password', 'partner_id'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['email'], 'email'],
            ['parent_partner_id', 'checkPartnerId'],
            [['partner_id'], 'unique'],
        ];
    }

    /**
     * validation rule
     *
     * @return bool
     */
    public function checkPartnerId()
    {
        if ($this::findOne(['partner_id' => $this->parent_partner_id])) {
            return true;
        }

        $this->addError('parent_partner_id', 'Такого уникального номера нет');
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'E-mail',
            'password' => 'Пароль',
            'partner_id' => 'Партнер id',
            'parent_partner_id' => 'Партнер id',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @param bool $insert
     *
     * @return bool
     *
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->partner_id = Yii::$app->getSecurity()->generateRandomString(10);
            $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        }

        return parent::beforeSave($insert);
    }

    /**
     * create userTree node
     */
    public function createNode()
    {
        $user_tree_parent = ($this::findOne(['partner_id' => $this->parent_partner_id]))->userTree;

        // The tree itself will substitute the necessary values for attributes (lft/rgt/depth)
        $user_tree = new UserTree(['user_id' => $this->id]);
        $user_tree->lft = 1;
        $user_tree->rgt = 2;
        $user_tree->depth = 0;
        $user_tree->appendTo($user_tree_parent);
    }

    /**
     * Gets query for [[UserTree]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserTree()
    {
        return $this->hasOne(UserTree::class, ['user_id' => 'id']);
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->email;
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     *
     * @return IdentityInterface|null the identity object that matches the given ID.
     *
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return User::find()->andWhere(['id' => $id])->one();
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     *
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $token
     * @param null $type
     *
     * @return void|IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * @return string|void|null
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    /**
     * @param string $authKey
     *
     * @return bool|void|null
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }
}
