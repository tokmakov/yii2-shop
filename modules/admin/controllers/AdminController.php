<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;

class AdminController extends Controller {
    public function beforeAction($action) {
        $session = Yii::$app->session;
        $session->open();
        if (!$session->has('auth_site_admin')) {
            $this->redirect('/admin/auth/login');
            return false;
        }
        return parent::beforeAction($action);
    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
}