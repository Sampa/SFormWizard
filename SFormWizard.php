<?php

 /** options
 		historyEnabled
		Enables the BBQ plugin
		Typeboolean
		Default valuefalse
		Descriptiontrue enables the BBQ plugin. Enables navigation of the wizard using the browser's back and forward buttons

		validationEnabled
		Enables the validation plugin
		Typeboolean
		Default valuefalse
		Descriptiontrue enables the validation plugin.

		validationOptions
		Holds options for the validation plugin
		TypeObject
		Default valueundefined
		DescriptionHolds options for the validation plugin. See validation plugin documentation for specific options.
		RequiresvalidationEnabled

		formPluginEnabled
		Enables the form plugin
		Typeboolean
		Default valuefalse
		Descriptiontrue enables the form plugin. Makes sure that the plugin is posted via AJAX. Set to false if you want to post the form without using AJAX.

		formOptions
		Holds options for the form plugin
		TypeObject
		Default value{ reset: true, success: function(data) { alert("success"); }
		Description Holds options for the form plugin. See form plugin documentation for specific options.

		linkClass
		CSS-class of inputs used as links in the wizard
		TypeString (selector)
		Default value".link"
		DescriptionSpecifies the CSS-class of inputs used as links in the wizard.

		submitStepClass
		CSS-class of steps where the form should be submitted
		TypeString
		Default value"submit_step"
		DescriptionSpecifies the CSS-class of the steps where the form should be submitted.
		
		back
		Elements used as back buttons
		TypeString (selector)
		Default value":reset"
		DescriptionSpecifies the elements used as back buttons

		next
		Elements used as next buttons   	
		TypeString (selector)
		Default value":submit"
		DescriptionSpecifies the elements used as next buttons
	
		textSubmit
		The text of the next button on submit steps
		TypeString
		Default value'Submit'
		DescriptionThe text of the next button on submit steps.
		
		textNext
		The text of the next button on non-submit steps
		TypeString
		Default value'Next'
		DescriptionThe text of the next button on non-submit steps.
	
		textBack
		The text of the back button
		TypeString
		Default value'Back'
		DescriptionThe text of the back button.

		remoteAjax
		Object holding options for AJAX calls done between steps
		TypeObject
		Default valueundefined
		DescriptionObject holding options for AJAX calls done between steps
		
		inAnimation
		The animation done during the in-transition between steps
		TypeObject
		Default value{opacity: 'show'}
		DescriptionSpecifies the animation done during the in-transition between steps

		outAnimation
		The animation done during the out-transition between steps
		TypeObject
		Default value{opacity: 'hide'}
		DescriptionSpecifies the animation done during the out-transition between steps

		inDuration
		The duration of the in-animation between steps   
		TypeNumber
		Default value400
		DescriptionSpecifies the duration of the in-animation between steps
		
		outDuration
		The duration of the out-animation between steps
   	 	TypeNumber
		Default value400
		DescriptionSpecifies the duration of the out-animation between steps
	
	
		easing
		The easing used during the transition animations between steps
   	 	TypeString
		Default value'swing'
		DescriptionSpecifies the easing used during the transition animations between steps. See jQuery Easing Plugin documentation for more information on easings.
	
	
		focusFirstInput
		True means that the first input field on each step should be focused
		Typeboolean
		Default valuefalse
		DescriptionSpecifies whether the first input field on each step should be focused.
	
		disableInputFields
		True means that the input fields in the form should be disabled
		Typeboolean
		Default valuetrue
		DescriptionSpecifies whether the input fields in the form should be disabled during the initialization of the plugin. The disabling of inputs may be needed to be done in HTML if the number of input fields are very large, if this is needed - set this flag to false. 

		disableUIStyles
		True means that the wizard will not set any jquery UI styles
		Typeboolean
		Default valuefalse
		DescriptionSpecifies whether the wizard should use jquery UI styles or not. 

 **/
class SFormWizard extends CWidget {
	public $selector;
	public $jsAfterStepShown;
	public $historyEnabled = "false";
	public $validationEnabled = "false";
	public $validationOptions = "undefined";
	public $formPluginEnabled = "false";
	public $formOptions = '{ reset: "true"}, success: function(data) { alert("success"); }';
	public $linkClass =".link";
	public $submitStepClass =".submit_step";
	public $back = ":reset";
	public $next = ":submit";
	public $textSubmit = "Submit";
	public $textNext = "Next";
	public $textBack="Back";
	public $remoteAjax="undefined";
	public $inAnimation = "{opacity:'show'}";
	public $outAnimation = "{opacity:'hide'}";
	public $inDuration = 400;
	public $outDuration = 400;
	public $easing = "swing";
	public $focusFirstInput = "false";
	public $disableInputFields = "true";
	public $disableUIStyles = "false";
	
    /**
     * Publishes the required assets
     */
    public function init() {
        parent::init();
		$this -> publishAssets();
		
    }

    public function run() {

     //   $this->render("",array());
    }

    /**
     * Publises and registers the required CSS and Javascript
     * @throws CHttpException if the assets folder was not found
     */

    public function publishAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app() -> assetManager -> publish($assets);
        if (is_dir($assets)) {
            //the css to use
				Yii::app() -> clientScript -> registerCssFile($baseUrl . '/css/ui-lightness/jquery-ui-1.8.2.custom.css');
			// the js to use
			    Yii::app() -> clientScript -> registerScriptFile($baseUrl . "/js/jquery.form.js", CClientScript::POS_END);
				Yii::app() -> clientScript -> registerScriptFile($baseUrl . "/js/jquery.validate.js", CClientScript::POS_END);
				Yii::app() -> clientScript -> registerScriptFile($baseUrl . "/js/bbq.js", CClientScript::POS_END);
				Yii::app() -> clientScript -> registerScriptFile($baseUrl . "/js/jquery-ui-1.8.5.custom.min.js", CClientScript::POS_END);
				Yii::app() -> clientScript -> registerScriptFile($baseUrl . "/js/jquery.form.wizard.js", CClientScript::POS_END);
				Yii::app() -> clientScript -> registerScript('initformwizard','
			$(function(){
				$("'.$this->selector.'").formwizard({ 
				 	historyEnabled:'. $this->historyEnabled .',
					validationEnabled:'. $this->validationEnabled .',
					validationOptions:'. $this->validationOptions .',
					formPluginEnabled:'. $this->formPluginEnabled .',
					formOptions:'. $this->formOptions .',

					linkClass:"'. $this->linkClass .'",
					submitStepClass:"'. $this->submitStepClass .'",
					back:"'. $this->back.'",
				
					next:" '. $this->next .'",
					textSubmit : "'. $this->textSubmit .' ",
					textNext : "'. $this->textNext .'",
					textBack:"'. $this->textBack .'",
					remoteAjax:"'. $this->remoteAjax .'",
					inAnimation : '.$this->inAnimation .',
					outAnimation : '. $this->outAnimation .',
					inDuration : '. $this->inDuration .',
					outDuration :'. $this->outDuration .',
					easing : "'. $this->easing .'",
					focusFirstInput : '. $this->focusFirstInput .',		
					disableInputFields : '. $this->disableInputFields .',
					disableUIStyles :'. $this->disableUIStyles .',
				 });
				 
			$("#stepmessage").append($("'.$this->selector.'").formwizard("state").firstStep);
				$("'.$this->selector.'").bind("step_shown", function(event, data){
					'.$this->jsAfterStepShown.';
					});
		
		
				
			});', CClientScript::POS_END);
        } else {
            throw new CHttpException(500, __CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

}
