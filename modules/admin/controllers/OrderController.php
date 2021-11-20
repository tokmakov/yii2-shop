<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\Order;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Класс OrderController реализует CRUD для модели Order.
 */
class OrderController extends AdminController {

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
     * Список всех заказов с постраничной навигацией
     * и сортировкой по статусу
     */
    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => Order::find(),
            'pagination' => [
                'pageSize' => 5 // пять заказов на страницу
            ],
            'sort' => [
                'defaultOrder' => [
                    // сортировка по статусу, по возрастанию
                    'status' => SORT_ASC
                ]
            ]
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Просмотр данных существующего заказа
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Создание нового заказа, метод не используется
     */
    public function actionCreate() {
        $model = new Order();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Обновление существующего заказа
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
     * Удаление существующего заказа
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Поиск заказа по идентификатору
     */
    protected function findModel($id) {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Заказ не найден');
    }
}
