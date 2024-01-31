<?php

namespace app\controllers\api;


use app\models\Message;
use app\models\User;
use app\rbac\rules\MessageOwnerRule;
use http\Client\Response;
use Yii;
use yii\base\ErrorException;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\web\ForbiddenHttpException;


class MessageController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\Message';

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = [
            'get-messages',
            'reply',
            'create',
            'update',
            'delete'
        ];
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::class,
            HttpBearerAuth::class,
        ];
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => [
                'get-messages',
                'create',
                'update',
                'delete',
                'reply'
            ],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@']
                ]
            ],
        ];

        return $behaviors;
    }



    public function actionGetMessages(int $author_id): array
    {
        return Message::findAll(['author_id' => $author_id]);
    }

    public function actionReply(int $msg_id)
    {
        $msg = Message::findOne(['id' => $msg_id]);
        $newMsg = new Message();
        $newMsg->load(Yii::$app->getRequest()->getBodyParams(),'');
        if($msg && $newMsg->validate()) {
            $newMsg->parent_msg_id = $msg->id;
            $newMsg->recipient = $msg->author_id;
            $newMsg->save();
            return [
                'status' => 'success',
                'data' => $newMsg
            ];
        }
        throw new ErrorException('Произошла ошибка при сохранении модели пользователя.');
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if (in_array($action, ['update','delete','get-messages'])) {
            if (!Yii::$app->user->can(User::OWN_MESSAGE, ['message' => $model])) {
                throw  new ForbiddenHttpException('Forbidden.');
            }
        }
    }

    protected function verbs()
    {
        return [
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH'],
            'delete' => ['DELETE'],
        ];
    }
}