<?php

namespace app\commands;

use app\rbac\rules\PostOwnerRule;

class RbacController extends \yii\console\Controller
{
    public function actionInit()
    {
        $auth = \Yii::$app->authManager;
        $auth->removeAll();

        $rule = new PostOwnerRule();
        $auth->add($rule);

        $ownPost = $auth->createPermission('ownPost');
        $ownPost->ruleName = $rule->name;
        $auth->add($ownPost);

        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $ownPost);
    }
}