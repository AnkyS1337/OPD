<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Drivers */

$this->title = 'Изменение ФИО Водителя';
$this->params['breadcrumbs'][] = ['label' => 'Водители', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменение ФИО водителя';
?>
<div class="drivers-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
