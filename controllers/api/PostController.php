<?php

namespace app\controllers\api;

use app\models\Post;
use app\models\Reply;
use app\models\User;
use Yii;
use yii\base\ErrorException;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use OpenApi\Annotations as QA;

/**
 * @OA\Post(
 *     tags={"Authorize"},
 *     path="/api/post/create",
 *     security={{ "Bearer":{} }},
 *     description="Reply on post",
 *     @QA\RequestBody(
 *          required=true,
 *          @QA\JsonContent(
 *              type="object",
 *              @OA\Property(
 *                  property="post",
 *                  description="post",
 *                  type="string",
 *                  example="post1 first"
 *              ),
 *             @QA\Property(
 *                  property="status",
 *                  description="status(public,auth,private) of the post",
 *                  type="string",
 *                  example="auth"
 *             )
 *          )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Post",
 *         @QA\JsonContent(ref="#/components/schemas/Post")
 *     ),
 *     @QA\Response(response="500", description="Validation error")
 * )
 */
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
     * @OA\Get(
     *     tags={"Authorize"},
     *     path="get-my-posts",
     *     description="Return posts of current user",
     *     security={{ "Bearer":{} }},
     *     @QA\Response(response=200,description="success",@QA\JsonContent(type="array",@QA\Items(ref="#/components/schemas/Post")))
     * )
     */
    public function actionGetMyPosts(): array
    {
        $response = Yii::$app->getResponse();
        $response->format = Response::FORMAT_JSON;
        $response->setStatusCode(200);
        return Post::findAll(['author_id' => Yii::$app->user->getId()]);
    }


    /**
     * @OA\Get(
     *     tags={"Authorize"},
     *     path="get-all-posts",
     *     description="Return all posts",
     *     security={{ "Bearer":{} }},
     *     @QA\Response(response=200,description="success",@QA\JsonContent(type="array",@QA\Items(ref="#/components/schemas/Post")))
     * )
     */
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
     * @OA\Get(
     *     path="get-posts",
     *     description="Return posts for all users",
     *     @QA\Response(response=200,description="success",@QA\JsonContent(type="array",@QA\Items(ref="#/components/schemas/Post")))
     * )
     * @return Post[]
     */
    public function actionGetPosts():array
    {
        $response = Yii::$app->getResponse();
        $response->setStatusCode(200);
        return Post::findAll(['status' => Post::GUEST]);
    }

    /**
     * @OA\Get(
     *     tags={"Authorize"},
     *     path="get-replies/{post_id}",
     *     description="Return replies of specify post",
     *     security={{ "Bearer":{} }},
     *     @QA\Parameter(
     *          name="post_id",
     *          in="path",
     *          required=true,
     *          description="post Id",
     *          @QA\Schema(
     *              type="integer",
     *              format="int64"
     *          ),
     *          example=1
     *     ),
     *     @QA\Response(response=200,description="success",
     *          @QA\JsonContent(type="array",@QA\Items(ref="#/components/schemas/Reply"))
     *     )
     * )
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
     * @OA\Post(
     *     tags={"Authorize"},
     *     path="/api/reply/{post_id}",
     *     security={{ "Bearer":{} }},
     *     description="Reply on post",
     *     @QA\Parameter(
     *          name="post_id",
     *          in="path",
     *          description="The ID of the post the user is responding to.",
     *          required=true,
     *          @QA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *     ),
     *     @QA\RequestBody(
     *          required=true,
     *          @QA\JsonContent(ref="#/components/schemas/Reply")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Reply",
     *         @QA\JsonContent(ref="#/components/schemas/Reply")
     *     ),
     *     @QA\Response(response="500", description="Validation error")
     * )
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