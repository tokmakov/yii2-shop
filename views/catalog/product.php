<?php
/*
 * Страница товара, файл views/catalog/product.php
 */

use app\components\TreeWidget;
use app\components\BrandsWidget;
use app\components\ChainWidget;
use yii\helpers\Url;
use yii\helpers\Html;
?>

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
                <?= ChainWidget::widget(['itemCurrent' => $product['category_id']]); ?>
                <h1><?= Html::encode($product['name']); ?></h1>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="product-picture">
                            <?php
                            echo Html::img(
                                '@web/images/products/large/'.$product['image'],
                                ['alt' => $product['name'], 'class' => 'img-responsive']
                            );
                            if ($product['new']) { // новинка?
                                echo Html::tag(
                                    'span',
                                    'Новинка',
                                    ['class' => 'new']
                                );
                            }
                            if ($product['hit']) { // лидер продаж?
                                echo Html::tag(
                                    'span',
                                    'Лидер продаж',
                                    ['class' => 'hit']
                                );
                            }
                            if ($product['sale']) { // распродажа?
                                echo Html::tag(
                                    'span',
                                    'Распродажа',
                                    ['class' => 'sale']
                                );
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="product-info">
                            <p class="product-price">
                                Цена: <span><?= $product['price']; ?></span> руб.
                            </p>
                            <form method="post"
                                  action="<?= Url::to(['basket/add']); ?>"
                                  class="add-to-basket">
                                <label>Количество</label>
                                <input name="count" type="text" value="1" />
                                <input type="hidden" name="id"
                                       value="<?= $product['id']; ?>">
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
                            <p>Артикул: 1234567</p>
                            <p>Наличие: На складе</p>
                            <p>
                                Бренд:
                                <a href="<?= Url::to(['catalog/brand', 'id' => $brand['id']]); ?>">
                                    <?= Html::encode($brand['name']); ?>
                                </a>
                            </p>

                        </div>
                    </div>
                </div>
                <div class="product-descr">
                    <?= $product['content']; ?>
                </div>
                <?php if (!empty($similar)): /* похожие товары */ ?>
                    <h2>Похожие товары</h2>
                    <div class="row">
                        <?php foreach ($similar as $item): ?>
                            <div class="col-sm-4">
                                <div class="product-wrapper text-center">
                                    <div class="product-image">
                                        <?php
                                        echo Html::img(
                                            '@web/images/products/medium/'.$item['image'],
                                            ['alt' => $item['name'], 'class' => 'img-responsive']
                                        );
                                        if ($item['new']) { // новинка?
                                            echo Html::tag(
                                                'span',
                                                'Новинка',
                                                ['class' => 'new']
                                            );
                                        }
                                        if ($item['hit']) { // лидер продаж?
                                            echo Html::tag(
                                                'span',
                                                'Лидер продаж',
                                                ['class' => 'hit']
                                            );
                                        }
                                        if ($item['sale']) { // распродажа?
                                            echo Html::tag(
                                                'span',
                                                'Распродажа',
                                                ['class' => 'sale']
                                            );
                                        }
                                        ?>
                                    </div>
                                    <h2><?= $item['price']; ?> руб.</h2>
                                    <p>
                                        <a href="<?= Url::to(['catalog/product', 'id' => $item['id']]); ?>">
                                            <?= Html::encode($item['name']); ?>
                                        </a>
                                    </p>
                                    <a href="#" class="btn btn-warning">
                                        <i class="fa fa-shopping-cart"></i>
                                        Добавить в корзину
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

