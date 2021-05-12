<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Products_for_entity */

$this->title = 'Добавление товара для юриридического лица';
$this->params['breadcrumbs'][] = ['label' => 'Products For Entities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-for-entity-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_one', [
        'model' => $model,
    ]) ?>

</div>
