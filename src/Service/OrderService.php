<?php

namespace Vstore\Service;

use Vstore\Repository\CustomerRepositoryInterface;
use Vstore\Support\Translatable;

class OrderService
{
	/**
	 * Order reference: first two letters of first and last name followed by the number padded to six digits.
	 *
	 * @param int    $id
	 * @param string $firstname
	 * @param string $lastname
	 * @return string
	 */
	public function reference($id, $firstname, $lastname)
	{
		return strtoupper(substr($firstname, 0, 2) . substr($lastname, 0, 2) . str_pad((string) $id, 6, '0', STR_PAD_LEFT));
	}

	/**
	 * Move the additional checkout fields (add_fieldN) out of the customer data.
	 *
	 * @param array  $fieldConfig Preference additional_fields (caption per language, type)
	 * @param array  $customer    Customer data
	 * @param string $language    e_LANGUAGE
	 * @return array customer data without add_fieldN and additional_fields as a list of caption and value
	 */
	public function splitAdditionalFields(array $fieldConfig, array $customer, $language)
	{
		$additional = array();

		foreach ($fieldConfig as $key => $field)
		{
			$name = 'add_field' . $key;

			if (!isset($customer[$name]))
			{
				continue;
			}

				$caption = Translatable::pick(isset($field['caption']) ? $field['caption'] : '', $language);
			$isText  = isset($field['type']) && $field['type'] === 'text';

			$additional[$name] = array(
				'caption' => strip_tags($caption),
				'value'   => $isText ? $customer[$name] : ($customer[$name] ? 'X' : '-'),
			);

			unset($customer[$name]);
		}

		return array('customer' => $customer, 'additional' => $additional);
	}

	/**
	 * Create or update the customer record of a logged in user.
	 *
	 * @return bool
	 */
	public function saveCustomer(CustomerRepositoryInterface $repository, $userId, array $customer, array $shipping, $useShipping, $gateway, $now)
	{
		$data = array();

		foreach ($customer as $key => $value)
		{
			$data['cust_' . $key] = $value;
		}

		$data['cust_shipping']     = json_encode($shipping, JSON_PRETTY_PRINT);
		$data['cust_use_shipping'] = $useShipping ? 1 : 0;
		$data['cust_gateway']      = $gateway;
		$data['cust_datestamp']    = $now;

		if ($repository->exists($userId))
		{
			return $repository->update($userId, $data);
		}

		$data['cust_e107_user'] = $userId;
		$id = $repository->insert($data);

		if (!$id)
		{
			return false;
		}

		return $repository->setRefCode(
			$userId,
			$this->reference($id, isset($customer['firstname']) ? $customer['firstname'] : '', isset($customer['lastname']) ? $customer['lastname'] : '')
		);
	}
}
