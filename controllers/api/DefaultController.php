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
 * ),
 * @OA\SecurityScheme(
 *   securityScheme="Bearer",
 *   scheme="bearer",
 *   type="http"
 * ),
 */
class DefaultController extends Controller
{

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
     *                ref="#/components/schemas/UserRequest"
     *          )
     *     ),
     *     @OA\Response(response="200", description="Token",@QA\JsonContent(ref="#/components/schemas/Token")),
     *     @QA\Response(
     *          response="500",
     *          description="Login model",
     *          @QA\JsonContent(ref="#/components/schemas/UserRequest")
     *      )
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


    /**
     * @OA\Post(
     *     path="/api/sign-up",
     *     description="SignUp",
     *     @QA\RequestBody(
     *          required=true,
     *          @QA\JsonContent(ref="#/components/schemas/UserRequest")
     *     ),
     *     @OA\Response(response="200", description="User",@QA\JsonContent(ref="#/components/schemas/User")),
     *     @QA\Response(response="500", description="Throw exception")
     * )
     *
     * @return User|null
     * @throws ErrorException
     */
    public function actionSignUp()
    {
        $model = new SignupForm;
        $model->load(Yii::$app->request->bodyParams, '');
        if(!$user = $model->signup()) {
            throw new ErrorException('Произошла ошибка при сохранении модели пользователя.');
        } else {
            return $user;
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