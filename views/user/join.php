<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="row" style="
margin-top: 8%;
     ">
    <div class="col-lg-4"></div>
        <div class="panel panel-success col-lg-4" style="padding-right: 0; padding-left: 0">
            <div class="panel-heading">
                <h1>Регистрация</h1>
            </div>
            <div class="panel-body">

                <?php $form = ActiveForm::begin(['id'=>'user-join-form']);?>
                <?= $form->field($userJoinForm,'name') ?>
                <?= $form->field($userJoinForm,'email') ?>
                <?= $form->field($userJoinForm,'password')->passwordInput() ?>
                <?= $form->field($userJoinForm,'password2')->passwordInput() ?>

                <?= Html::submitButton('Зарегистрироваться',['class'=>'btn btn-success']) ?>
               <? ActiveForm::end(); ?>

            </div>
        </div>
    <div class="col-lg-4"></div>
</div>

