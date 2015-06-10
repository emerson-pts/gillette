<?php
//Lockable Behavior
class LockableBehavior extends ModelBehavior {
	function beforeSave(&$model){
		if(!empty($model->autoLock) && !$this->lock($model))return false;
	}
	
	function afterSave(&$model){
		if(!empty($model->autoLock))$this->unlock($model);
	}

	function _defaultLockName($model){
		return 'CakePHP'.$model->alias.'Lock';
	}

	function lock(&$model, $lockname = null){
		if(empty($lockname))$lockname = $this->_defaultLockName($model);

//		var_dump($model->query("SELECT IS_FREE_LOCK('{$lockname}')"));
		$result = $model->query("SELECT GET_LOCK('{$lockname}',5)");
		if (current($result[0][0]) == 0){
			debug("Cannot get lock");
			return false;
		}elseif (!$result){
			debug("Server died away or something");
			return false;
		}else{
			return true;
		}
	}

	function unlock(&$model, $lockname = null){
		if(empty($lockname))$lockname = $this->_defaultLockName($model);
		$result = $model->query("SELECT RELEASE_LOCK('{$lockname}')");
		if (current($result[0][0]) == 0){
			debug("Cannot release lock");
			return false;
		}elseif (!$result){
			debug("Server died away or something");
			return false;
		}else{
			return true;
		}
	}
}
