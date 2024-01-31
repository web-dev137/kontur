<?php

namespace app\rbac\rules;

use app\models\Message;
use app\models\User;
use yii\base\InvalidCallException;
use yii\rbac\Item;

class MessageOwnerRule extends \yii\rbac\Rule
{

    public $name = 'ownerMessage';
    /**
     * @inheritDoc
     */
    public function execute($user, $item, $params)
    {
        if(empty($params['message'])){
            throw new InvalidCallException("You are editing a not  exist message");
        }


        return $params['message']->author_id == $user;
    }
}