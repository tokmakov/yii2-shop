<?php
/*
 * Страница добавления нового товара, файл modules/admin/views/product/create.php
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Product */

$this->title = 'Новый товар';
?>

<h1><?= Html::encode($this->title); ?></h1>
<?=
$this->render(
    '_form',
    ['model' => $model]
);
?>
