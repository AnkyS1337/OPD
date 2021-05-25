<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Products_for_entity */

$this->title = $model->product_name->name;
$this->params['breadcrumbs'][] = ['label' => 'Юридические лица', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="products-for-entity-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            ['attribute'=>'id_product','value'=>function($model)
            {
                return $model->product_name->name;
            }],
            'price',
        ],
    ]) ?>

    <span class="btn pull-left"><?= Html::a('Назад', ['entity/view',
            'id' => $model->id_entity],
            ['class'=>'btn btn-danger',]) ?></span>
    <span class="btn pull-right">  <?= Html::a('Изменить',
            ['update_one', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </span>
</div>
