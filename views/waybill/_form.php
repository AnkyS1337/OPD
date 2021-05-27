<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Waybill */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="waybill-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_driver')->dropDownList(\app\models\Drivers::getList_drivers()) ?>

    <?= $form->field($model, 'id_route')->dropDownList(\app\models\Route::getList_routes()) ?>

    <?= $form->field($model, 'date')->widget(DatePicker::className(), ['language' => 'ru','pluginOptions' =>
        [
            'format' => 'yyyy-mm-dd',
            'autoclose'=>true,
        ]]); ?>

    <div class="form-group">
        <span class="btn pull-left"><?= Html::a( 'Назад', ('/waybill/index'),
                ['class'=>'btn btn-danger']) ?></span>
        <span class="btn pull-right"><?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </span>
    </div>

    <?php ActiveForm::end(); ?>

</div>
