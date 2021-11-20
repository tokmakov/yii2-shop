<?php
namespace app\controllers;

use Yii;
use app\models\Basket;
use app\models\Order;

class OrderController extends AppController {
    public $defaultAction = 'checkout';

    public function actionCheckout() {
        // если в корзине нет товаров, здесь делать нечего
        if (empty((new Basket())->getBasket())) {
            return $this->redirect(['basket/index']);
        }
        $this->setMetaTags('Оформление заказа');
        $order = new Order();
        /*
         * Если пришли post-данные, загружаем их в модель...
         */
        if ($order->load(Yii::$app->request->post())) {
            // ...и проверяем эти данные
            if ( ! $order->validate()) {
                // данные не прошли валидацию, отмечаем этот факт
                Yii::$app->session->setFlash(
                    'checkout-success',
                    false
                );
                // сохраняем в сессии введенные пользователем данные
                Yii::$app->session->setFlash(
                    'checkout-data',
                    [
                        'name' => $order->name,
                        'email' => $order->email,
                        'phone' => $order->phone,
                        'address' => $order->address,
                        'comment' => $order->comment
                    ]
                );
                /*
                 * Сохраняем в сессии массив сообщений об ошибках. Массив имеет вид
                 * [
                 *     'name' => [
                 *         'Поле «Ваше имя» обязательно для заполнения',
                 *     ],
                 *     'email' => [
                 *         'Поле «Ваш email» обязательно для заполнения',
                 *         'Поле «Ваш email» должно быть адресом почты'
                 *     ]
                 * ]
                 */
                Yii::$app->session->setFlash(
                    'checkout-errors',
                    $order->getErrors()
                );
            } else {
                /*
                 * Заполняем остальные поля модели — те которые приходят
                 * не из формы, а которые надо получить из корзины. Кроме
                 * того, поля created и updated будут заполнены с помощью
                 * метода Order::behaviors().
                 */
                $basket = new Basket();
                $content = $basket->getBasket();
                $order->amount = $content['amount'];
                // сохраняем заказ в базу данных
                $order->insert();
                $order->addItems($content);
                // отправляем письмо покупателю
                $mail = Yii::$app->mailer->compose(
                    'order',
                    ['order' => $order]
                );
                $mail->setFrom(Yii::$app->params['senderEmail'])
                    ->setTo($order->email)
                    ->setSubject('Заказ в магазине № ' . $order->id)
                    ->send();
                // очищаем содержимое корзины
                $basket->clearBasket();
                // данные прошли валидацию, заказ успешно сохранен
                Yii::$app->session->setFlash(
                    'checkout-success',
                    true
                );
            }
            // выполняем редирект, чтобы избежать повторной отправки формы
            return $this->refresh();
        }
        return $this->render('checkout', ['order' => $order]);
    }
}