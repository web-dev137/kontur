<?php

namespace app\models;

use OpenApi\Annotations as QA;

/**
 * @QA\Schema(
 *   schema="Token"
 * ),
 *
 * @OA\Property(
 *     property="id",
 *     type="integer",
 *     description="ID"
 * ),
 * @property integer $id
 *
 * @OA\Property(
 *     property="user_id",
 *     type="integer",
 *     description="ID user"
 * ),
 * @property integer $user_id
 *
 * @OA\Property(
 *     property="expired_at",
 *     type="integer",
 *     description="expired time"
 * ),
 * @property integer $expired_at
 *
 * @OA\Property(
 *     property="token",
 *     type="string",
 *     description="Token"
 * ),
 * @property string $token
 */
class Token extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%token}}';
    }

    public function generateToken(int $expire)
    {
        $this->expired_at = $expire;
        $this->token = \Yii::$app->security->generateRandomString();
    }

    public function fields()
    {
        return [
            'token',
            'expired' => function() {
                return date(DATE_RFC3339,$this->expired_at);
            }
        ];
    }
}