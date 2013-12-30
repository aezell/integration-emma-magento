<?php

class Emp_Emma_Model_Observer {

	/**
	 * newsletterSubscriberChange function.
	 *
	 * @access public
	 * @param Varien_Event_Observer $observer
	 * @return void
	 */
	public function newsletterSubscriberSaveAfter(Varien_Event_Observer $observer) {

	    $subscriber = $observer->getEvent()->getSubscriber();

        $emma_object = Mage::getModel('emma/EMMAAPI');

        $members = array(
        	array( 'email' => $subscriber->getEmail() ),
        );

        $groups_ids = explode(',', Mage::getStoreConfig('emmasection/emma_lists/emma_groups') );

		foreach($groups_ids as $groups_id) {
           $groups_list[] = (integer) $groups_id;
        }

        $response = $emma_object->import_member_list($members, 'emma_register_add', 1, $groups_list);
	}
}