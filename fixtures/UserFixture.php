<?php

namespace app\fixtures;

class UserFixture extends \yii\test\ActiveFixture
{
    public $modelClass = 'app\models\User';
    public $depends = ['app\fixtures\UserFixture'];
}