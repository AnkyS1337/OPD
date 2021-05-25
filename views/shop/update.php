<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Shop */

$this->title = 'Изменение данных о точке продаж: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Точки продаж', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label'=> $model->name, 'url'=>['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="shop-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
