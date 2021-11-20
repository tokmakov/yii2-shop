<?php
namespace app\modules\admin\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Это модель для таблицы БД `order`
 *
 * @property int $id Идентификатор заказа
 * @property int $user_id Идентификатор пользователя
 * @property string $name Имя и фамилия покупателя
 * @property string $email Почта покупателя
 * @property string $phone Телефон покупателя
 * @property string $address Адрес доставки
 * @property string $comment Комментарий к заказу
 * @property string $amount Сумма заказа
 * @property int $status Статус заказа
 * @property string $created Дата и время создания
 * @property string $updated Дата и время обновления
 */
class Order extends ActiveRecord {
    /**
     * Возвращает имя таблицы базы данных
     */
    public static function tableName() {
        return 'order';
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    // при обновлении существующей записи  присвоить атрибуту
                    // updated значение метки времени UNIX
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated'],
                ],
                // если вместо метки времени UNIX используется DATETIME
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * Правила валидации полей формы при редактировании заказа
     */
    public function rules() {
        return [
            [['user_id', 'status'], 'integer'],
            [['amount'], 'number'],
            [['created', 'updated'], 'safe'],
            [['name', 'email', 'phone'], 'string', 'max' => 50],
            [['address', 'comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * Возвращает имена полей формы для редактирования заказа
     */
    public function attributeLabels() {
        return [
            'id' => 'Номер',
            'user_id' => 'User ID',
            'name' => 'Имя',
            'email' => 'Почта',
            'phone' => 'Телефон',
            'address' => 'Адрес доставки',
            'comment' => 'Комментарий',
            'amount' => 'Сумма',
            'status' => 'Статус',
            'created' => 'Дата создания',
            'updated' => 'Дата обновления',
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
     * Удаляет товары заказа при удалении заказа
     */
    public function afterDelete() {
        parent::afterDelete();
        OrderItem::deleteAll(['order_id' => $this->id]);
    }
}
