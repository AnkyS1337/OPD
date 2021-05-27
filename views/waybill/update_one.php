<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\States_for_waybill */

//$this->title = 'Update States For Waybill: ' . $model->id;
//$this->params['breadcrumbs'][] = ['label' => 'States For Waybills', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Update';
//?>
<div class="states-for-waybill-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_one', [
        'model' => $model,
    ]) ?>

</div>
