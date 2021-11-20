<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\AppAsset;
use yii\bootstrap\Modal;

AppAsset::register($this);
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language; ?>">
<head>
    <meta charset="<?= Yii::$app->charset; ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $this->registerCsrfMetaTags(); ?>
    <title><?= Html::encode($this->title); ?></title>
    <?php $this->head(); ?>
</head>

<body>
<?php $this->beginBody(); ?>
<header>

    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <ul class="nav nav-pills">
                        <li><a href="#"><i class="fa fa-phone"></i> +2 95 01 88 821</a></li>
                        <li><a href="#"><i class="fa fa-envelope"></i> info@domain.com</a></li>
                    </ul>
                </div>
                <div class="col-sm-6">
                    <ul class="nav nav-pills pull-right">
                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                        <li><a href="#"><i class="fa fa-dribbble"></i></a></li>
                        <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="header-middle">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <div class="pull-left">
                        <a href="<?= Url::home(); ?>">
                            <?=
                            Html::img(
                                '@web/images/home/logo.png',
                                ['alt' => Yii::$app->params['shopName']]
                            );
                            ?>
                        </a>
                    </div>
                </div>
                <div class="col-sm-8">
                    <ul class="pull-right">
                        <li><i class="fa fa-user"></i> <a href="#">Аккаунт</a></li>
                        <li><i class="fa fa-star"></i> <a href="#">Избранное</a></li>
                        <li><i class="fa fa-crosshairs"></i> <a href="#">Оформить</a></li>
                        <li>
                            <i class="fa fa-shopping-cart"></i>
                            <a href="<?= Url::to(['basket/index']); ?>">Корзина</a>
                        </li>
                        <li><i class="fa fa-lock"></i> <a href="#">Войти</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="header-bottom">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <div id="menu">
                        <ul>
                            <li>
                                <a href="<?= Url::to(['catalog/index']); ?>">
                                    Каталог
                                </a>
                            </li>
                            <?php foreach ($this->context->pageMenu as $page): ?>
                                <li>
                                    <a href="<?= Url::to(['page/view', 'slug' => $page['slug']]); ?>">
                                        <?= $page['name']; ?>
                                    </a>
                                    <?php if (isset($page['childs'])): ?>
                                        <ul>
                                        <?php foreach ($page['childs'] as $child): ?>
                                            <li>
                                                <a href="<?= Url::to(['page/view', 'slug' => $child['slug']]); ?>">
                                                    <?= $child['name']; ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-4">
                    <form method="post" action="<?= Url::to(['catalog/search']); ?>" class="pull-right">
                        <?=
                        Html::hiddenInput(
                            Yii::$app->request->csrfParam,
                            Yii::$app->request->csrfToken
                        );
                        ?>
                        <div class="input-group">
                            <input type="text" name="query" class="form-control" placeholder="Поиск по каталогу">
                            <div class="input-group-btn">
                                <button class="btn btn-default" type="submit">
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</header>

<?= $content ?>

<footer>
    <div class="container">
        Copyright © 2018 E-SHOPPER Inc. All rights reserved.
    </div>
</footer>

<?php
$checkout = Url::to(['order/checkout']);
$footer =
<<<FOOTER
<button type="button" class="btn btn-default" data-dismiss="modal">
    Продолжить покупки
</button>
<a href="$checkout" class="btn btn-warning">
    Оформить заказ
</a>
FOOTER;
Modal::begin([
    'header' => '<h2>Корзина</h2>',
    'id' => 'basket-modal',
    'size'=>'modal-lg',
    'footer' => $footer
]);
Modal::end();
unset($checkout, $footer);
?>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
