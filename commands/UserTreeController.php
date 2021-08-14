<?php


namespace app\commands;


use app\models\UserTree;
use yii\console\Controller;

class UserTreeController extends Controller
{
    /**
     * Create first node on first user
     */
    public function actionCreateFirstNode()
    {
        $user_tree = new UserTree(['user_id' => 1]);
        $user_tree->lft = 1;
        $user_tree->rgt = 2;
        $user_tree->depth = 0;
        $user_tree->makeRoot();
    }
}