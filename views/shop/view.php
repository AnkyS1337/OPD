<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Shop */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Точки продаж', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;

\yii\web\YiiAsset::register($this);
?>
<div class="shop-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            ['attribute'=>'id_entity','value'=>function($model){return $model->getEntity_name($model->id_entity);}],
            'name',
            'address',
            // 'coord',
            ['attribute'=>'payment_method','value'=>function($model){return $model->getName_of_payment_method($model->payment_method);}],
        ],
    ]) ?>
    <span class="btn pull-left"> <?= Html::a( 'На главную', ('/shop/index'),
            ['class'=>'btn btn-danger',]) ?>
    </span>
    <span class="btn pull-right">  <?= Html::a('Изменить',
            ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </span>
</div>
