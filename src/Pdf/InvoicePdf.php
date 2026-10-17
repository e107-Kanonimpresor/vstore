<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Copyright (c) 2026 Kanonimpresor.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Vstore\Pdf;

if (!defined('K_PATH_CACHE') && defined('e_CACHE_CONTENT') && is_dir(e_CACHE_CONTENT) && is_writable(e_CACHE_CONTENT))
{
	define('K_PATH_CACHE', rtrim(realpath(e_CACHE_CONTENT), '/\\') . DIRECTORY_SEPARATOR);
}

/**
 * Invoice document built with the TCPDF package from Composer (no dependency on the e107 pdf plugin).
 * Everything that comes from e107 (site name, labels, logo path) is passed in, so the class has no e107 calls.
 */
class InvoicePdf extends \TCPDF
{
	/** @var array */
	private $options;

	/**
	 * @param array $options logo (file path), site_name, site_tag, footer_html, page_label, created_text,
	 *                       font_family, font_size, margin_left, margin_right, margin_top
	 */
	public function __construct(array $options = array())
	{
		$this->options = array_merge(array(
			'logo'         => '',
			'site_name'    => '',
			'site_tag'     => '',
			'footer_html'  => '',
			'page_label'   => 'Page',
			'created_text' => '',
			'font_family'  => 'helvetica',
			'font_size'    => 8,
			'margin_left'  => 25,
			'margin_right' => 15,
			'margin_top'   => 15,
		), $options);

		parent::__construct('P', 'mm', 'A4', true, 'UTF-8', false);

		$this->setCellHeightRatio(1.25);
	}

	/**
	 * Add the page and write the invoice HTML.
	 *
	 * @param string $html
	 * @param array  $meta creator, author, title, subject, keywords
	 */
	public function build($html, array $meta = array())
	{
		$meta = array_merge(array('creator' => '', 'author' => '', 'title' => '', 'subject' => '', 'keywords' => ''), $meta);

		$this->SetMargins($this->options['margin_left'], $this->options['margin_top'], $this->options['margin_right']);
		// The footer block is about 35 mm high, so the text must stop above it.
		$this->SetAutoPageBreak(true, 40);
		$this->SetPrintHeader(true);
		$this->SetPrintFooter(true);
		$this->SetCellPadding(0);
		$this->setHtmlVSpace(array('p' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n' => 0))));
		$this->setImageScale(0.47);

		$font = array($this->options['font_family'], '', $this->options['font_size']);
		$this->SetFont($font[0], $font[1], $font[2]);
		$this->SetHeaderFont($font);
		$this->SetFooterFont($font);

		$this->SetCreator($meta['creator']);
		$this->SetAuthor($meta['author']);
		$this->SetTitle($meta['title']);
		$this->SetSubject($meta['subject']);
		$this->SetKeywords($meta['keywords']);

		$this->AddPage();
		$this->writeHTML($html, true, false, true, false, '');
	}

	/** Header: site name and tag line on the left, logo on the right, a line below. */
	public function Header()
	{
		$top       = $this->options['margin_top'];
		$pageWidth = $this->getPageWidth();
		$logoBottom = $top;
		$logo      = $this->options['logo'];

		if ($logo !== '' && is_readable($logo))
		{
			$this->Image($logo, $pageWidth - $this->rMargin - 35, $top, 35, 35, '', '', '', true, 300, '', false, false, 0, true, false, false);
			$logoBottom = $this->getImageRBY();
		}

		$this->SetY($top + 2);
		$width = $pageWidth - $this->GetX() - $this->rMargin;

		$this->SetFont($this->options['font_family'], 'B', 14);
		$this->Cell($width, 5, $this->options['site_name'], 0, 1, 'L');
		$this->SetFont($this->options['font_family'], 'I', $this->options['font_size']);
		$this->Cell($width, 5, $this->options['site_tag'], 0, 1, 'L');
		$this->SetFont($this->options['font_family'], '', $this->options['font_size']);

		$this->SetY((2.835 / $this->getScaleFactor()) + max($logoBottom, $this->GetY()));
		$this->SetX($this->lMargin);
		$this->Cell(0, 0, '', 'T', 0, 'C');

		$this->SetTopMargin($this->GetY() + 2);
	}

	/** Footer: merchant blocks, page number and creation date. */
	public function Footer()
	{
		$this->SetY(-35);
		$this->SetFont($this->options['font_family'], '', $this->options['font_size']);
		$this->writeHTML($this->options['footer_html'], true, false, false, false, 'L');
		$this->Cell(0, 5, $this->options['page_label'] . ' ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
		$this->Cell(0, 5, $this->options['created_text'], 0, 1, 'R');
	}

	/** Entities that e107 stores in text and that TCPDF would print literally. */
	public static function decodeEntities($text)
	{
		return str_replace(array('&#39;', '&#039;', '&#036;', '&quot;'), array("'", "'", '$', '"'), (string) $text);
	}
}
