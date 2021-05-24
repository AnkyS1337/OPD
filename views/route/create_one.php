<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Order_of_Route */

$this->title = 'Добавление точки маршрута';
$this->params['breadcrumbs'][] = ['label' => 'Order Of Routes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-of--route-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_one', [
        'model' => $model,
        'id_route'=>$id_route
    ]) ?>

</div>
