<?php
namespace app\controllers;

use app\models\SignupForm;
use app\models\User;
use yii\widgets\ActiveForm;
use yii\web\Response;
use Yii;

class UserController extends AppController {
    public function actionSignup() {
        $this->setMetaTags('Регистрация');
        $model = new SignupForm();
        /*
         * Если пришли post-данные, загружаем их в модель...
         */
        if ($model->load(Yii::$app->request->post())) {
            // ...и проверяем эти данные
            if ( ! $model->validate()) {
                // данные не прошли валидацию, отмечаем этот факт
                Yii::$app->session->setFlash(
                    'signup-success',
                    false
                );
                // сохраняем в сессии введенные пользователем данные
                Yii::$app->session->setFlash(
                    'signup-data',
                    [
                        'email' => $model->email,
                        'password' => $model->password,
                    ]
                );
                /*
                 * Сохраняем в сессии массив сообщений об ошибках. Массив имеет вид
                 * [
                 *     'email' => [
                 *         'Поле «Ваш email» обязательно для заполнения',
                 *         'Поле «Ваш email» должно быть адресом почты'
                 *     ]
                 *     'password' => [
                 *         'Поле «Пароль» обязательно для заполнения',
                 *     ],
                 * ]
                 */
                Yii::$app->session->setFlash(
                    'signup-errors',
                    $model->getErrors()
                );
            } else {
                // добавляем нового пользователя в базу данных
                $user = new User();
                $user->email = $model->email;
                $user->password = Yii::$app->security->generatePasswordHash($model->password);
                $user->insert();
                // данные прошли валидацию, пользователь зарегистрирован
                Yii::$app->session->setFlash(
                    'signup-success',
                    true
                );
            }
            // выполняем редирект, чтобы избежать повторной отправки формы
            return $this->refresh();
        }
        return $this->render('signup', ['model' => $model]);
    }

    public function actionValidate() {
        $model = new SignupForm();
        $request = Yii::$app->getRequest();
        if ($request->isAjax && $model->load($request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }
}