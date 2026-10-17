<?php

namespace Vstore\Payment;

use Omnipay\Mollie\Gateway;

class MollieGateway extends Gateway
{
	/**
	 * @param array $parameters
	 * @return MolliePurchaseRequest
	 */
	public function purchase(array $parameters = [])
	{
		return $this->createRequest(MolliePurchaseRequest::class, $parameters);
	}
}
