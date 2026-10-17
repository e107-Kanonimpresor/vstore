<?php

namespace Vstore\Repository;

class OrderRepository
{
	/** Highest invoice number in use, 0 when there are no orders. */
	public function lastInvoiceNr()
	{
		$row = \e107::getDb()->retrieve('vstore_orders', 'MAX(order_invoice_nr) AS last');

		return $row ? (int) $row['last'] : 0;
	}

	/** @return int order_id, 0 when the invoice number is unknown */
	public function orderIdFromInvoiceNr($invoiceNr)
	{
		return (int) \e107::getDb()->retrieve('vstore_orders', 'order_id', 'order_invoice_nr=' . (int) $invoiceNr);
	}

	/** @return int order_id of the Mollie order with this payment id, 0 when there is none */
	public function orderIdFromMollieTransaction($transactionId)
	{
		$id = \e107::getDb()->escape((string) $transactionId);

		return (int) \e107::getDb()->retrieve('vstore_orders', 'order_id', "order_pay_transid='" . $id . "' AND order_pay_gateway LIKE 'mollie%'");
	}
}
