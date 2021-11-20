<?php
/*
 * Страница добавления нового бренда, файл modules/admin/views/brand/create.php
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Brand */

$this->title = 'Новый бренд';
?>

<h1><?= Html::encode($this->title) ?></h1>
<?=
$this->render(
    '_form',
    ['model' => $model]
);
?>

