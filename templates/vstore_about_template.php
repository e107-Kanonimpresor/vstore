<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Based on e107inc/vstore (CaMerOn, Achim Ennenbach, e107 Inc). Modified by Kanonimpresor, 2025-2026.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */
if (!defined('e107_INIT')) { exit; }

/*
 * Admin "About" template (e107 array template).
 *
 * 4-layer pattern shared with sitedown_styles and booking:
 *   Layer 1 - Controller : inc/vstore_admin_common.php :: AboutPage()
 *   Layer 2 - Template   : THIS FILE, pure HTML (native Bootstrap 3 panels + css/vstore_admin.css, wrapper .vstore-about)
 *   Layer 3 - Languages  : languages/<Lang>/<Lang>_admin_about.php  ->  {LAN_PLUGIN_VSTORE_ABOUT_*}
 *   Layer 4 - Shortcodes : shortcodes/batch/vstore_about_shortcodes.php  ->  {VSTORE_ABOUT_*}
 *
 * {LAN_PLUGIN_VSTORE_ABOUT_*} are resolved by vstore_admin_common::_resolveLans() before parseTemplate().
 * Theme override: e107_themes/<theme>/templates/vstore/vstore_about_template.php
 */

$VSTORE_ABOUT_TEMPLATE['body'] = '
<div class="vstore-about">

	<div class="panel panel-primary">
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-8">
					<div class="vs-about-hero">
						<span class="vs-about-hero-icon"><i class="fas fa-store"></i></span>
						<div>
							<h3>{VSTORE_ABOUT_NAME} <span class="label label-primary" style="vertical-align:middle">v{VSTORE_ABOUT_VERSION}</span></h3>
							<p class="text-muted" style="margin:0">{LAN_PLUGIN_VSTORE_ABOUT_TAGLINE}</p>
						</div>
					</div>
				</div>
				<div class="col-sm-4 vs-about-meta">
					<i class="fas fa-calendar-alt"></i> {LAN_PLUGIN_VSTORE_ABOUT_LBL_RELEASED}: <strong>{VSTORE_ABOUT_RELEASED}</strong><br />
					<i class="fas fa-cube"></i> e107 {VSTORE_ABOUT_COMPAT}+
				</div>
			</div>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading"><i class="fas fa-info-circle"></i> {LAN_PLUGIN_VSTORE_ABOUT_DESCRIPTION}</div>
		<div class="panel-body">
			<p>{LAN_PLUGIN_VSTORE_ABOUT_INTRO}</p>
			<ul class="vs-about-features">
				<li><i class="fas fa-boxes"></i> {LAN_PLUGIN_VSTORE_ABOUT_FEAT1}</li>
				<li><i class="fas fa-file-signature"></i> {LAN_PLUGIN_VSTORE_ABOUT_FEAT2}</li>
				<li><i class="fas fa-shopping-basket"></i> {LAN_PLUGIN_VSTORE_ABOUT_FEAT3}</li>
				<li><i class="fas fa-percent"></i> {LAN_PLUGIN_VSTORE_ABOUT_FEAT4}</li>
				<li><i class="fas fa-credit-card"></i> {LAN_PLUGIN_VSTORE_ABOUT_FEAT5}</li>
				<li><i class="fas fa-file-invoice"></i> {LAN_PLUGIN_VSTORE_ABOUT_FEAT6}</li>
				<li><i class="fas fa-user-circle"></i> {LAN_PLUGIN_VSTORE_ABOUT_FEAT7}</li>
				<li><i class="fas fa-chart-bar"></i> {LAN_PLUGIN_VSTORE_ABOUT_FEAT8}</li>
			</ul>
			<p class="small text-muted" style="margin-bottom:0">
				<i class="fas fa-code-branch"></i> {LAN_PLUGIN_VSTORE_ABOUT_CHANGELOG_HINT}
				<a href="{VSTORE_ABOUT_CHANGELOG_URL}" target="_blank" rel="noopener">CHANGELOG.md</a>
			</p>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading"><i class="fas fa-id-card"></i> {LAN_PLUGIN_VSTORE_ABOUT_METADATA_TITLE}</div>
		<div class="panel-body">
			<table class="table table-bordered table-condensed" style="margin-bottom:0">
				{VSTORE_ABOUT_METADATA_ROWS}
			</table>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading"><i class="fas fa-heart"></i> {LAN_PLUGIN_VSTORE_ABOUT_CREDITS_TITLE}</div>
		<div class="panel-body">
			<p style="margin-bottom:0">{LAN_PLUGIN_VSTORE_ABOUT_CREDITS}</p>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading"><i class="fas fa-life-ring"></i> {LAN_PLUGIN_VSTORE_ABOUT_SUPPORT_TITLE}</div>
		<div class="panel-body">
			<p class="text-muted">{LAN_PLUGIN_VSTORE_ABOUT_SUPPORT_INTRO}</p>
			<p style="margin-bottom:0">{VSTORE_ABOUT_BUTTONS}</p>
		</div>
	</div>

	<div class="vs-about-footer">
		&copy; {VSTORE_ABOUT_YEAR} {VSTORE_ABOUT_AGENCY} &middot; {LAN_PLUGIN_VSTORE_ABOUT_RELEASED_UNDER}
		<a href="{VSTORE_ABOUT_LICENSE_URL}" target="_blank" rel="noopener">{VSTORE_ABOUT_LICENSE}</a>
	</div>

</div>';
