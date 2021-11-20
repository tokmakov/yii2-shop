<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\modules\admin\models\LoginForm;

class AuthController extends Controller {

    public function actionLogin() {
        $model = new LoginForm();
        /*
         * Если пришли post-данные, загружаем их в модель...
         */
        if ($model->load(Yii::$app->request->post())) {
            // ...и проверяем эти данные
            if ($model->validate()) {
                // данные корректные, пробуем авторизовать
                if (Yii::$app->params['adminEmail'] == $model->email
                    && Yii::$app->params['adminPassword'] == $model->password) {
                    LoginForm::login();
                    return $this->redirect('/admin/default/index');
                } else {
                    return $this->refresh();
                }
            }
        }
        return $this->render('login', ['model' => $model]);;
    }

    public function actionLogout() {
        LoginForm::logout();
        return $this->redirect('/admin/auth/login');
    }
}