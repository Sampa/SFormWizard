SFormWizard is a wrapper for http://thecodemine.org

Features:
Turn a normal form into a multi-step ajax form in no time.
A bunch of events,options and methods (see link above).

Installation:
unzip to protected/extensions/sformwizard

Make sure your form has an id (or other unique identifier) 

Then, divide your normal form into steps by adding class="step" to any div AND give each div an unique id.
this id can be anything. To use other elements then a div tag, please see  http://thecodemine.org
Leave the submit button outside of the steps
Example:
...
<div class="step" id="Step1">
	<div class="row">
			<p class="note">Fields with <span class="required">*</span> are required.</p>

			<?php echo $form->labelEx($model,'title'); ?>
			<?php echo $form->textField($model,'title',array('size'=>80,'maxlength'=>128)); ?>
			<?php echo $form->error($model,'title'); ?>
		</div>
	<div class="row">
			<?php echo $form->labelEx($model,'content'); ?>
			<?php echo CHtml::activeTextArea($model,'content',array('rows'=>10, 'cols'=>70)); ?>
			<p class="hint">You may use <a target="_blank" href="http://daringfireball.net/projects/markdown/syntax">Markdown syntax</a>.</p>
			<?php echo $form->error($model,'content'); ?>
	</div>
</div>
...
Minimum code:

 <?php 			
		$this->widget('ext.sformwizard.SFormWizard',array(
			'selector'=>"#post-form", //change this to the jquery selector for your form
			));
	?>
All options and their default values (see SFormWizard.php for description of each or visit   http://thecodemine.org
 <?php 			
	$this->widget('ext.sformwizard.SFormWizard',array(
		'selector'=>"#post-form",
			'historyEnabled' =>"false",
			'validationEnabled' => "false",
			'validationOptions' => "undefined",
			'formPluginEnabled' => "false",
			'formOptions' => '{ reset: "true",}, success: function(data) { alert("success"); }',
			'linkClass' =>".link",
			'submitStepClass' =>".submit_step",
			'back' => ":reset",
			'next' => ":submit",
			'textSubmit' => "Submit",
			'textNext' => "Next",
			'textBack' =>"Back",
			'remoteAjax' =>"undefined",
			'inAnimation' => "{opacity:'show'}",
			'outAnimation' => "{opacity:'hide'}",
			'inDuration' => 400,
			'outDuration' => 400,
			'easing' => "swing",
			'focusFirstInput' => "false",
			'disableInputFields' => "true",
			'disableUIStyles' => "false",
			'jsAfterStepShown'=>	'',

		));
?>
A more complex but far from full featured example that uses ajax:
For this we need to slightly modify out controller action. 
This is the how it should look in the blog demo (PostController).
Note you can customize as much if you want if you understand the code.
public function actionCreate()
	{
		$model=new Post;
		
		if(isset($_POST['Post']))
		{
			
			$model->attributes=$_POST['Post'];
			if($model->save()){
					if(Yii::app()->request->isAjaxRequest){
					$comment=$this->newComment($model);
					$link = CHtml::link('Click here to see your new post','view?id='.$model->id);
							echo CJSON::encode($link);
							exit;
					}
					//if ithe form is submitted without ajax 
					
				$this->redirect(array('view','id'=>$model->id));
			}else{
				if(Yii::app()->request->isAjaxRequest){
								echo CJSON::encode("Error! Could not save the model");
								exit;
						}
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
Next:
	Add a couple of placeholder divs below your form	
<div id="stepmessage"></div>
<div id="data"></div>
And then the widgetcall:
 <?php 
					
		$this->widget('ext.sformwizard.SFormWizard',array(
			'selector'=>"#post-form",
			'historyEnabled' =>"true",
			'validationEnabled' => "true",
			'validationOptions' => "undefined",
			'formPluginEnabled' => "true",
			'formOptions' => '{
						success: function(data){$("#stepmessage").fadeTo(500,1,function(){ $("#stepmessage").html(data); })},
						beforeSubmit: function(data){
							$("#data").html("data sent to the server: " + $.param(data));
							},
						dataType: "json",
						resetForm: true
				 	}	',
			'linkClass' =>".link",
			'submitStepClass' =>".submit_step",
			'back' => ":reset",
			'next' => ":submit",
			'textSubmit' => "Submit",
			'textNext' => "Next",
			'textBack' =>"Back",
			'remoteAjax' =>"undefined",
			'inAnimation' => "{opacity:'show'}",
			'outAnimation' => "{opacity:'hide'}",
			'inDuration' => 400,
			'outDuration' => 400,
			'easing' => "swing",
			'focusFirstInput' => "true",
			'disableInputFields' => "true",
			'disableUIStyles' => "false",
			'jsAfterStepShown'=>	'
				/*		Available in data (data.steps ,data.settings etc):
						isBackNavigation - boolean
						settings - options object containing the options set for the wizard
						activatedSteps - list of activated steps (visited steps)
						isLastStep - boolean specifying whether the current step is a submit step
						isFirstStep - boolean
						previousStep - the id of the previously visited step
						currentStep - the id of the current step
						backButton
						nextButton
						steps - the steps of the wizard 
						firstStep - the id of the first step
					*/
						$("#stepmessage").html("");
						$.each(data.activatedSteps, function(){
						$("#stepmessage").append(this+" - ");
						});
					var currentstepnumber = data.activatedSteps.length;					
					$("#stepmessage").append("<br/>Step "+currentstepnumber+"/"+data.steps.length);',
			));?>		
			
			
			


