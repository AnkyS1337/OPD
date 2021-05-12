<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Products_for_entity */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-for-entity-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_product')->dropDownList(\app\models\Products_for_entity::getList_shop_for_entity($model->id_entity)) ?>

    <?= $form->field($model, 'price_dot')->textInput()->label('Цена') ?>
    <div class="form-group">
        <span class="btn pull-left"><?= Html::a('Назад', ['entity/view',
                'id' => $model->id_entity],
                ['class'=>'btn btn-danger',]) ?></span>
        <span class="btn pull-right"><?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </span>
    </div>

    <?php ActiveForm::end(); ?>

</div>
