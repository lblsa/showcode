<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	public $layout2='//layouts/column3';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	public $headering = '';

        protected function beforeAction($action) {
            $IP = $_SERVER["REMOTE_ADDR"];
            $visitor_ban = Visitors::model()->find('ip=:ip', array(':ip'=>$IP));
            if(intval($visitor_ban['BAN']) && $this->id.'/'.$action->id !== 'user/permissionDenied'){
                if(date('Y-m-d H:i:s') < $visitor_ban['time_ban']){
                    $this->redirect('/user/permissionDenied');
                    return false;
                }else{
                    Visitors::model()->updateByPk($visitor_ban->id, array('count'=>0,'BAN' => 0, 'time_ban'=>NULL,'time_last_come'=>Yii::app()->mf->dateForMysql(date('Y-m-d')).' '.date('H:i:s')));
                }
            }
            return true;
        }
}