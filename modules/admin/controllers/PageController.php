<?php
namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\Page;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Класс CategoryController реализует CRUD для страниц
 */
class PageController extends AdminController {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Список всех страниц сайта
     */
    public function actionIndex() {
        return $this->render(
            'index',
            ['pages' => Page::getTree()]
        );
    }

    /**
     * Просмотр данных существующей страницы
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Создание новой страницы сайта
     */
    public function actionCreate() {
        $model = new Page();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Обновление существующей страницы
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Удаление существующей страницы
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Поиск страницы по идентификатору
     */
    protected function findModel($id) {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
