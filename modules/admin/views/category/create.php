<?php
/*
 * Страница добавления новой категории, файл modules/admin/views/category/create.php
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Category */

$this->title = 'Новая категория';
?>

<h1><?= Html::encode($this->title) ?></h1>
<?=
$this->render(
    '_form',
    ['model' => $model]
);
?>

