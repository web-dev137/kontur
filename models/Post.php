<?php

namespace app\models;

use Yii;
use OpenApi\Annotations as QA;

/**
 * @OA\Schema(
 *     title="Post",
 *     required={"post"}
 *  )
*/
class Post extends \yii\db\ActiveRecord
{
    /**
     *  @OA\Property(
     *     property="id",
     *     type="integer",
     *     description="ID",
     *     readOnly="true",
     *     example=1
     *  ),
     * @property string $id
     *
     *  @OA\Property(
     *     property="author_id",
     *     type="integer",
     *     description="Server current date time",
     *     readOnly="true",
     *     example=1
     *  ),
     * @property string $author_id
     *
     *  @OA\Property(
     *     property="post",
     *     type="string",
     *     description="Text post",
     *     example="post1 first"
     *  ),
     * @property string $post
     *
     *  @OA\Property(
     *     property="status",
     *     type="string",
     *     description="status: public, auth, private",
     *     example="auth"
     *  ),
     * @property string $status
     *
     * @QA\Property(
     *     property="reply",
     *     type="array",

     *     @QA\Items(ref="#/components/schemas/Reply")
     * )
     * @property Reply[] $replies
     */
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