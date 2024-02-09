<?php

namespace app\models;

use Yii;

/**
 * @property integer $id
 * @property integer $author_id
 * @property string $post
 * @property string $status
 * @property Reply[] $replies
 */
class Post extends \yii\db\ActiveRecord
{
    const GUEST = 'public';
    const AUTH = 'auth';
    const SPECIFY = 'private';
    public static function tableName()
    {
        return '{{%post}}';
    }

    public function rules()
    {
        return [
            [['post'],'required'],
            [['post','status'],'string'],
            [['status'],'in', 'range' => ['public','auth','private']],
            [['id','author_id'],'integer'],
            ['author_id','safe']
        ];
    }

    public function beforeSave($insert)
    {
        $this->author_id = Yii::$app->user->getId();
        return parent::beforeSave($insert);
    }

    public function getReplies()
    {
        return $this->hasMany(Reply::class,['post_id'=>'id']);
    }

    public function fields()
    {
        return [
            'id',
            'author_id',
            'post',
            'status'
        ];
    }
}