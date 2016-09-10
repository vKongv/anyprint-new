<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Printing Shop Registration';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to signup:</p>

    <div class="row">
        <div class="col-lg-5">
            <!-- TODO: Send verification code when submitting -->
            <!-- TODO: Verify mobile number (Low prio) -->
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'hp') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'repeat_password')->passwordInput() ?>

                <?= $form->field($model, 'shop_name') ?>

                <?= $form->field($model, 'area')
                    ->dropDownList(['Kedah' => 'Kedah',
                            'Kelantan' => 'Kelantan',
                            'Kuala Lumpur' => 'Kuala Lumpur',
                            'Melaka' => 'Melaka',
                            'Negeri Sembilan' => 'Negeri Sembilan',
                            'Pahang' => 'Pahang',
                            'Perak' => 'Perak',
                            'Perlis' => 'Perlis',
                            'Penang' => 'Penang',
                            'Sabah' => 'Sabah',
                            'Sarawak' => 'Sarawak',
                            'Selangor' => 'Selangor',
                            'Terengganu' => 'Terengganu'],
                        [
                            'options' => ['Kuala Lumpur' => ['Selected' => true]],
                        ]) ?>

                <?= $form->field($model, 'address') ?>


                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
            <!-- TODO: Make this text smaller -->
            <p>Looking to register your shop? Click <?= Html::a('here', ['site/business-sign-up']) ?>!</p>
        </div>
    </div>
</div>