<?php
/*
 * Страница списка всех товаров, файл modules/admin/views/product/index.php
 */
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары каталога';
?>

<h1><?= Html::encode($this->title) ?></h1>
<p>
    <?= Html::a('Добавить товар', ['create'], ['class' => 'btn btn-success']) ?>
</p>

<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'name',
        [
            'attribute' => 'category_id',
            'value' => function($data){
                return $data->getCategoryName();
            }
        ],
        [
            'attribute' => 'brand_id',
            'value' => function($data){
                return $data->getBrandName();
            }
        ],
        'price',
        [
            'attribute' => 'hit',
            'value' => function($data) {
                return $data->hit ? 'Да' : 'Нет';
            }
        ],
        [
            'attribute' => 'new',
            'value' => function($data) {
                return $data->new ? 'Да' : 'Нет';
            }
        ],
        [
            'attribute' => 'sale',
            'value' => function($data) {
                return $data->sale ? 'Да' : 'Нет';
            }
        ],
        ['class' => 'yii\grid\ActionColumn'],
    ],
]);
?>

