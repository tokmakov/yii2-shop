<?php
/*
 * Страница редактирования товара, файл modules/admin/views/product/update.php
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Product */

$this->title = 'Редактирование товара: ' . $model->name;
?>

<h1><?= Html::encode($this->title) ?></h1>
<?=
$this->render(
    '_form',
    ['model' => $model]
);
?>

