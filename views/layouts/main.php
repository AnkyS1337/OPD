<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
        .my-navbar{
            background-color: skyblue;
        }
    </style>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'AKPK',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top ',
        ],
    ]);

    if (Yii::$app->user->isGuest)
        $menu =[
            ['label'=>'Регистрация','url'=>['/user/join']],
            ['label'=>'Войти','url'=>['/user/login']],
        ];
    else
        $menu =[
            //['label'=>Yii::$app->user->getIdentity()->name],
            ['label'=>'Печать погрузочных листов','url'=>['/waybill/view_routes']],
            ['label'=>'Погрузочные листы','url'=>['/waybill/index']],
//                   ['label'=>'Отчет','url'=>['/waybill/report']],
//                   ['label'=>'Прайсы','url'=>['/directory/index']],
//            [
//                'label' => 'Отчеты',
//                'items' => [
//                    ['label'=>'Свод по маршрутам','url'=>['/waybill/report_full']],
//                    ['label'=>'Юридические лица','url'=>['/report/contragents']],
//                ],
//            ],
            [
                'label' => 'Справочники',
                'items' => [
                    ['label'=>'Точки продаж','url'=>['/shop/index']],
                    ['label'=>'Юридические лица','url'=>['/entity/index']],
                    ['label'=>'Товары','url'=>['/product/index']],
                    ['label'=>'Маршруты','url'=>['/route/index']],
                    ['label'=>'Водители','url'=>['drivers/index']],
                ],
            ],
            ['label'=>'Выйти','url'=>['/user/logout']],
        ];

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menu,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'homeLink' => [
                    'label' => 'Главная',
                    'url' => Yii::$app->homeUrl
                ],
        ]
        ) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<!--<footer class="footer">-->
<!--    <div class="container">-->
<!--        <p class="pull-left">&copy; AKPK --><?//= '2021' ?><!--</p>-->
<!---->
<!--        <p class="pull-right">--><?//= Yii::powered() ?><!--</p>-->
<!--    </div>-->
<!--</footer>-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
