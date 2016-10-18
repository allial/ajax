<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use backend\models\Companies;
use backend\models\Branches;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Departments */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="departments-form">

    <?php $form = ActiveForm::begin(['id'=>$model->formName()]); ?>

	<?PHP /*$form->field($model, 'companies_company_id')->widget(Select2::classname(), [
	    'data' => ArrayHelper::map(Companies::find()->all(),'company_id','company_name'),

		'pluginEvents' => [
		    'change' => 'function() {
		    	$.post( "branches/lists?id=1",function( data ) {
		    		$("select#models-contact" ).html( data );
				})
			};',
		],
	    
	    'language' => 'en',
	    'options' => ['placeholder' => 'Select a company ...'],
	    'pluginOptions' => [
	        'allowClear' => true
	    ],
	]); */?>
	
    <?= $form->field($model, 'companies_company_id')->dropDownList(
		ArrayHelper::map(Companies::find()->all(),'company_id','company_name'),
		[
			'prompt'=>'Select Company',
		    'onChange' => '
		    	$.post( "/branches/lists?id='.'"+$(this).val(), function( data ) {
		    		$("select#departments-branches_branch_id" ).html( data );
				});'
		]); ?>

    <?= $form->field($model, 'branches_branch_id')->dropDownList(
		ArrayHelper::map(Branches::find()->all(),'branch_id','branch_name'),
		['prompt'=>'Select Branch']
	) ?>

    <?= $form->field($model, 'department_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'department_status')->dropDownList([ 'active' => 'Active', 'inactive' => 'Inactive', ], ['prompt' => 'Status']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php
$script = <<< JS

$('form#{$model->formName()}').on('beforeSubmit'), function(e)
{
	var \$form= $(this);
	$.post(
		\$form.attr("action"), // serialiae Yiii2 form
		\$form.serialize()
	)
	.done(function($result)) {
		console.log(result);
		// if(result.message == 'Success')
		// {
			// $(document).find('#secondmodal').modal('hide');
			// $.pjax.reload({container:'#commodity-grid'});
		// } else {
			// $(\$form).trigger("reset");
			// $("#message").html(result.message)
		// }
	})
	.fail(function(){
		console.log("server error");
	});
	return false;
});

JS;
$this->registerJs($script);
