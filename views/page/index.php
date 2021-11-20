<?php
/*
 * Главная страница сайта, файл views/page/index.php
 */

use app\components\TreeWidget;
use app\components\BrandsWidget;
use yii\helpers\Url;
use yii\helpers\Html;
?>

<section>
    <div class="container">
        <!-- Слайдер рекламных акций -->
        <div id="slider" class="carousel slide" data-ride="carousel">

            <!-- Индикатор текущего элемента -->
            <ol class="carousel-indicators">
                <!-- Активный элемент -->
                <li data-target="#slider" data-slide-to="0" class="active"></li>
                <li data-target="#slider" data-slide-to="1"></li>
                <li data-target="#slider" data-slide-to="2"></li>
            </ol>

            <!-- Обертка для слайдов -->
            <div class="carousel-inner" role="listbox">
                <!-- Активный элемент -->
                <div class="item active">
                    <img src="/images/slider/1.jpg" alt="...">
                    <div class="carousel-caption">Первый элемент слайдера</div>
                </div>
                <div class="item">
                    <img src="/images/slider/2.jpg" alt="...">
                    <div class="carousel-caption">Второй элемент слайдера</div>
                </div>
                <div class="item">
                    <img src="/images/slider/3.jpg" alt="...">
                    <div class="carousel-caption">Третий элемент слайдера</div>
                </div>
            </div>

            <!-- Элементы управления -->
            <a class="left carousel-control" href="#slider"
               role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Предыдущий</span>
            </a>
            <a class="right carousel-control" href="#slider"
               role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Следующий</span>
            </a>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <h2>Каталог</h2>
                <div class="category-products">
                    <?= TreeWidget::widget(); ?>
                </div>

                <h2>Бренды</h2>
                <div class="brand-products">
                    <?= BrandsWidget::widget(); ?>
                </div>
            </div>

            <div class="col-sm-9">
                <?php if (!empty($hitProducts)): ?>
                    <h2>Лидеры продаж</h2>
                    <div class="row">
                        <?php foreach ($hitProducts as $item): ?>
                            <div class="col-sm-4">
                                <div class="product-wrapper text-center">
                                    <div class="product-image">
                                        <?php
                                        echo Html::img(
                                            '@web/images/products/medium/'.$item['image'],
                                            ['alt' => $item['name'], 'class' => 'img-responsive']
                                        );
                                        echo Html::tag(
                                            'span',
                                            'Лидер продаж',
                                            ['class' => 'hit']
                                        );
                                        ?>
                                    </div>
                                    <h2><?= $item['price']; ?> руб.</h2>
                                    <p>
                                        <a href="<?= Url::to(['catalog/product', 'id' => $item['id']]); ?>">
                                            <?= Html::encode($item['name']); ?>
                                        </a>
                                    </p>
                                    <form method="post"
                                          action="<?= Url::to(['basket/add']); ?>"
                                          class="add-to-basket">
                                        <input type="hidden" name="id"
                                               value="<?= $item['id']; ?>">
                                        <?=
                                        Html::hiddenInput(
                                            Yii::$app->request->csrfParam,
                                            Yii::$app->request->csrfToken
                                        );
                                        ?>
                                        <button type="submit"
                                                class="btn btn-warning">
                                            <i class="fa fa-shopping-cart"></i>
                                            Добавить в корзину
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($newProducts)): ?>
                    <h2>Новинки</h2>
                    <div class="row">
                        <?php foreach ($newProducts as $item): ?>
                            <div class="col-sm-4">
                                <div class="product-wrapper text-center">
                                    <div class="product-image">
                                        <?php
                                        echo Html::img(
                                            '@web/images/products/medium/'.$item['image'],
                                            ['alt' => $item['name'], 'class' => 'img-responsive']
                                        );
                                        echo Html::tag(
                                            'span',
                                            'Новинка',
                                            ['class' => 'new']
                                        );
                                        ?>
                                    </div>
                                    <h2><?= $item['price']; ?> руб.</h2>
                                    <p>
                                        <a href="<?= Url::to(['catalog/product', 'id' => $item['id']]); ?>">
                                            <?= Html::encode($item['name']); ?>
                                        </a>
                                    </p>
                                    <form method="post"
                                          action="<?= Url::to(['basket/add']); ?>"
                                          class="add-to-basket">
                                        <input type="hidden" name="id"
                                               value="<?= $item['id']; ?>">
                                        <?=
                                        Html::hiddenInput(
                                            Yii::$app->request->csrfParam,
                                            Yii::$app->request->csrfToken
                                        );
                                        ?>
                                        <button type="submit"
                                                class="btn btn-warning">
                                            <i class="fa fa-shopping-cart"></i>
                                            Добавить в корзину
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($saleProducts)): ?>
                    <h2>Распродажа</h2>
                    <div class="row">
                        <?php foreach ($saleProducts as $item): ?>
                            <div class="col-sm-4">
                                <div class="product-wrapper text-center">
                                    <div class="product-image">
                                        <?php
                                        echo Html::img(
                                            '@web/images/products/medium/'.$item['image'],
                                            ['alt' => $item['name'], 'class' => 'img-responsive']
                                        );
                                        echo Html::tag(
                                            'span',
                                            'Распродажа',
                                            ['class' => 'sale']
                                        );
                                        ?>
                                    </div>
                                    <h2><?= $item['price']; ?> руб.</h2>
                                    <p>
                                        <a href="<?= Url::to(['catalog/product', 'id' => $item['id']]); ?>">
                                            <?= Html::encode($item['name']); ?>
                                        </a>
                                    </p>
                                    <form method="post"
                                          action="<?= Url::to(['basket/add']); ?>"
                                          class="add-to-basket">
                                        <input type="hidden" name="id"
                                               value="<?= $item['id']; ?>">
                                        <?=
                                        Html::hiddenInput(
                                            Yii::$app->request->csrfParam,
                                            Yii::$app->request->csrfToken
                                        );
                                        ?>
                                        <button type="submit"
                                                class="btn btn-warning">
                                            <i class="fa fa-shopping-cart"></i>
                                            Добавить в корзину
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
