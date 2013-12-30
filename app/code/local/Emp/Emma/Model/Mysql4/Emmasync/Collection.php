<?php
class Emp_Emma_Model_Mysql4_Emmasync_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{

	/**
	 * _construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function _construct(){
		$this->_init("emma/emmasync");
	}

	/**
	 * addStoreFilter function.
	 *
	 * @access public
	 * @param mixed $store
	 * @param bool $withAdmin (default: true)
	 * @return void
	 */
	public function addStoreFilter($store, $withAdmin = true){
	    if ($store instanceof Mage_Core_Model_Store) {
	        $store = array($store->getId());
	    }

	    if (!is_array($store)) {
	        $store = array($store);
	    }

	    $this->addFilter('store_id', array('in' => $store));

	    return $this;
	}

}
