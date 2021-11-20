<?php
/*
 * Страница просмотра товара, файл modules/admin/views/product/view.php
 */
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Product */

$this->title = 'Просмотр товара: ' . $model->name;
?>

<h1><?= Html::encode($this->title) ?></h1>
<p>
    <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Вы уверены, что хотите удалить товар?',
            'method' => 'post',
        ],
    ]) ?>
</p>

<?=
DetailView::widget([
    'model' => $model,
    'attributes' => [
        'name',
        [
            'attribute' => 'category_id',
            'value' => $model->getCategoryName()
        ],
        [
            'attribute' => 'brand_id',
            'value' => $model->getBrandName()
        ],
        'price',
        'content:html',
        'keywords',
        'description',
        'image',
        [
            'attribute' => 'hit',
            'value' =>  $model->hit ? 'Да' : 'Нет'
        ],
        [
            'attribute' => 'new',
            'value' =>  $model->new ? 'Да' : 'Нет'
        ],
        [
            'attribute' => 'sale',
            'value' =>  $model->sale ? 'Да' : 'Нет'
        ],
    ],
]);
?>

