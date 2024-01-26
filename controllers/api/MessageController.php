<?php

namespace app\controllers\api;


use app\models\Message;
use app\models\User;
use app\rbac\rules\MessageOwnerRule;
use http\Client\Response;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;


class MessageController extends \yii\rest\Controller
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::class,
            HttpBearerAuth::class,
        ];
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['get-messages','create'],
                    'roles' => ['@']
                ],
                [
                    'allow' => true,
                    'actions' => ['update'],
                    'roles' => ['updateMessage'],
                    'roleParams' => [
                        'message' => Message::findOne(['author_id'=>Yii::$app->user->id])
                    ]
                ]
            ],
        ];

        return $behaviors;
    }



    public function actionGetMessages()
    {
        return "msg";
    }

    public function actionCreate()
    {
        return "create";
    }

    public function actionUpdate()
    {
        return "update";
    }
}