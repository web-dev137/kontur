<?php

namespace app\models;

use yii\base\Model;

class SignupForm extends Model
{
    public string $login;
    public string $password;
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['login', 'password'], 'required'],
            // password is validated by validatePassword()
            ['password', 'string','min'=>6],
        ];
    }

    public function signup(): ?User
    {
        if($this->validate()) {
            $user = new User();
            $user->login = $this->login;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            return $user->save() ? $user : null;
        }
    }
}