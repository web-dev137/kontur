<?php

namespace app\controllers\api;


use app\models\Post;
use app\models\Reply;
use app\models\User;
use Yii;
use yii\base\ErrorException;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;


class PostController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\Post';

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = [
            'get-my-posts',
            'get-all-posts',
            'reply',
            'create',
            'update',
            'delete'
        ];
        //$behaviors['authenticator']['except'] = ['token'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::class,
            HttpBearerAuth::class
        ];
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => [
                'get-posts',
                'get-my-posts',
                'get-all-posts',
                'create',
                'update',
                'delete',
                'reply'
            ],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => [
                        'get-my-posts',
                        'get-all-posts',
                        'create',
                        'update',
                        'delete',
                        'reply'
                    ],
                    'roles' => ['@']
                ],
                [
                    'allow' => true,
                    'actions' => ['get-posts'],
                    'roles' => ['?']
                ]
            ],
        ];

        return $behaviors;
    }

    /**
     * @return array
     */
    public function actionGetMyPosts(): array
    {
        $response = Yii::$app->getResponse();
        $response->format = Response::FORMAT_JSON;
        $response->setStatusCode(200);
        return Post::findAll(['author_id' => Yii::$app->user->getId()]);
    }


    public function actionGetAllPosts()
    {
        $response = Yii::$app->getResponse();
        $response->format = Response::FORMAT_JSON;
        $response->setStatusCode(200);
        return Post::find()->where(['or',
            ['=','status',Post::AUTH],
            ['=','status',Post::GUEST],
            ['=','author_id',Yii::$app->user->getId()]
        ])->all();
    }

    /**
     * @return Post[]
     */
    public function actionGetPosts():array
    {
        $response = Yii::$app->getResponse();
        $response->setStatusCode(200);
        return Post::findAll(['status' => Post::GUEST]);
    }

    /**
     * @param int $post_id
     * @return Reply[]
     */
    public function actionGetReplies(int $post_id): array
    {
        $response = Yii::$app->getResponse();
        $response->setStatusCode(200);
        return Reply::findAll(['post_id' => $post_id]);
    }

    /**
     * @param int $post_id
     * @return Reply
     * @throws ErrorException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionReply(int $post_id)
    {
        $reply = new Reply();
        $reply->load(Yii::$app->getRequest()->getBodyParams(),'');
        if($reply->validate()) {
            $reply->post_id = $post_id;
            $reply->save();
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            return $reply;
        }
        throw new ErrorException('Произошла ошибка при сохранении модели пользователя.');
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if (in_array($action, ['update','delete','get-my-posts'])) {
            if (!Yii::$app->user->can(User::POST_OWNER, ['post' => $model])) {
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
            'reply'  => ['POST'],
            'get-replies' => ['GET'],
            'get-posts' => ['GET'],
            'get-all-posts' => ['GET'],
            'get-my-posts' => ['GET'],
            'get-reply' => ['GET']
        ];
    }
}