<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Based on e107inc/vstore (CaMerOn, Achim Ennenbach, e107 Inc). Modified by Kanonimpresor, 2025-2026.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */
if (!defined('e107_INIT')) { exit; }

/**
 * About tab shortcodes. Metadata is injected by vstore_admin_common::AboutPage() through setVars().
 */
class plugin_vstore_vstore_about_shortcodes extends e_shortcode
{
	private function v($key)
	{
		return isset($this->var[$key]) ? (string) $this->var[$key] : '';
	}

	public function sc_vstore_about_name()
	{
		return htmlspecialchars($this->v('name'), ENT_QUOTES);
	}

	public function sc_vstore_about_version()
	{
		return htmlspecialchars($this->v('version'), ENT_QUOTES);
	}

	public function sc_vstore_about_released()
	{
		return htmlspecialchars($this->v('released'), ENT_QUOTES);
	}

	public function sc_vstore_about_compat()
	{
		return htmlspecialchars($this->v('compatibility'), ENT_QUOTES);
	}

	public function sc_vstore_about_changelog_url()
	{
		return htmlspecialchars($this->v('changelog'), ENT_QUOTES);
	}

	public function sc_vstore_about_year()
	{
		return date('Y');
	}

	public function sc_vstore_about_agency()
	{
		return htmlspecialchars($this->v('agency'), ENT_QUOTES);
	}

	public function sc_vstore_about_license()
	{
		return htmlspecialchars($this->v('license'), ENT_QUOTES);
	}

	public function sc_vstore_about_license_url()
	{
		return htmlspecialchars($this->v('license_url'), ENT_QUOTES);
	}

	public function sc_vstore_about_metadata_rows()
	{
		$e = function ($s)
		{
			return htmlspecialchars((string) $s, ENT_QUOTES);
		};

		$website = $this->v('website');
		$email   = $this->v('email');

		$rows = array(
			'LAN_PLUGIN_VSTORE_ABOUT_LBL_VERSION'  => $e($this->v('version')),
			'LAN_PLUGIN_VSTORE_ABOUT_LBL_RELEASED' => $e($this->v('released')),
			'LAN_PLUGIN_VSTORE_ABOUT_LBL_COMPAT'   => 'e107 ' . $e($this->v('compatibility')) . '+',
			'LAN_PLUGIN_VSTORE_ABOUT_LBL_AUTHOR'   => $e($this->v('author')) . ' &mdash; ' . $e($this->v('agency')),
			'LAN_PLUGIN_VSTORE_ABOUT_LBL_CONTACT'  => '<a href="' . $e($website) . '" target="_blank" rel="noopener">' . $e($website) . '</a>'
				. ' &middot; <a href="mailto:' . $e($email) . '">' . $e($email) . '</a>',
			'LAN_PLUGIN_VSTORE_ABOUT_LBL_LICENSE'  => $e($this->v('license')),
		);

		$html = '';
		foreach($rows as $label => $value)
		{
			$html .= '<tr><th style="width:30%">' . constant($label) . '</th><td>' . $value . '</td></tr>';
		}

		return $html;
	}

	public function sc_vstore_about_buttons()
	{
		$e = function ($s)
		{
			return htmlspecialchars((string) $s, ENT_QUOTES);
		};

		$buttons = array(
			array('docs',      'btn-default', LAN_PLUGIN_VSTORE_ABOUT_BTN_DOCS),
			array('support',   'btn-default', LAN_PLUGIN_VSTORE_ABOUT_BTN_SUPPORT),
			array('donate',    'btn-warning', LAN_PLUGIN_VSTORE_ABOUT_BTN_DONATE),
			array('changelog', 'btn-link',    LAN_PLUGIN_VSTORE_ABOUT_BTN_CHANGELOG),
		);

		$html = '';
		foreach($buttons as $b)
		{
			$html .= '<a class="btn ' . $b[1] . '" href="' . $e($this->v($b[0])) . '" target="_blank" rel="noopener">' . $b[2] . '</a> ';
		}

		return $html;
	}
}
