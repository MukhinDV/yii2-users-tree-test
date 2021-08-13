<?php

/* @var $this yii\web\View */

use app\models\User;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <?php
    if (!Yii::$app->user->isGuest) { ?>
        <div class="row">
            <h1>Партнерский id= <?= User::findOne([Yii::$app->user->getId()])->partner_id ?></h1>
        </div>
    <?php } else
        echo '<div class="row">
                    <h1>Войдите в систему</h1>
             </div>';
    ?>

</div>
