<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Products_for_entity */

$this->params['breadcrumbs'][] = ['label' => 'Юридические лица', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->product_name->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменение товара';
?>
<div class="products-for-entity-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('update_form', [
        'model' => $model,
    ]) ?>

</div>
