<?php
/*
 * Страница раздела каталога, файл views/catalog/category.php
 */

use app\components\TreeWidget;
use app\components\BrandsWidget;
use app\components\ChainWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <div class="left-sidebar">
                    <h2>Каталог</h2>
                    <div class="category-products">
                        <?= TreeWidget::widget(); ?>
                    </div>

                    <h2>Бренды</h2>
                    <div class="brand-products">
                        <?= BrandsWidget::widget(); ?>
                    </div>
                </div>
            </div>

            <div class="col-sm-9">
                <?= ChainWidget::widget(['itemCurrent' => $category['id'], 'showCurrent' => false]); ?>
                <h2><?= Html::encode($category['name']); ?></h2>
                <?php if (!empty($products)): ?>
                    <div class="row">
                        <?php foreach ($products as $product): ?>
                            <div class="col-sm-4">
                                <div class="product-wrapper text-center">
                                    <div class="product-image">
                                        <?php
                                        echo Html::img(
                                            '@web/images/products/medium/'.$product['image'],
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
                                    <h2><?= $product['price']; ?> руб.</h2>
                                    <p>
                                        <a href="<?= Url::to(['catalog/product', 'id' => $product['id']]); ?>">
                                            <?= Html::encode($product['name']); ?>
                                        </a>
                                    </p>
                                    <form method="post"
                                          action="<?= Url::to(['basket/add']); ?>"
                                          class="add-to-basket">
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
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?= LinkPager::widget(['pagination' => $pages]); /* постраничная навигация */ ?>
                <?php else: ?>
                    <p>Нет товаров в этой категории.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>