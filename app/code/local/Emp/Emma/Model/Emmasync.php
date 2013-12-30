<?php

class Emp_Emma_Model_Emmasync extends Mage_Core_Model_Abstract
{
    protected function _construct(){

       $this->_init("emma/emmasync");

    }

    /**
     * toOptionArray function.
     *
     * @access public
     * @return void
     */
    public function toOptionArray() {
        $emma_object=Mage::getModel('emma/EMMAAPI');
        $groups=$emma_object->list_groups('g,t');
		$emma_table_model = Mage::getModel('emma/emmasync');
        $data = $emma_table_model->load(1);
        $groups_ids = json_decode($data->groups);

        foreach($groups as $group) {
            $groups_list[] = array(
                'value' => $group->member_group_id,
                'label' => $group->group_name,
            );
        }

        return $groups_list;
    }
}
