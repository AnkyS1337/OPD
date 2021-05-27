<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\States_for_waybill */

//$this->title = $id_waybill;
$this->params['breadcrumbs'][] = ['label' => 'States For Waybills', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="states-for-waybill-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_one', [
        'model' => $model,
        'id_waybill'=>$id_waybill,
        'id_entity'=>$id_entity,
        'drop_down_count'=>$drop_down_count
    ]) ?>

</div>
