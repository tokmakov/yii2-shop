<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class OrderItem extends ActiveRecord {

    /**
     * Возвращает имя таблицы БД
     */
    public static function tableName() {
        return 'order_item';
    }

    /**
     * Позволяет получить заказ, в который входит этот элемент
     */
    public function getOrder() {
        // связь таблицы БД `order_item` с таблицей `order`
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }
}
