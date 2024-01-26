<?php

namespace app\models;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $expired_at
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