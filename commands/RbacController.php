<?php

namespace app\commands;

use app\rbac\rules\MessageOwnerRule;

class RbacController extends \yii\console\Controller
{
    public function actionInit()
    {
        $auth = \Yii::$app->authManager;
        $auth->removeAll();

        $rule = new MessageOwnerRule();
        $auth->add($rule);

        $ownMessage = $auth->createPermission('ownMessage');
        $ownMessage->ruleName = $rule->name;
        $auth->add($ownMessage);

        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $ownMessage);
    }
}