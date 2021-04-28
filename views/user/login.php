<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm; ?>

<div class="panel panel-info">
    <div class="panel-heading">
        <h1>Войти</h1>
    </div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(['id'=>'user-login-form']);?>


        <?= $form->field($userLoginForm,'email') ?>
        <?= $form->field($userLoginForm,'password')->passwordInput() ?>
        <?= $form->field($userLoginForm,'remember')->checkbox() ?>
        <?= Html::submitButton('Войти',['class'=>'btn btn-primary']) ?>
        <? ActiveForm::end(); ?>
    </div>
</div>
