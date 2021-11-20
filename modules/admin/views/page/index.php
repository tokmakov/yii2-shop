<?php
/*
 * Страница списка всех страниц, файл modules/admin/views/page/index.php
 */
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Все страницы';
?>

<h1><?= Html::encode($this->title); ?></h1>
<p>
    <?= Html::a('Добавить страницу', ['create'], ['class' => 'btn btn-success']); ?>
</p>

<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>Наименование</th>
        <th>Мета-тег keywords</th>
        <th>Мета-тег description</th>
        <th><span class="glyphicon glyphicon-eye-open"></span></th>
        <th><span class="glyphicon glyphicon-pencil"></span></th>
        <th><span class="glyphicon glyphicon-trash"></span></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($pages as $page): ?>
        <tr>
            <td><?= $page['name']; ?></td>
            <td><?= $page['keywords']; ?></td>
            <td><?= $page['description']; ?></td>
            <td>
                <?=
                Html::a(
                    '<span class="glyphicon glyphicon-eye-open"></span>',
                    ['/admin/page/view', 'id' => $page['id']]
                );
                ?>
            </td>
            <td>
                <?=
                Html::a(
                    '<span class="glyphicon glyphicon-pencil"></span>',
                    ['/admin/page/update', 'id' => $page['id']]
                );
                ?>
            </td>
            <td>
                <?=
                Html::a(
                    '<span class="glyphicon glyphicon-trash"></span>',
                    ['/admin/page/delete', 'id' => $page['id']],
                    [
                        'data-confirm'=> 'Вы уверены, что хотите удалить эту страницу?',
                        'data-method'=> 'post'
                    ]
                );
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
