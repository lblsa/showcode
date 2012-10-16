<?php
class MyCCaptcha extends CCaptcha
{
	//private static nj='111';
	//public $my_id='11';
	public $captchaAction='captcha';
	
	public function registerClientScript()
	{
		$cs=Yii::app()->clientScript;
		$id=$this->imageOptions['id'];
		$url=$this->getController()->createUrl($this->captchaAction,array(CCaptchaAction::REFRESH_GET_VAR=>true));

		$js="";
		if($this->showRefreshButton)
		{
			$cs->registerScript('Yii.CCaptcha#'.$id,'dummy');
			$label=$this->buttonLabel===null?Yii::t('yii','Get a new code'):$this->buttonLabel;
			$options=$this->buttonOptions;
			if(isset($options['id']))
				$buttonID=$options['id'];
			else
				$buttonID=$options['id']=$id.'_button';
			if($this->buttonType==='button')
				$html=CHtml::button($label, $options);
			else
				$html=CHtml::link($label, $url, $options);
			$js="jQuery('#$id').after(".CJSON::encode($html).");";
			$selector="#$buttonID";
		}

		if($this->clickableImage)
			$selector=isset($selector) ? "$selector, #$id" : "#$id";

		if(!isset($selector))
			return;

		$js.="
jQuery('$selector').live('click',function(){
	jQuery.ajax({
		url: ".CJSON::encode($url).",
		dataType: 'json',
		cache: false,
		success: function(data) {
			jQuery('#$id').attr('src', data['url']);
			jQuery('body').data('{$this->actionPrefix}{$this->captchaAction}.hash', [data['hash1'], data['hash2']]);
		}
	});
	return false;
});
";
		$cs->registerScript('Yii.CCaptcha#'.$id,$js);
	}
}
?>