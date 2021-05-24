<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Order_of_Route */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-of--route-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_shop')->dropDownList(\app\models\Order_of_Route::getShops_for_route($model->id_route)) ?>

<!--    --><?//= $form->field($model, 'NPP')->textInput() ?>


    <div class="form-group">
        <span class="btn pull-left"><?= Html::a('Назад', ['route/view',
                'id' => $model->id_route],
                ['class'=>'btn btn-danger',]) ?></span>
        <span class="btn pull-right"><?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </span>
    </div>

    <?php ActiveForm::end(); ?>

</div>
