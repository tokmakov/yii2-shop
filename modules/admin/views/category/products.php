<?php
/*
 * Страница списка товаров категории, файл modules/admin/views/category/products.php
 */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $category app\modules\admin\models\Category  */
/* @var $products yii\data\ActiveDataProvider */

$this->title = 'Товары категории: ' . $category->name;
?>

<h1><?= Html::encode($this->title) ?></h1>
<p>
    <?=
    Html::a(
        'Добавить товар',
        ['/admin/product/create', 'category' => $category->id],
        ['class' => 'btn btn-success']
    );
    ?>
</p>

<?=
GridView::widget([
    'dataProvider' => $products,
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
        [
            'class' => 'yii\grid\ActionColumn',
            'urlCreator' => function ($action, $model, $key, $index) {
                return Url::to(['/admin/product/'.$action, 'id' => $model->id]);
            }
        ],
    ],
]);
?>
