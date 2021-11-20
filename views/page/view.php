<?php
/*
 * Произвольная страница сайта, файл views/page/view.php
 */

use app\components\TreeWidget;
use app\components\BrandsWidget;
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
                <h1><?= $page['name']; ?></h1>
                <?= $page['content']; ?>
            </div>
        </div>
    </div>
</section>

