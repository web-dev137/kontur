<?php

namespace app\controllers\api;

use app\models\LoginForm;
use app\models\SignupForm;
use app\models\User;
use Yii;
use yii\base\ErrorException;
use yii\rest\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return 'api';
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        $model->load(Yii::$app->request->bodyParams, '');

        if ($token = $model->auth()) {
            return $token;
        } else {
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