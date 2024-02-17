<?php

namespace app\controllers\api;

use app\models\LoginForm;
use app\models\SignupForm;
use app\models\User;
use Yii;
use yii\base\ErrorException;
use yii\rest\Controller;
use OpenApi\Annotations as QA;

/**
 * @QA\Info(
 *      title="Post API",
 *      version="1.0"
 * )
 */
class DefaultController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api",
     *     description="Home page",
     *     @OA\Response(response="200", description="Welcome page")
     * )
     */
    public function actionIndex()
    {
        return 'api';
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     description="Login page",
     *     @QA\RequestBody(
     *          required=true,
     *          @QA\JsonContent(
     *                  type="object",
     *                  @QA\Property(
     *                      property="login",
     *                      description="user login",
     *                      type="string"
     *                  ),
     *                  @QA\Property(
     *                      property="password",
     *                      description="user password",
     *                      type="string"
     *                  )
     *          )
     *     ),
     *     @OA\Response(response="200", description="Token",@QA\JsonContent(ref="#/components/schemas/Token")),
     *     @QA\Response(response="500", description="Login model",
     *          @QA\JsonContent(
     *              type="object",
     *              @QA\Property(
     *                  property="login",
     *                  description="user login",
     *                  type="string"
     *              ),
     *             @QA\Property(
     *                 property="password",
     *                 description="user password",
     *                 type="string"
     *              )
     *          )
     *     )
     * )
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        $model->load(Yii::$app->request->bodyParams, '');

        if ($token = $model->auth()) {
            Yii::$app->response->setStatusCode(200);
            return $token;
        } else {
            Yii::$app->response->setStatusCode(500);
            return $model;
        }
    }

    public function actionSignUp()
    {
        $model = new SignupForm;
        $model->load(Yii::$app->request->bodyParams, '');
        if(!$user = $model->signup()) {
            throw new ErrorException('Произошла ошибка при сохранении модели пользователя.');
        } else {
            return [
                'status' => 'success',
                'data' => $user
            ];
        }

    }

    protected function verbs()
    {
        return [
            'login' => ['post'],
            'sign-up' => ['post']
        ];
    }
}