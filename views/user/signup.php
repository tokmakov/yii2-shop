<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Регистрация';

/*
* Если данные формы не прошли валидацию, получаем из сессии сохраненные
* данные, чтобы заполнить ими поля формы, не заставляя пользователя
* заполнять форму повторно
*/
$email = '';
$password = '';
if (Yii::$app->session->hasFlash('signup-data')) {
    $data = Yii::$app->session->getFlash('signup-data');
    $email = Html::encode($data['email']);
    $password = Html::encode($data['password']);
}
?>

<div class="container">
    <?php
    $success = false;
    if (Yii::$app->session->hasFlash('signup-success')) {
        $success = Yii::$app->session->getFlash('signup-success');
    }
    ?>
    <div id="response">
        <?php if (!$success): ?>
            <?php if (Yii::$app->session->hasFlash('signup-errors')): ?>
                <div class="alert alert-warning alert-dismissible" role="alert">
                    <button type="button" class="close"
                            data-dismiss="alert" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <p>При заполнении формы допущены ошибки</p>
                    <?php $allErrors = Yii::$app->session->getFlash('signup-errors'); ?>
                    <ul>
                        <?php foreach ($allErrors as $errors): ?>
                            <?php foreach ($errors as $error): ?>
                                <li><?= $error; ?></li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close"
                        data-dismiss="alert" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
                <p>Ваше успешно зарегистрировались на сайте</p>
            </div>
        <?php endif; ?>
    </div>

    <?php
    $form = ActiveForm::begin([
        'id' => 'signup',
        'enableAjaxValidation' => true,
        'validationUrl' => Url::to(['user/validate'])
    ]);
    ?>
    <?= $form->field($model, 'email')->input('email', ['value' => $email]); ?>
    <?= $form->field($model, 'password')->input('password', ['value' => $password]); ?>
    <?= $form->field($model, 'password_repeat')->input('password', ['value' => '']); ?>
    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php
    ActiveForm::end();
    ?>
</div>

