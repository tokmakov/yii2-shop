<?php
namespace app\models;

use yii\base\Model;
use Yii;

class Basket extends Model {
    /**
     * Метод добавляет товар в корзину
     */
    public function addToBasket($id, $count = 1) {
        $count = (int)$count;
        if ($count < 1) {
            return;
        }
        $id = abs((int)$id);
        $product = Product::findOne($id);
        if (empty($product)) {
            return;
        }
        if ($count > 10) {
            $count = 10;
        }
        $session = Yii::$app->session;
        $session->open();
        if (!$session->has('basket')) {
            $session->set('basket', []);
            $basket = [];
        } else {
            $basket = $session->get('basket');
        }
        if (isset($basket['products'][$product->id])) { // такой товар уже есть?
            $count = $basket['products'][$product->id]['count'] + $count;
            if ($count > 100) {
                $count = 100;
            }
            $basket['products'][$product->id]['count'] = $count;
        } else { // такого товара еще нет
            $basket['products'][$product->id]['name'] = $product->name;
            $basket['products'][$product->id]['price'] = $product->price;
            $basket['products'][$product->id]['count'] = $count;
        }
        $amount = 0.0;
        foreach ($basket['products'] as $item) {
            $amount = $amount + $item['price'] * $item['count'];
        }
        $basket['amount'] = $amount;
        $session->set('basket', $basket);
    }

    /**
     * Метод удаляет товар из корзины
     */
    public function removeFromBasket($id) {
        $id = abs((int)$id);
        $session = Yii::$app->session;
        $session->open();
        if (!$session->has('basket')) {
            return;
        }
        $basket = $session->get('basket');
        if (!isset($basket['products'][$id])) {
            return;
        }
        unset($basket['products'][$id]);
        if (count($basket['products']) == 0) {
            $session->set('basket', []);
            return;
        }
        $amount = 0.0;
        foreach ($basket['products'] as $item) {
            $amount = $amount + $item['price'] * $item['count'];
        }
        $basket['amount'] = $amount;

        $session->set('basket', $basket);
    }

    /**
     * Метод возвращает содержимое корзины
     */
    public function getBasket() {
        $session = Yii::$app->session;
        $session->open();
        if (!$session->has('basket')) {
            $session->set('basket', []);
            return [];
        } else {
            return $session->get('basket');
        }
    }

    /**
     * Метод удаляет все товары из корзины
     */
    public function clearBasket() {
        $session = Yii::$app->session;
        $session->open();
        $session->set('basket', []);
    }

    /**
     * Метод обновляет содержимое корзины
     */
    public function updateBasket($data) {
        $this->clearBasket();
        foreach ($data['count'] as $id => $count) {
            $this->addToBasket($id, $count);
        }
    }
}