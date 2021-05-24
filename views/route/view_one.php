<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Order_of_Route */


?>
<div class="order-of--route-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_shop',
            'NPP',
        ],
    ]) ?>
    <span class="btn pull-left"><?= Html::a('Назад', ['route/view',
            'id' => $model->id_route],
            ['class'=>'btn btn-danger',]) ?></span>
     <span class="btn pull-right">  <?= Html::a('Изменить',
            ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </span>
</div>
