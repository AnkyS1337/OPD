<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\States_for_waybill */

//$this->title = $model->id;
//$this->params['breadcrumbs'][] = ['label' => 'States For Waybills', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="states-for-waybill-view">

    <h1><?= Html::encode($this->title) ?></h1>

<!--    <p>-->
<!--        --><?//= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
<!--        --><?//= Html::a('Delete', ['delete', 'id' => $model->id], [
//            'class' => 'btn btn-danger',
//            'data' => [
//                'confirm' => 'Are you sure you want to delete this item?',
//                'method' => 'post',
//            ],
//        ]) ?>
<!--    </p>-->
<h1>Hi</h1>
<!--    --><?//= DetailView::widget([
//        'model' => $model,
//        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' =>  '(Не задано)'],
//        'attributes' => [
////            'id_waybill',
//            ['attribute'=>'id_shop','value'=>function($model)
//            {
//                return $model->shop_name;
//            }],
//            ['attribute'=>'id_product','value'=>function($model)
//            {
//                return $model->product_name->name;
//            }],
//            'price_for_one',
//            'count',
//            'NPP',
//            'returns'
//        ],
//    ]) ?>
<!--    <span class="btn pull-left">--><?//= Html::a('Назад', ['waybill/view',
//            'id' => $model->id_waybill],
//            ['class'=>'btn btn-danger',]) ?><!--</span>-->
<!--    <span class="btn pull-right">  --><?//= Html::a('Изменить',
//            ['update_one', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
<!--    </span>-->

</div>
