<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Shop */

$this->title = 'Изменение данных о точке продаж:';

?>
<div class="shop-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>