<?php

namespace app\fixtures;

class ReplyFixture extends \yii\test\ActiveFixture
{
    public $modelClass = 'app\models\Reply';
    public $depends = ['app\fixtures\ReplyFixture'];
}