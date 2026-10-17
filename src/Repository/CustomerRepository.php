<?php

namespace Vstore\Repository;

class CustomerRepository implements CustomerRepositoryInterface
{
	public function exists($userId)
	{
		return (bool) \e107::getDb()->select('vstore_customer', 'cust_id', 'cust_e107_user=' . (int) $userId);
	}

	public function update($userId, array $data)
	{
		return (bool) \e107::getDb()->update('vstore_customer', array('data' => $data, 'WHERE' => 'cust_e107_user=' . (int) $userId));
	}

	public function insert(array $data)
	{
		return \e107::getDb()->insert('vstore_customer', $data);
	}

	public function setRefCode($userId, $refCode)
	{
		return (bool) \e107::getDb()->update('vstore_customer', array(
			'data'  => array('cust_refcode' => $refCode),
			'WHERE' => 'cust_e107_user=' . (int) $userId,
		));
	}
}
