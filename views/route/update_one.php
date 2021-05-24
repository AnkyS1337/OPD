<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Order_of_Route */

?>
<div class="order-of--route-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_one', [
        'model' => $model,
    ]) ?>

</div>
