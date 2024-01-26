<?php

namespace app\fixtures;

class TokenFixture extends \yii\test\ActiveFixture
{
    public $modelClass = 'app\models\Token';
    public $depends = ['app\fixtures\TokenFixture'];
}