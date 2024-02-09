<?php

namespace app\rbac\rules;


use yii\base\InvalidCallException;

class PostOwnerRule extends \yii\rbac\Rule
{

    public $name = 'ownPost';
    /**
     * @inheritDoc
     */
    public function execute($user, $item, $params)
    {
        if(empty($params['post'])){
            throw new InvalidCallException("You are editing a not  exist post");
        }


        return $params['post']->author_id == $user;
    }
}