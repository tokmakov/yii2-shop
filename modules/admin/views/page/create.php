<?php
/*
 * Страница добавления новой страницы, файл modules/admin/views/page/create.php
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Page */

$this->title = 'Добавить страницу';
?>

<h1><?= Html::encode($this->title) ?></h1>
<?=
$this->render(
    '_form',
    ['model' => $model]
);
?>

