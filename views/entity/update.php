<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Route */

$this->title = 'Изменение юридического лица: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Юридические лица', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="route-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
