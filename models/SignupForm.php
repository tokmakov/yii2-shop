<?php

namespace app\models;

use yii\base\Model;

class SignupForm extends Model {

    public $email;
    public $password;
    public $password_repeat;

    public function rules() {
        return [
            // удалим случайные пробелы для трех полей
            [['email', 'password', 'password_repeat'], 'trim'],
            // email и пароль обязательны для заполнения
            [
                ['email', 'password', 'password_repeat'],
                'required',
                'message' => 'Это поле обязательно для заполнения'
            ],
            // email должен быть уникальным
            [
                'email',
                'unique',
                'targetClass' => User::class,
                'message' => 'Пользователь с таким e-mail уже зарегистрирован'
            ],
            // поле email должно быть адресом почты
            ['email', 'email'],
            // пароль не может быть короче 8 символов
            [['password', 'password_repeat'], 'string', 'min' => 8],
            // поле password_repeat должно совпадать с password
            ['password_repeat', 'compare', 'compareAttribute' => 'password']
        ];
    }

    public function attributeLabels() {
        return [
            'username' => 'E-mail',
            'password' => 'Пароль',
            'password_repeat' => 'Пароль еще раз',
        ];
    }
}
