<?php

namespace app\models;
use yii\base\Model;

class UserJoinForm extends Model{

    public $name;
    public $email;
    public $password;
    public $password2;


    public function  rules()
    {
        return [
            [['name','email','password','password2'],'required','message'=>'Это поле должно быть заполнено'],
            ['name','string','min'=>3,'max'=>30],
            ['email','email','message'=>'Адрес электронной почты указан неверно'],
            ['password','string','min'=>4],
            ['password2','compare','compareAttribute'=>'password'],
            ['email','errorIfEmailUsed']
        ];
    }
        public function setUserRecord($userRecord)
        {
            $this->name = $userRecord->name;
            $this->email=$userRecord->email;
            $this->password = $this->password2;
        }

        public function errorIfEmailUsed()
        {
            if (UserRecord::existsEmail($this->email))
            $this->addError('email','Данная почта уже зарегистрирована');
        }
    public function attributeLabels()
    {
        return
            [
                'name'=>'ФИО',
                'email'=>'Email',
                'password'=>'Пароль',
                'password2'=>'Повторите пароль',
            ];
    }
}