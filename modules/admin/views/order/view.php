<?php
/*
 * Файл view-шаблона modules/admin/views/order/view.php
 */
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Order */

$this->title = 'Просмотр заказа № ' . $model->id;
?>

<h1><?= Html::encode($this->title); ?></h1>

<p>
    <?=
    Html::a(
        'Изменить',
        ['update', 'id' => $model->id],
        ['class' => 'btn btn-primary']
    );
    ?>
    <?=
    Html::a(
        'Удалить',
        ['delete', 'id' => $model->id],
        [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить заказ?',
                'method' => 'post',
            ],
        ]
    );
    ?>
</p>

<?=
DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'attribute' => 'status',
            'value' => function ($data) {
                switch ($data->status) {
                    case 0: return '<span class="text-danger">Новый</span>';
                    case 1: return '<span class="text-warning">Обработан</span>';
                    case 2: return '<span class="text-warning">Оплачен</span>';
                    case 3: return '<span class="text-warning">Доставлен</span>';
                    case 4: return '<span class="text-success">Завершен</span>';
                    default: return 'Ошибка';
                }
            },
            'format' => 'html'
        ],
        'name',
        'email:email',
        'phone',
        'address',
        'comment',
        'created',
        'updated'
    ],
]);
?>

<?php
$products = $model->items;
?>
<table class="table table-bordered table-striped">
    <tr>
        <th>Наименование</th>
        <th>Количество</th>
        <th>Цена</th>
        <th>Сумма</th>
    </tr>
    <?php foreach ($products as $product): ?>
       <tr>
           <td><?= $product->name; ?></td>
           <td class="text-right"><?= $product->quantity; ?></td>
           <td class="text-right"><?= $product->price; ?></td>
           <td class="text-right"><?= $product->cost; ?></td>
       </tr>
    <?php endforeach; ?>
    <tr>
        <th colspan="3" class="text-right">Итого</th>
        <th class="text-right"><?= $model->amount; ?></th>
    </tr>
</table>
