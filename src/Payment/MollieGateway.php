<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Copyright (c) 2026 Kanonimpresor.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */

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
