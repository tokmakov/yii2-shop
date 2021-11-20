<?php
namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\Brand;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * Класс OrderController реализует CRUD для брендов
 */
class BrandController extends AdminController {

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
     * Список всех брендов с постраничной навигацией
     */
    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => Brand::find(),
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Просмотр данных существующего бренда
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Создание нового бренда
     */
    public function actionCreate() {
        $model = new Brand();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // print_r($model); die();
            // загружаем изображение и выполняем resize исходного изображения
            $model->upload = UploadedFile::getInstance($model, 'image');
            if ($name = $model->uploadImage()) { // если изображение было загружено
                // сохраняем в БД имя файла изображения
                $model->image = $name;
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Обновление существующего бренда
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        // старое изображение, которое надо удалить, если загружено новое
        $old = $model->image;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // загружаем изображение и выполняем resize исходного изображения
            $model->upload = UploadedFile::getInstance($model, 'image');
            if ($new = $model->uploadImage()) { // если изображение было загружено
                // удаляем старое изображение
                if (!empty($old)) {
                    $model::removeImage($old);
                }
                // сохраняем в БД новое имя
                $model->image = $new;
            } else { // оставляем старое изображение
                $model->image = $old;
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Удаление существующего бренда
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Поиск бренда по идентификатору
     */
    protected function findModel($id) {
        if (($model = Brand::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
