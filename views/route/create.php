<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Route */

$this->title = 'Создание маршрута';
$this->params['breadcrumbs'][] = ['label' => 'Маршруты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="route-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
