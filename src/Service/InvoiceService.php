<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Copyright (c) 2026 Kanonimpresor.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Vstore\Service;

use Vstore\Support\Result;

class InvoiceService
{
	/** @var InvoiceNumberService */
	private $numbers;

	public function __construct(InvoiceNumberService $numbers)
	{
		$this->numbers = $numbers;
	}

	/**
	 * Invoice title in the language of the shop, followed by the formatted invoice number.
	 *
	 * @param array  $pref          Plugin preferences (invoice_title per language, invoice_nr_prefix)
	 * @param string $language      e_LANGUAGE
	 * @param string $defaultTitle  Title used when the preference is empty
	 * @param int    $invoiceNr
	 * @return string
	 */
	public function title(array $pref, $language, $defaultTitle, $invoiceNr)
	{
		$title  = isset($pref['invoice_title'][$language]) ? $pref['invoice_title'][$language] : $defaultTitle;
		$prefix = isset($pref['invoice_nr_prefix']) ? (string) $pref['invoice_nr_prefix'] : '';

		return $title . ' ' . $this->numbers->format($invoiceNr, $prefix);
	}

	/** Full path of an existing invoice pdf, or an empty string. */
	public function existingPdf($userDir, $fileSlug)
	{
		$file = $userDir . $fileSlug . '.pdf';

		return is_readable($file) ? $file : '';
	}

	/**
	 * May the current user see the invoice of this order?
	 * Codes: ACCESS_DENIED, INVOICE_STATE (param: status label).
	 */
	public function checkAccess($orderUserId, $orderStatus, $currentUserId, $isAdmin, $statusLabel)
	{
		if ($orderUserId != $currentUserId && !$isAdmin)
		{
			return Result::fail('ACCESS_DENIED');
		}

		if (!$this->numbers->isValidState($orderStatus))
		{
			return Result::fail('INVOICE_STATE', array($statusLabel));
		}

		return Result::ok();
	}
}
