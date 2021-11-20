<?php
namespace app\modules\admin\controllers;

use yii\data\ActiveDataProvider;
use app\modules\admin\models\Order;

class DefaultController extends AdminController {

    public function actionIndex() {
        $queueOrders = new ActiveDataProvider([
            'query' => Order::find()
                ->where(['status' => 0])
                ->orderBy(['created' => SORT_ASC]),
            'sort' => false,
            'pagination' => [
                // три заказа на страницу
                'pageSize' => 3,
                // уникальный параметр пагинации
                'pageParam' => 'queue',

            ]
        ]);
        $processOrders = new ActiveDataProvider([
            'query' => Order::find()
                ->where(['IN', 'status', [1,2,3]])
                ->orderBy(['updated' => SORT_ASC]),
            'sort' => false,
            'pagination' => [
                // три заказа на страницу
                'pageSize' => 3,
                // уникальный параметр пагинации
                'pageParam' => 'process',

            ]
        ]);
        return $this->render('index', [
            'queueOrders' => $queueOrders,
            'processOrders' => $processOrders,
        ]);
    }
}
