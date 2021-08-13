<?php


namespace app\components;


use app\models\User;

class UserComponent
{
    /**
     * @param $email
     *
     * @return User|null
     */
    private function findModel($email)
    {
        return User::findOne(['email' => $email]);
    }

    /**
     * This method makes user authorization
     *
     * @param $password
     * @param $login
     *
     * @return bool
     */
    public function authUser($password, $login)
    {
        /** @var User $model */
        $model = $this->findModel($login);

        if ($model == null || !$this->checkPassword($password, $model->password)
            || !\Yii::$app->user->login($model, 3600)) {
            return false;
        }

        return true;
    }

    /**
     * Verifies a password against a hash.
     *
     * @param $password
     * @param $passwordHash
     *
     * @return bool
     */
    private function checkPassword($password, $passwordHash)
    {
        return \Yii::$app->security->validatePassword($password, $passwordHash);
    }
}