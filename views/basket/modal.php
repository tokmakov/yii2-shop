<?php
/*
 * Корзина покупателя в модальном окне, файл views/basket/modal.php
 */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php if (!empty($basket)): ?>
    <p class="text-right clear">
        <a href="<?= Url::to(['basket/clear']); ?>" class="text-danger">
            Очистить корзину
        </a>
    </p>
    <div class="table-responsive">
        <form action="<?= Url::to(['basket/update']); ?>" method="post">
            <?=
            Html::hiddenInput(
                Yii::$app->request->csrfParam,
                Yii::$app->request->csrfToken
            );
            ?>
            <table class="table table-bordered">
                <tr>
                    <th>Наименование</th>
                    <th>Кол-во, шт.</th>
                    <th>Цена, руб.</th>
                    <th>Сумма, руб.</th>
                    <th></th>
                </tr>
                <?php foreach ($basket['products'] as $id => $item): ?>
                    <tr>
                        <td>
                            <a href="<?= Url::to(['catalog/product', 'id' => $id]); ?>">
                                <?= Html::encode($item['name']); ?>
                            </a>
                        </td>
                        <td class="text-right">
                            <?=
                            Html::input(
                                'text',
                                'count['.$id.']',
                                $item['count'],
                                ['style' => 'width: 100%; text-align: right;']
                            );
                            ?>
                        </td>
                        <td class="text-right"><?= $item['price']; ?></td>
                        <td class="text-right"><?= $item['price'] * $item['count']; ?></td>
                        <td>
                            <a href="<?= Url::to(['basket/remove', 'id' => $id]); ?>"
                               class="text-danger">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td>
                        <button type="submit"
                                class="btn btn-primary">
                            <i class="fa fa-refresh" aria-hidden="true"></i>
                            Пересчитать
                        </button>
                    </td>
                    <td colspan="2" class="text-right">Итого</td>
                    <td class="text-right"><?= $basket['amount']; ?></td>
                    <td></td>
                </tr>
            </table>
        </form>
    </div>
<?php else: ?>
    <p>Ваша корзина пуста</p>
<?php endif; ?>
