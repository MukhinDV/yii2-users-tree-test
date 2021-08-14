<?php


namespace app\models\query;

use creocoder\nestedsets\NestedSetsQueryBehavior;

class UserQuery extends \yii\db\ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::class
        ];
    }
}