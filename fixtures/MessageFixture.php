<?php

namespace app\fixtures;

class MessageFixture extends \yii\test\ActiveFixture
{
    public $modelClass = 'app\models\Message';
    public $depends = ['app\fixtures\MessageFixture'];
}