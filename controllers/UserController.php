<?php
namespace app\controllers;


use app\models\UserJoinForm;
use app\models\UserLoginForm;
use Yii;
use app\models\UserIdentity;
use app\models\UserRecord;
use yii\web\Controller;

class UserController extends Controller{


    public function actionJoin()
    {
        if(Yii::$app->request->isPost)
            return $this->actionJoinPost();
             $userJoinForm = new UserJoinForm();
            $userRecord = new UserRecord();
            $userJoinForm->setUserRecord($userRecord);

        return $this->render('join',['userJoinForm'=>$userJoinForm]);
    }

    public function actionJoinPost()
    {
        $userJoinForm = new UserJoinForm();
        if ($userJoinForm->load(Yii::$app->request->post()))
            if($userJoinForm->validate())                          //Нужно для пользовательской проверки
            {
                $userRecord = new UserRecord();
                $userRecord->setUserJoinForm($userJoinForm);
                $userRecord->save();
                return $this->redirect('/user/login');
            }
        return $this->render('join',['userJoinForm'=>$userJoinForm]);
    }



    public function actionLogin()
    {
        if(Yii::$app->request->isPost)
            return $this->actionLoginPost();
        $userLoginForm = new UserLoginForm();
        return $this->render('login',['userLoginForm'=>$userLoginForm]);
    }

    public function actionLoginPost()
    {
        $userLoginForm = new UserLoginForm();
        if ($userLoginForm->load(Yii::$app->request->post()))
            if ($userLoginForm->validate())
            {
                $userLoginForm->login();
                return $this->redirect('/');
            }
        return $this->render('login',['userLoginForm'=>$userLoginForm]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect('/');
    }
}