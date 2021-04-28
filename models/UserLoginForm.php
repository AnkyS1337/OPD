<?php

namespace app\models;

use Yii;
use yii\base\Model;

class UserLoginForm extends Model
{
    public $email;
    public $password;
    private $userRecord;
    public $remember;

    public function rules()
    {
        return [
          [['email','password'],'required','message'=>'Данное поле должно быть заполнено'],
          ['email','email'],
            ['email','errorIfEmailNotFound'],
            ['password','errorIfPasswordWrong'],
            ['remember','boolean'],
        ];
    }

    public function errorIfEmailNotFound()
    {
        $this->userRecord = UserRecord::findUserByEmail($this->email);
        if ($this->userRecord->email == null)
            $this->addError('email',"Такая почта не зарегистрирована");
    }

    public function errorIfPasswordWrong()
{
    if($this->hasErrors())
        return;
    if (!$this->userRecord->validatePassword($this->password))
        $this->addError('password','Неверный пароль');
}

    public function login()
    {
        if ($this->hasErrors())
            return;
        $userIdentity = UserIdentity::findIdentity($this->userRecord->id);
        Yii::$app->user->login($userIdentity,
            $this->remember ? 3600 * 24 * 30 : 0);
    }
    public function attributeLabels()
    {
        return
            [
                'email'=>'Email',
                'password'=>'Пароль',
                'remember'=>'Запомнить меня',
            ];
    }

}