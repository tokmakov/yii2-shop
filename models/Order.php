<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class Order extends ActiveRecord {

    /**
     * Метод возвращает имя таблицы БД
     */
    public static function tableName() {
        return 'order';
    }

    /**
     * Метод расширяет возможности класса Order, внедряя дополительные
     * свойства и методы. Кроме того, позволяет реагировать на события,
     * создаваемые классом Order
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    // при вставке новой записи присвоить атрибутам created
                    // и updated значение метки времени UNIX
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created', 'updated'],
                    // при обновлении существующей записи присвоить атрибуту
                    // updated значение метки времени UNIX
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated'],
                ],
                // если вместо метки времени UNIX используется DATETIME
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * Позволяет получить все товары заказа
     */
    public function getItems() {
        // связь таблицы БД `order` с таблицей `order_item`
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    /**
     * Правила валидации атрибутов класса при сохранении
     */
    public function rules()
    {
        return [
            // эти четыре поля обязательны для заполнения
            [['name', 'email', 'phone', 'address'], 'required'],
            // поле email должно быть корректным адресом почты
            ['email', 'email'],
            // поле phone должно совпадать с шаблоном +7 (495) 123-45-67
            [
                'phone',
                'match',
                'pattern' => '~^\+7\s\([0-9]{3}\)\s[0-9]{3}-[0-9]{2}-[0-9]{2}$~',
                'message' => 'Номер телефона должен соответствовать шаблону +7 (495) 123-45-67'
            ],
            // эти три строки должны быть не более 50 символов
            [['name', 'email', 'phone'], 'string', 'max' => 50],
            // эти две строки должны быть не более 255 символов
            [['address', 'comment'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'Ваш e-mail',
            'phone' => 'Номер телефона',
            'address' => 'Адрес доставки',
            'comment' => 'Комментарий к заказу',
        ];
    }

    /**
     * Добавляет записи в таблицу БД `order_item`
     */
    public function addItems($basket) {
        // получаем товары в корзине
        $products = $basket['products'];
        // добавляем товары по одному
        foreach ($products as $product_id => $product) {
            $item = new OrderItem();
            $item->order_id = $this->id;
            $item->product_id = $product_id;
            $item->name = $product['name'];
            $item->price = $product['price'];
            $item->quantity = $product['count'];
            $item->cost = $product['price'] * $product['count'];
            $item->insert();
        }
    }
}
