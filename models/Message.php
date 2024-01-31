<?php

namespace app\models;

use Yii;

/**
 * @property integer $id
 * @property integer $author_id
 * @property integer $recipient
 * @property integer $parent_msg_id
 * @property string $msg
 */
class Message extends \yii\db\ActiveRecord
{
    const SCENARIO_REPLY = 'reply';
    const SCENARIO_CREATE = 'create';

    public static function tableName()
    {
        return '{{%message}}';
    }

    public function rules()
    {
        return [
            [['msg','recipient'],'required','on' => [self::SCENARIO_CREATE]],
            [['msg'],'required','on' => self::SCENARIO_REPLY],
            ['msg','string'],
            [['id','recipient','parent_msg_id'],'integer'],
            ['author_id','safe']
        ];
    }

    public function beforeSave($insert)
    {
        $this->author_id = Yii::$app->user->getId();
        return parent::beforeSave($insert);
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_REPLY] = ['msg'];
        $scenarios[self::SCENARIO_CREATE] = ['msg','recipient'];

        return $scenarios;
    }

    public function fields()
    {
        return [
            'id',
            'author_id',
            'recipient',
            'msg'
        ];
    }
}