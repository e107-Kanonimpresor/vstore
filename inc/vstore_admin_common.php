<?php
if (!defined('e107_INIT')) { exit; }

/**
 * Shared admin behaviour for every vstore e_admin_ui controller:
 * About tab, User Guide tab and the sidebar help widget.
 *
 * Layers: controller (this trait) -> templates/vstore_{about,guide}_template.php
 *         -> languages/<Lang>/<Lang>_admin_{about,help}.php -> shortcodes/batch/vstore_about_shortcodes.php
 */
trait vstore_admin_common
{
	/**
	 * Metadata read straight from plugin.xml (single source of truth for version/date).
	 *
	 * @return array
	 */
	protected function getPluginInfo()
	{
		$version = '?';
		$released = '';
		$compat = '2.3';

		$file = e_PLUGIN . 'vstore/plugin.xml';
		$xml = is_readable($file) ? @simplexml_load_file($file) : false;

		if($xml)
		{
			$version  = (string) $xml['version'];
			$released = (string) $xml['date'];
			$compat   = (string) $xml['compatibility'];
		}

		$repo = 'https://github.com/e107-Kanonimpresor/vstore';

		return array(
			'name'          => 'Vstore',
			'slug'          => 'vstore',
			'version'       => $version,
			'released'      => $released,
			'compatibility' => $compat,
			'author'        => 'Kanonimpresor',
			'agency'        => 'Marketing de Performance',
			'website'       => 'https://marketingdeperformance.online',
			'email'         => 'info@marketingdeperformance.online',
			'repo'          => $repo,
			'docs'          => $repo . '#readme',
			'support'       => $repo . '/issues',
			'donate'        => 'https://www.paypal.com/donate/?hosted_button_id=C2DA8NC5XP56A',
			'changelog'     => $repo . '/blob/main/CHANGELOG.md',
			'license'       => 'GPL-3.0',
			'license_url'   => 'https://www.gnu.org/licenses/gpl-3.0.html',
		);
	}

	/**
	 * Substitutes {LAN_...} tokens before parseTemplate(), which only dispatches shortcodes.
	 *
	 * @param string $html
	 * @param string $prefix LAN constant prefix to match
	 * @return string
	 */
	protected function _resolveLans($html, $prefix)
	{
		$pattern = '/\{(' . preg_quote($prefix, '/') . '[A-Z0-9_]+)\}/';

		return preg_replace_callback($pattern, function ($m)
		{
			return defined($m[1]) ? constant($m[1]) : $m[0];
		}, $html);
	}

	/**
	 * About tab (menu entry main/about).
	 *
	 * @return string
	 */
	public function AboutPage()
	{
		e107::lan('vstore', 'admin_about', true);
		e107::css('vstore', 'css/vstore_admin.css');

		$tmpl = e107::getTemplate('vstore', 'vstore_about', 'body');
		$body = $this->_resolveLans((string) $tmpl, 'LAN_PLUGIN_VSTORE_ABOUT_');

		$sc = e107::getScBatch('vstore_about', 'vstore');
		$sc->setVars($this->getPluginInfo());

		return e107::getParser()->parseTemplate($body, true, $sc);
	}

	/**
	 * User Guide tab (menu entry main/guide). One Bootstrap tab per template key.
	 *
	 * @return string
	 */
	public function GuidePage()
	{
		e107::lan('vstore', 'admin_help', true);
		e107::css('vstore', 'css/vstore_admin.css');

		$tp = e107::getParser();
		$sc = e107::getScBatch('vstore_guide', 'vstore');
		$sections = e107::getTemplate('vstore', 'vstore_guide');

		$tabs = array();
		foreach((array) $sections as $key => $html)
		{
			$name = strtoupper($key);
			$html = $this->_resolveLans($html, 'LAN_VSTORE_GUIDE_' . $name . '_');
			$html = $this->_resolveLans($html, 'LAN_VSTORE_STATUS_');
			$tabs['guide-' . $key] = array(
				'caption' => defset('LAN_VSTORE_GUIDE_' . $name . '_TAB', $name),
				'text'    => $tp->parseTemplate($html, true, $sc),
			);
		}

		return '<div class="vstore-guide">' . e107::getForm()->tabs($tabs) . '</div>';
	}

	/**
	 * Sidebar help widget: caption, version, tagline, contextual tip, links.
	 *
	 * @return array caption/text pair expected by e_admin_ui
	 */
	public function renderHelp()
	{
		$info = $this->getPluginInfo();

		$mode   = strtolower($this->getMode());
		$action = strtolower($this->getAction());
		$tipKey = ($mode === 'main') ? 'PREFS' : strtoupper($mode);

		$tip = LAN_VSTORE_HELP_TIP;
		if(!in_array($action, array('about', 'guide'), true) && defined('LAN_VSTORE_HELP_TIP_' . $tipKey))
		{
			$tip = constant('LAN_VSTORE_HELP_TIP_' . $tipKey);
		}

		$e = function ($s)
		{
			return htmlspecialchars((string) $s, ENT_QUOTES);
		};

		$text  = '<p><strong>' . $e($info['name']) . '</strong> <span class="label label-info">v' . $e($info['version']) . '</span></p>';
		$text .= '<p class="text-muted"><small>' . LAN_VSTORE_HELP_TAGLINE . '</small></p>';
		$text .= '<div class="alert alert-info" style="margin-bottom:10px"><small>' . $tip . '</small></div>';
		$text .= '<a class="btn btn-default btn-block" href="' . $e($info['docs']) . '" target="_blank" rel="noopener"><i class="glyphicon glyphicon-book"></i> ' . LAN_VSTORE_BTN_DOCS . '</a>';
		$text .= '<a class="btn btn-default btn-block" href="' . $e($info['support']) . '" target="_blank" rel="noopener"><i class="glyphicon glyphicon-bug"></i> ' . LAN_VSTORE_BTN_SUPPORT . '</a>';
		$text .= '<a class="btn btn-warning btn-block" href="' . $e($info['donate']) . '" target="_blank" rel="noopener"><i class="glyphicon glyphicon-heart"></i> ' . LAN_VSTORE_BTN_DONATE . '</a>';
		$text .= '<hr /><p class="text-right"><a href="' . e_SELF . '?mode=main&amp;action=about">' . LAN_VSTORE_ADMIN_HELP_MORE . ' &raquo;</a></p>';

		return array('caption' => LAN_VSTORE_HELP_CAPTION, 'text' => $text);
	}
}
