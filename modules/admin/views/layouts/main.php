<?php
/*
 * Layout-шаблон, файл modules/views/layouts/main.php
 */
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
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
    <title><?= Html::encode($this->title) ?> | Панель управления</title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<header>
        <?php
        NavBar::begin([
            'brandLabel' => 'Панель управления',
            'brandUrl' => Url::to(['/admin/default/index']),
            'options' => [
                'class' => 'navbar-inverse',
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'items' => [
                [
                    'label' => 'Каталог',
                    'items' => [
                        ['label' => 'Категории', 'url' => ['/admin/category/index']],
                        ['label' => 'Товары', 'url' => ['/admin/product/index']],
                        ['label' => 'Бренды', 'url' => ['/admin/brand/index']],
                    ],
                ],
                ['label' => 'Заказы', 'url' => ['/admin/order/index']],
                ['label' => 'Пользователи', 'url' => ['/admin/user/index']],
                ['label' => 'Страницы', 'url' => ['/admin/page/index']],
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                ['label' => 'Выйти', 'url' => ['/admin/auth/logout']],
            ],
        ]);
        NavBar::end();
        ?>
</header>

<div class="container">
    <?php if (Yii::$app->session->hasFlash('warning')): ?>
        <div class="alert alert-warning alert-dismissible" role="alert">
            <button type="button" class="close"
                    data-dismiss="alert" aria-label="Закрыть">
                <span aria-hidden="true">&times;</span>
            </button>
            <p><?= Yii::$app->session->getFlash('warning'); ?></p>
        </div>
    <?php endif; ?>
    <?= $content; ?>
</div>

<footer class="footer">
    <div class="container">
        <p>&copy; <?= Yii::$app->params['shopName'] ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>