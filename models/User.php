<?php

namespace app\models;



use app\models\Token;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use OpenApi\Annotations as QA;

/**
 * @OA\Schema(
 *     title="User",
 *     required={"login"}
 *  ),
 * @QA\Property(
 *     property="id",
 *     type="int",
 *     description="ID",
 *     readOnly=true,
 *     example="1"
 * ),
 * @property int $id
 *
 * @QA\Property(
 *     property="login",
 *     type="string",
 *     description="Login",
 *     example="userNew"
 * ),
 * @property string $login
 * @property string $password_hash
 *
 * @QA\Property(
 *     property="auth_key",
 *     type="string",
 *     description="Auth key",
 *     readOnly=true,
 *     example="JU_kCvpzBC03YSkUbloLcJ6G0v1_UY75"
 * ),
 * @property string $auth_key
 *
 * @QA\Property(
 *     property="is_archived",
 *     type="string",
 *     description="Is archived user",
 *     readOnly=true,
 *     example="0"
 * ),
 * @property bool $is_archived
 *
 * @QA\Property(
 *     property="created_at",
 *     type="int",
 *     description="Created at",
 *     readOnly=true,
 *     example="1705700550"
 * ),
 * @property int $created_at
 *
 * @QA\Property(
 *     property="updated_at",
 *     type="int",
 *     description="Updated at",
 *     readOnly=true,
 *     example="1705700550"
 * ),
 * @property int $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    const POST_OWNER = 'ownPost';
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }


    public function rules()
    {
        return [
            [['login','password_hash','auth_key'],'required'],
            [['created_at','updated_at'], 'safe'],
            [['login','password_hash'],'string'],
            ['id','integer']
        ];
    }

    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne(["id"=>$id]);
    }


    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()
            ->joinWith('tokens t')
            ->andWhere(['t.token' => $token])
            ->andWhere(['>', 't.expired_at', time()])
            ->one();
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return User
     */
    public static function findByUsername(string $username): User
    {
        return self::findOne(["login" => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): ?bool
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password,$this->password_hash);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTokens()
    {
        return $this->hasMany(Token::class, ['user_id' => 'id']);
    }

    /**
     *  Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password_hash = \Yii::$app->security->generatePasswordHash($password);
    }

    public function fields()
    {
        return [
            'id',
            'login',
            'created_at' => function() {
                return date(DATE_RFC3339,$this->created_at);
            }
        ];
    }

}
