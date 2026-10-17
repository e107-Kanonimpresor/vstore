<?php

namespace Vstore\Service;

class InvoiceNumberService
{
	/** Order states that allow an invoice: New, Complete, Processing. */
	const INVOICE_STATES = array('N', 'C', 'P');

	/**
	 * Next invoice number: the configured start value when it is ahead of the last used number.
	 *
	 * @param int $lastUsed   Highest invoice number in the orders table
	 * @param int $configured Preference invoice_next_nr
	 * @return int
	 */
	public function next($lastUsed, $configured)
	{
		return (int) $configured > (int) $lastUsed ? (int) $configured : (int) $lastUsed + 1;
	}

	/** Prefix followed by the number padded to six digits. */
	public function format($invoiceNr, $prefix = '')
	{
		return $prefix . str_pad((string) $invoiceNr, 6, '0', STR_PAD_LEFT);
	}

	public function isValidState($orderStatus)
	{
		return in_array(strtoupper($orderStatus), self::INVOICE_STATES, true);
	}
}
