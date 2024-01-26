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

        $updateMessage = $auth->createPermission('updateMessage');
        $updateMessage->ruleName = $rule->name;
        $auth->add($updateMessage);

        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $updateMessage);
    }
}