
<?php
require_once 'Mage/Newsletter/controllers/ManageController.php';
class Emp_Emma_ManageController extends Mage_Newsletter_ManageController
{
    public function preDispatch()
    {
        parent::preDispatch();
        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
        }
    }

    public function saveAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('customer/account/');
        }
        try {
            Mage::getSingleton('customer/session')->getCustomer()
            ->setStoreId(Mage::app()->getStore()->getId())
            ->setIsSubscribed((boolean)$this->getRequest()->getParam('is_subscribed', false))
            ->save();
            if ((boolean)$this->getRequest()->getParam('is_subscribed', false)) {

                $customer = Mage::getSingleton('customer/session')->getCustomer();

                $emma_object = Mage::getModel('emma/EMMAAPI');

                $members = array(
                	array( 'email' => $customer->getEmail() ),
                );

                $groups_ids = explode(',', Mage::getStoreConfig('emmasection/emma_lists/emma_groups') );

				foreach($groups_ids as $groups_id) {
                   $groups_list[] = (integer) $groups_id;
                }

                $response = $emma_object->import_member_list($members, 'emma_register_add', 1, $groups_list);

                Mage::getSingleton('customer/session')->addSuccess($this->__('The subscription has been saved.'));

            } else {

                $customer = Mage::getSingleton('customer/session')->getCustomer();

                $emma_object = Mage::getModel('emma/EMMAAPI');

                $member_details = $emma_object->get_member_detail_by_email($customer->getEmail());

                $groups_ids = explode(',', Mage::getStoreConfig('emmasection/emma_lists/emma_groups') );

				foreach($groups_ids as $groups_id) {
                   $groups_list[] = (integer) $groups_id;
                }

                Mage::log($groups_list, null, 'emma.log');

                if(isset($member_details->member_id) && ($member_details->member_id))
                    $response = $emma_object->remove_member_from_groups($member_details->member_id, $groups_list );

                Mage::log($response, null, 'emma.log');

                Mage::getSingleton('customer/session')->addSuccess($this->__('The subscription has been removed.'));
            }
        }
        catch (Exception $e) {
            Mage::getSingleton('customer/session')->addError($this->__('An error occurred while saving your subscription.'));
        }
        $this->_redirect('customer/account/');
    }
}
