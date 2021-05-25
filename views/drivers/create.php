<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Drivers */

$this->title = 'Добавление водителя';
$this->params['breadcrumbs'][] = ['label' => 'Водители', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="drivers-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
