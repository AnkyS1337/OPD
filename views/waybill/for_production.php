<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\States_for_waybill */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="states-for-waybill-form">
    <h1>Добавление товара для производства</h1>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'product_for_production')->dropDownList($products_push)->label('Товар,который пойдет на производство') ?>
    <?= $form->field($model, 'count_for_production_add')->label('Количество') ?>

    <div class="form-group">
        <span class="btn pull-left"><?= Html::a('Назад', ['waybill/view',
                'id' => $id_waybill],
                ['class'=>'btn btn-danger',]) ?></span>
        <span class="btn pull-right"><?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </span>
    </div>

    <?php ActiveForm::end(); ?>

</div>
