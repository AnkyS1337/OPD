<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\States_for_waybill */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="states-for-waybill-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'products')->checkboxlist(\app\models\States_for_waybill::getProducts_for_adding_shop($id_entity),['multiple' => true])->label('Выберите продукцию') ?>
    <?= $form->field($model, 'NPP')->dropDownList($drop_down_count)->label('Очередность в маршруте') ?>

    <div class="form-group">
        <span class="btn pull-left"><?= Html::a('Назад', ['waybill/view_add',
                'id_waybill' => $id_waybill],
                ['class'=>'btn btn-danger',]) ?></span>
        <span class="btn pull-right"><?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </span>
    </div>

    <?php ActiveForm::end(); ?>

</div>
