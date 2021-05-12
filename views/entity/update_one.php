<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Products_for_entity */

$this->params['breadcrumbs'][] = ['label' => 'Products For Entities', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="products-for-entity-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('update_form', [
        'model' => $model,
    ]) ?>

</div>
