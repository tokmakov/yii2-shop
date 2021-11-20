<?php
/*
 * Страница списка всех брендов, файл views/catalog/brands.php
 */

use app\components\TreeWidget;
use app\components\BrandsWidget;
use yii\helpers\Html;
use yii\helpers\Url;
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

            <div class="col-sm-9 padding-right">
                <h1>Все бренды</h1>
                <?php if (!empty($brands)): ?>
                    <div class="row">
                        <?php foreach ($brands as $brand): ?>
                            <div class="col-sm-6 col-md-4">
                                <div class="thumbnail">
                                    <?php
                                    $image = $brand['image']
                                        ? '@web/images/brands/thumb/'.$brand['image']
                                        : '@web/images/brands/no-image.png';
                                    ?>
                                    <?=
                                        Html::img( $image, ['alt' => $brand['name'], 'class' => 'img-response']);
                                    ?>
                                    <div class="caption">
                                        <h2>
                                            <a href="<?= Url::to(['catalog/brand', 'id' => $brand['id']]); ?>">
                                                <?= Html::encode($brand['name']); ?>
                                            </a>
                                        </h2>
                                        <p><?= Html::encode($brand['content']); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

