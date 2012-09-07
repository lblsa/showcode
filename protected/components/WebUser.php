<?php
class WebUser extends CWebUser {

	private $_model = null;

/**
 * Получаем значения всех полей таблицы текущего Пользователя.
 * Example: Yii::app()->user->uid
 * @return mixed uid,etc.
 */
	function getUid() {
		if ($this->_model === null)
			$this->getModel();
		return $this->_model->uid;
	}

	function getType() {
		if ($this->_model === null)
			$this->getModel();
		return $this->_model->type;
	}

	function getUniq() {
		if ($this->_model === null)
			$this->getModel();
		return $this->_model->uniq;
	}

	function getEmail() {
		if ($this->_model === null)
			$this->getModel();
		return $this->_model->email;
	}

	function getPhone() {
		if ($this->_model === null)
			$this->getModel();
		return $this->_model->phone;
	}

        function getEvents() {
		if ($this->_model === null)
			$this->getModel();
		return $this->_model->events;
	}

        function getCountEvents() {
		if ($this->_model === null)
			$this->getModel();
		return count($this->_model->events);
	}

	function getName() {
		if ($this->_model === null)
			$this->getModel();
		if(isset($this->_model->role)){
			return $this->_model->name;
		}else{
			return '';
		}
	}

	function getRole() {
		if ($this->_model === null)
			$this->_model = $this->getModel();
		if(isset($this->_model->role)){
			return $this->_model->role;
		}else{
			return '';
		}
    }

	function getAccess_token() {
		if ($this->_model === null)
			$this->getModel();
		return $this->_model->access_token;
    }

    function getSend_mail() {
		if ($this->_model === null)
			$this->getModel();
		return $this->_model->send_mail;
	}

    function getModel(){
        if (!$this->isGuest && $this->_model === null)
            $this->_model = User::model()->findByPk($this->id);
        return $this->_model;
    }

	function refresh(){
		$this->_model = User::model()->findByPk($this->id);
		return true;
	}

	/**
	 * По id пользователя выдаёт его имя
	 * @param integer $author_id id пользователя.
	 * @return string имя пользователя.
	 */
	public function getAuthorName($author_id)
    {
		$user = User::model()->findByPk($author_id, array('select' => 'name'));
		return $user->name;
    }

	/**
	 * Функция проверяет, является ли пользователь администратором.
	 * Пример использования: Yii::app()->user->isAdmin();
	 * @return boolean true, если текущий пользователь является администратором.
	 */
	public function isAdmin()
	{
		if (Yii::app()->user->role=='admin')
			return true;
		else
			return false;
    }

	/**
	 * Функция проверяет, является ли пользователь организатором.
	 * Пример использования: Yii::app()->user->isOrganizer();
	 * @return boolean true, если текущий пользователь является организатором.
	 */
	public function isOrganizer()
	{
		if (Yii::app()->user->role=='organizer')
			return true;
		else
			return false;
    }

	/**
	 * Функция по id мероприятия проверяет, является ли пользователь создателем этого мероприятия.
	 * @param string $event_id id мероприятия.
	 * @return boolean true, если текущий пользователь является создателем этого мероприятия.
	 */
	public function isCreator($event_id)
	{
		$count = Events::model()->countBySql('select COUNT(*) from tbl_events where author="' .Yii::app()->user->id. '" and id="' .$event_id. '"');
		if($count && (Yii::app()->user->role=='organizer' || Yii::app()->user->role=='admin'))
			return true;
		else
			return false;
	}

	/**
	 * Функция проверяет, является ли пользователь является текущим полльзователем.
	 * Пример использования: Yii::app()->user->isCurUser($_GET["id"]);
	 * @return boolean true, если текущий пользователь является текущим полльзователем.
	 */
	public function isCurUser($event_id)
	{
		if (Yii::app()->user->id == $event_id)
			return true;
		else
			return false;
    }
}
?>