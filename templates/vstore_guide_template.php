<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Based on e107inc/vstore (CaMerOn, Achim Ennenbach, e107 Inc). Modified by Kanonimpresor, 2025-2026.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */
if (!defined('e107_INIT')) { exit; }

/*
 * Admin "User Guide" template (e107 array template), one key per tab.
 *
 * Architecture: 4-layer pattern shared with sitedown_styles and booking.
 *   Layer 1 - Controller : inc/vstore_admin_common.php :: GuidePage()
 *   Layer 2 - Template   : THIS FILE, pure HTML. Classes come from css/vstore_admin.css (wrapper .vstore-guide).
 *   Layer 3 - Languages  : languages/<Lang>/<Lang>_admin_help.php, plain text with inline <code>/<strong> only.
 *   Layer 4 - Shortcodes : shortcodes/batch/vstore_guide_shortcodes.php (dynamic data only).
 *
 * {LAN_VSTORE_GUIDE_<SECTION>_*} tokens are resolved by vstore_admin_common::_resolveLans() before parseTemplate().
 * {LAN_VSTORE_STATUS_*} (order status names from the global language file) are resolved in the same pre-pass.
 * The tab caption is LAN_VSTORE_GUIDE_<SECTION>_TAB (section = key in upper case).
 * Theme override: e107_themes/<theme>/templates/vstore/vstore_guide_template.php
 */

$VSTORE_GUIDE_TEMPLATE['overview'] = '
<div class="bg-overview-header">
	<h4><i class="fas fa-store"></i> {LAN_VSTORE_GUIDE_OVERVIEW_TITLE}</h4>
	<p class="bg-overview-intro">{LAN_VSTORE_GUIDE_OVERVIEW_INTRO}</p>
</div>

<div class="bg-flow-bar">
	<div class="bg-flow-step"><span class="bg-flow-num">1</span><span class="bg-flow-label">{LAN_VSTORE_GUIDE_OVERVIEW_FLOW1}</span></div>
	<span class="bg-flow-arrow">&rsaquo;</span>
	<div class="bg-flow-step"><span class="bg-flow-num">2</span><span class="bg-flow-label">{LAN_VSTORE_GUIDE_OVERVIEW_FLOW2}</span></div>
	<span class="bg-flow-arrow">&rsaquo;</span>
	<div class="bg-flow-step"><span class="bg-flow-num">3</span><span class="bg-flow-label">{LAN_VSTORE_GUIDE_OVERVIEW_FLOW3}</span></div>
	<span class="bg-flow-arrow">&rsaquo;</span>
	<div class="bg-flow-step"><span class="bg-flow-num">4</span><span class="bg-flow-label">{LAN_VSTORE_GUIDE_OVERVIEW_FLOW4}</span></div>
	<span class="bg-flow-arrow">&rsaquo;</span>
	<div class="bg-flow-step"><span class="bg-flow-num">5</span><span class="bg-flow-label">{LAN_VSTORE_GUIDE_OVERVIEW_FLOW5}</span></div>
</div>

<h5 class="bg-section-title"><i class="fas fa-th-large"></i> {LAN_VSTORE_GUIDE_OVERVIEW_FEAT_H}</h5>
<div class="bg-features-grid">
	<div class="bg-feature-card"><i class="fas fa-boxes bg-feature-icon"></i><strong>{LAN_VSTORE_GUIDE_OVERVIEW_F1_T}</strong><span>{LAN_VSTORE_GUIDE_OVERVIEW_F1_D}</span></div>
	<div class="bg-feature-card"><i class="fas fa-file-signature bg-feature-icon"></i><strong>{LAN_VSTORE_GUIDE_OVERVIEW_F2_T}</strong><span>{LAN_VSTORE_GUIDE_OVERVIEW_F2_D}</span></div>
	<div class="bg-feature-card"><i class="fas fa-shopping-basket bg-feature-icon"></i><strong>{LAN_VSTORE_GUIDE_OVERVIEW_F3_T}</strong><span>{LAN_VSTORE_GUIDE_OVERVIEW_F3_D}</span></div>
	<div class="bg-feature-card"><i class="fas fa-percent bg-feature-icon"></i><strong>{LAN_VSTORE_GUIDE_OVERVIEW_F4_T}</strong><span>{LAN_VSTORE_GUIDE_OVERVIEW_F4_D}</span></div>
	<div class="bg-feature-card"><i class="fas fa-credit-card bg-feature-icon"></i><strong>{LAN_VSTORE_GUIDE_OVERVIEW_F5_T}</strong><span>{LAN_VSTORE_GUIDE_OVERVIEW_F5_D}</span></div>
	<div class="bg-feature-card"><i class="fas fa-file-invoice bg-feature-icon"></i><strong>{LAN_VSTORE_GUIDE_OVERVIEW_F6_T}</strong><span>{LAN_VSTORE_GUIDE_OVERVIEW_F6_D}</span></div>
	<div class="bg-feature-card"><i class="fas fa-user-circle bg-feature-icon"></i><strong>{LAN_VSTORE_GUIDE_OVERVIEW_F7_T}</strong><span>{LAN_VSTORE_GUIDE_OVERVIEW_F7_D}</span></div>
	<div class="bg-feature-card"><i class="fas fa-chart-bar bg-feature-icon"></i><strong>{LAN_VSTORE_GUIDE_OVERVIEW_F8_T}</strong><span>{LAN_VSTORE_GUIDE_OVERVIEW_F8_D}</span></div>
</div>

<h5 class="bg-section-title"><i class="fas fa-map-signs"></i> {LAN_VSTORE_GUIDE_OVERVIEW_WHERE_H}</h5>
<table class="table table-striped table-condensed">
	<tbody>
		<tr><th>{LAN_VSTORE_GUIDE_OVERVIEW_W1_N}</th><td>{LAN_VSTORE_GUIDE_OVERVIEW_W1_D}</td></tr>
		<tr><th>{LAN_VSTORE_GUIDE_OVERVIEW_W2_N}</th><td>{LAN_VSTORE_GUIDE_OVERVIEW_W2_D}</td></tr>
		<tr><th>{LAN_VSTORE_GUIDE_OVERVIEW_W3_N}</th><td>{LAN_VSTORE_GUIDE_OVERVIEW_W3_D}</td></tr>
		<tr><th>{LAN_VSTORE_GUIDE_OVERVIEW_W4_N}</th><td>{LAN_VSTORE_GUIDE_OVERVIEW_W4_D}</td></tr>
		<tr><th>{LAN_VSTORE_GUIDE_OVERVIEW_W5_N}</th><td>{LAN_VSTORE_GUIDE_OVERVIEW_W5_D}</td></tr>
		<tr><th>{LAN_VSTORE_GUIDE_OVERVIEW_W6_N}</th><td>{LAN_VSTORE_GUIDE_OVERVIEW_W6_D}</td></tr>
	</tbody>
</table>
<div class="alert alert-info"><i class="fas fa-lightbulb"></i> {LAN_VSTORE_GUIDE_OVERVIEW_TIP}</div>';

$VSTORE_GUIDE_TEMPLATE['start'] = '
<div class="bg-overview-header">
	<h4><i class="fas fa-rocket"></i> {LAN_VSTORE_GUIDE_START_TITLE}</h4>
	<p class="bg-overview-intro">{LAN_VSTORE_GUIDE_START_INTRO}</p>
</div>
<ol class="bg-steps">
	<li><strong>{LAN_VSTORE_GUIDE_START_S1_T}</strong><span>{LAN_VSTORE_GUIDE_START_S1_D}</span></li>
	<li><strong>{LAN_VSTORE_GUIDE_START_S2_T}</strong><span>{LAN_VSTORE_GUIDE_START_S2_D}</span></li>
	<li><strong>{LAN_VSTORE_GUIDE_START_S3_T}</strong><span>{LAN_VSTORE_GUIDE_START_S3_D}</span></li>
	<li><strong>{LAN_VSTORE_GUIDE_START_S4_T}</strong><span>{LAN_VSTORE_GUIDE_START_S4_D}</span></li>
	<li><strong>{LAN_VSTORE_GUIDE_START_S5_T}</strong><span>{LAN_VSTORE_GUIDE_START_S5_D}</span></li>
	<li><strong>{LAN_VSTORE_GUIDE_START_S6_T}</strong><span>{LAN_VSTORE_GUIDE_START_S6_D}</span></li>
</ol>
<div class="alert alert-info"><i class="fas fa-link"></i> {LAN_VSTORE_GUIDE_START_NOTE}</div>';

$VSTORE_GUIDE_TEMPLATE['catalog'] = '
<div class="bg-overview-header">
	<h4><i class="fas fa-boxes"></i> {LAN_VSTORE_GUIDE_CATALOG_TITLE}</h4>
	<p class="bg-overview-intro">{LAN_VSTORE_GUIDE_CATALOG_INTRO}</p>
</div>
<div class="bg-features-grid">
	<div class="bg-feature-card"><i class="fas fa-sitemap bg-feature-icon"></i><strong>{LAN_VSTORE_GUIDE_CATALOG_CAT_H}</strong><span>{LAN_VSTORE_GUIDE_CATALOG_CAT_T}</span></div>
	<div class="bg-feature-card"><i class="fas fa-box-open bg-feature-icon"></i><strong>{LAN_VSTORE_GUIDE_CATALOG_PROD_H}</strong><span>{LAN_VSTORE_GUIDE_CATALOG_PROD_T}</span></div>
	<div class="bg-feature-card"><i class="fas fa-gifts bg-feature-icon"></i><strong>{LAN_VSTORE_GUIDE_CATALOG_VAR_H}</strong><span>{LAN_VSTORE_GUIDE_CATALOG_VAR_T}</span></div>
</div>

<h5 class="bg-section-title"><i class="fas fa-images"></i> {LAN_VSTORE_GUIDE_CATALOG_IMG_H}</h5>
<p>{LAN_VSTORE_GUIDE_CATALOG_IMG_T}</p>
<ul>
	<li>{LAN_VSTORE_GUIDE_CATALOG_IMG_L1}</li>
	<li>{LAN_VSTORE_GUIDE_CATALOG_IMG_L2}</li>
	<li>{LAN_VSTORE_GUIDE_CATALOG_IMG_L3}</li>
</ul>

<h5 class="bg-section-title"><i class="fas fa-tag"></i> {LAN_VSTORE_GUIDE_CATALOG_PRICE_H}</h5>
<p>{LAN_VSTORE_GUIDE_CATALOG_PRICE_T}</p>
<table class="table table-striped table-condensed">
	<tbody>
		<tr><th>{LAN_VSTORE_GUIDE_CATALOG_P1_N}</th><td>{LAN_VSTORE_GUIDE_CATALOG_P1_D}</td></tr>
		<tr><th>{LAN_VSTORE_GUIDE_CATALOG_P2_N}</th><td>{LAN_VSTORE_GUIDE_CATALOG_P2_D}</td></tr>
		<tr><th>{LAN_VSTORE_GUIDE_CATALOG_P3_N}</th><td>{LAN_VSTORE_GUIDE_CATALOG_P3_D}</td></tr>
	</tbody>
</table>';

$VSTORE_GUIDE_TEMPLATE['quotes'] = '
<div class="bg-overview-header">
	<h4><i class="fas fa-file-signature"></i> {LAN_VSTORE_GUIDE_QUOTES_TITLE}</h4>
	<p class="bg-overview-intro">{LAN_VSTORE_GUIDE_QUOTES_INTRO}</p>
</div>

<div class="bg-flow-bar">
	<div class="bg-flow-step"><span class="bg-flow-num">Q</span><span class="bg-flow-label">{LAN_VSTORE_STATUS_Q}</span></div>
	<span class="bg-flow-arrow">&rsaquo;</span>
	<div class="bg-flow-step"><span class="bg-flow-num">M</span><span class="bg-flow-label">{LAN_VSTORE_STATUS_M}</span></div>
	<span class="bg-flow-arrow">&rsaquo;</span>
	<div class="bg-flow-step"><span class="bg-flow-num">A</span><span class="bg-flow-label">{LAN_VSTORE_STATUS_A}</span></div>
	<span class="bg-flow-arrow">&rsaquo;</span>
	<div class="bg-flow-step"><span class="bg-flow-num">F</span><span class="bg-flow-label">{LAN_VSTORE_STATUS_F}</span></div>
</div>

<h5 class="bg-section-title"><i class="fas fa-route"></i> {LAN_VSTORE_GUIDE_QUOTES_HOW_H}</h5>
<div class="bg-examples-grid">
	<div class="bg-example-card"><i class="fas fa-user bg-example-icon"></i><strong>{LAN_VSTORE_GUIDE_QUOTES_E1_T}</strong><p>{LAN_VSTORE_GUIDE_QUOTES_E1_D}</p></div>
	<div class="bg-example-card bg-accent-success"><i class="fas fa-store bg-example-icon"></i><strong>{LAN_VSTORE_GUIDE_QUOTES_E2_T}</strong><p>{LAN_VSTORE_GUIDE_QUOTES_E2_D}</p></div>
	<div class="bg-example-card bg-accent-warning"><i class="fas fa-file-invoice-dollar bg-example-icon"></i><strong>{LAN_VSTORE_GUIDE_QUOTES_E3_T}</strong><p>{LAN_VSTORE_GUIDE_QUOTES_E3_D}</p></div>
</div>

<h5 class="bg-section-title"><i class="fas fa-shopping-basket"></i> {LAN_VSTORE_GUIDE_QUOTES_MIX_H}</h5>
<p>{LAN_VSTORE_GUIDE_QUOTES_MIX_T}</p>

<h5 class="bg-section-title"><i class="fas fa-clipboard-check"></i> {LAN_VSTORE_GUIDE_QUOTES_RULES_H}</h5>
<ul>
	<li>{LAN_VSTORE_GUIDE_QUOTES_R1}</li>
	<li>{LAN_VSTORE_GUIDE_QUOTES_R2}</li>
	<li>{LAN_VSTORE_GUIDE_QUOTES_R3}</li>
	<li>{LAN_VSTORE_GUIDE_QUOTES_R4}</li>
	<li>{LAN_VSTORE_GUIDE_QUOTES_R5}</li>
</ul>
<div class="alert alert-info"><i class="fas fa-lightbulb"></i> {LAN_VSTORE_GUIDE_QUOTES_NOTE}</div>';

$VSTORE_GUIDE_TEMPLATE['coupons'] = '
<div class="bg-overview-header">
	<h4><i class="fas fa-tags"></i> {LAN_VSTORE_GUIDE_COUPONS_TITLE}</h4>
	<p class="bg-overview-intro">{LAN_VSTORE_GUIDE_COUPONS_INTRO}</p>
</div>
<div class="bg-features-grid">
	<div class="bg-feature-card"><i class="fas fa-percent bg-feature-icon"></i><strong>{LAN_VSTORE_GUIDE_COUPONS_TYPES_H}</strong><span>{LAN_VSTORE_GUIDE_COUPONS_TYPES_T}</span></div>
	<div class="bg-feature-card"><i class="fas fa-filter bg-feature-icon"></i><strong>{LAN_VSTORE_GUIDE_COUPONS_RULES_H}</strong><span>{LAN_VSTORE_GUIDE_COUPONS_RULES_T}</span></div>
	<div class="bg-feature-card"><i class="fas fa-hashtag bg-feature-icon"></i><strong>{LAN_VSTORE_GUIDE_COUPONS_LIMITS_H}</strong><span>{LAN_VSTORE_GUIDE_COUPONS_LIMITS_T}</span></div>
</div>
<div class="alert alert-info"><i class="fas fa-lightbulb"></i> {LAN_VSTORE_GUIDE_COUPONS_NOTE}</div>';

$VSTORE_GUIDE_TEMPLATE['tax'] = '
<div class="bg-overview-header">
	<h4><i class="fas fa-percent"></i> {LAN_VSTORE_GUIDE_TAX_TITLE}</h4>
	<p class="bg-overview-intro">{LAN_VSTORE_GUIDE_TAX_INTRO}</p>
</div>
<div class="bg-features-grid">
	<div class="bg-feature-card"><i class="fas fa-cog bg-feature-icon"></i><strong>{LAN_VSTORE_GUIDE_TAX_SETUP_H}</strong><span>{LAN_VSTORE_GUIDE_TAX_SETUP_T}</span></div>
	<div class="bg-feature-card"><i class="fas fa-layer-group bg-feature-icon"></i><strong>{LAN_VSTORE_GUIDE_TAX_CLASSES_H}</strong><span>{LAN_VSTORE_GUIDE_TAX_CLASSES_T}</span></div>
</div>

<h5 class="bg-section-title"><i class="fas fa-globe-europe"></i> {LAN_VSTORE_GUIDE_TAX_SCEN_H}</h5>
<table class="table table-striped table-condensed">
	<thead><tr><th>{LAN_VSTORE_GUIDE_TAX_COL_WHO}</th><th>{LAN_VSTORE_GUIDE_TAX_COL_RATE}</th></tr></thead>
	<tbody>
		<tr><td>{LAN_VSTORE_GUIDE_TAX_SC1_W}</td><td>{LAN_VSTORE_GUIDE_TAX_SC1_R}</td></tr>
		<tr><td>{LAN_VSTORE_GUIDE_TAX_SC2_W}</td><td>{LAN_VSTORE_GUIDE_TAX_SC2_R}</td></tr>
		<tr><td>{LAN_VSTORE_GUIDE_TAX_SC3_W}</td><td>{LAN_VSTORE_GUIDE_TAX_SC3_R}</td></tr>
		<tr><td>{LAN_VSTORE_GUIDE_TAX_SC4_W}</td><td>{LAN_VSTORE_GUIDE_TAX_SC4_R}</td></tr>
	</tbody>
</table>
<div class="alert alert-info"><i class="fas fa-lightbulb"></i> {LAN_VSTORE_GUIDE_TAX_NOTE}</div>';

$VSTORE_GUIDE_TEMPLATE['ship'] = '
<div class="bg-overview-header">
	<h4><i class="fas fa-truck"></i> {LAN_VSTORE_GUIDE_SHIP_TITLE}</h4>
	<p class="bg-overview-intro">{LAN_VSTORE_GUIDE_SHIP_INTRO}</p>
</div>
<div class="bg-examples-grid">
	<div class="bg-example-card"><i class="fas fa-plus-circle bg-example-icon"></i><strong>{LAN_VSTORE_GUIDE_SHIP_M1_T}</strong><p>{LAN_VSTORE_GUIDE_SHIP_M1_D}</p></div>
	<div class="bg-example-card"><i class="fas fa-clone bg-example-icon"></i><strong>{LAN_VSTORE_GUIDE_SHIP_M2_T}</strong><p>{LAN_VSTORE_GUIDE_SHIP_M2_D}</p></div>
	<div class="bg-example-card"><i class="fas fa-stream bg-example-icon"></i><strong>{LAN_VSTORE_GUIDE_SHIP_M3_T}</strong><p>{LAN_VSTORE_GUIDE_SHIP_M3_D}</p></div>
</div>
<div class="alert alert-info"><i class="fas fa-lightbulb"></i> {LAN_VSTORE_GUIDE_SHIP_NOTE}</div>';

$VSTORE_GUIDE_TEMPLATE['gate'] = '
<div class="bg-overview-header">
	<h4><i class="fas fa-credit-card"></i> {LAN_VSTORE_GUIDE_GATE_TITLE}</h4>
	<p class="bg-overview-intro">{LAN_VSTORE_GUIDE_GATE_INTRO}</p>
</div>
<div class="bg-examples-grid">
	<div class="bg-example-card"><i class="fab fa-paypal bg-example-icon"></i><strong>{LAN_VSTORE_GUIDE_GATE_G1_T}</strong><p>{LAN_VSTORE_GUIDE_GATE_G1_D}</p></div>
	<div class="bg-example-card"><i class="fas fa-wallet bg-example-icon"></i><strong>{LAN_VSTORE_GUIDE_GATE_G2_T}</strong><p>{LAN_VSTORE_GUIDE_GATE_G2_D}</p></div>
	<div class="bg-example-card"><i class="fas fa-university bg-example-icon"></i><strong>{LAN_VSTORE_GUIDE_GATE_G3_T}</strong><p>{LAN_VSTORE_GUIDE_GATE_G3_D}</p></div>
</div>
<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> {LAN_VSTORE_GUIDE_GATE_NOTE}</div>';

$VSTORE_GUIDE_TEMPLATE['orders'] = '
<div class="bg-overview-header">
	<h4><i class="fas fa-shopping-cart"></i> {LAN_VSTORE_GUIDE_ORDERS_TITLE}</h4>
	<p class="bg-overview-intro">{LAN_VSTORE_GUIDE_ORDERS_INTRO}</p>
</div>

<h5 class="bg-section-title"><i class="fas fa-tasks"></i> {LAN_VSTORE_GUIDE_ORDERS_ST_H}</h5>
<p>{LAN_VSTORE_GUIDE_ORDERS_ST_T}</p>
<table class="table table-striped table-condensed bg-status-table">
	<tbody>
		<tr><td><span class="label label-default">N</span> {LAN_VSTORE_STATUS_N}</td><td>{LAN_VSTORE_GUIDE_ORDERS_SN}</td></tr>
		<tr><td><span class="label label-info">P</span> {LAN_VSTORE_STATUS_P}</td><td>{LAN_VSTORE_GUIDE_ORDERS_SP}</td></tr>
		<tr><td><span class="label label-warning">H</span> {LAN_VSTORE_STATUS_H}</td><td>{LAN_VSTORE_GUIDE_ORDERS_SH}</td></tr>
		<tr><td><span class="label label-success">C</span> {LAN_VSTORE_STATUS_C}</td><td>{LAN_VSTORE_GUIDE_ORDERS_SC}</td></tr>
		<tr><td><span class="label label-danger">X</span> {LAN_VSTORE_STATUS_X}</td><td>{LAN_VSTORE_GUIDE_ORDERS_SX}</td></tr>
		<tr><td><span class="label label-danger">R</span> {LAN_VSTORE_STATUS_R}</td><td>{LAN_VSTORE_GUIDE_ORDERS_SR}</td></tr>
		<tr><td><span class="label label-primary">Q</span> {LAN_VSTORE_STATUS_Q}</td><td>{LAN_VSTORE_GUIDE_ORDERS_SQ}</td></tr>
		<tr><td><span class="label label-primary">M</span> {LAN_VSTORE_STATUS_M}</td><td>{LAN_VSTORE_GUIDE_ORDERS_SM}</td></tr>
		<tr><td><span class="label label-primary">A</span> {LAN_VSTORE_STATUS_A}</td><td>{LAN_VSTORE_GUIDE_ORDERS_SA}</td></tr>
		<tr><td><span class="label label-primary">F</span> {LAN_VSTORE_STATUS_F}</td><td>{LAN_VSTORE_GUIDE_ORDERS_SF}</td></tr>
	</tbody>
</table>

<div class="bg-features-grid">
	<div class="bg-feature-card"><i class="fas fa-envelope bg-feature-icon"></i><strong>{LAN_VSTORE_GUIDE_ORDERS_EM_H}</strong><span>{LAN_VSTORE_GUIDE_ORDERS_EM_T}</span></div>
	<div class="bg-feature-card"><i class="fas fa-file-pdf bg-feature-icon"></i><strong>{LAN_VSTORE_GUIDE_ORDERS_INV_H}</strong><span>{LAN_VSTORE_GUIDE_ORDERS_INV_T}</span></div>
	<div class="bg-feature-card"><i class="fas fa-chart-bar bg-feature-icon"></i><strong>{LAN_VSTORE_GUIDE_ORDERS_STAT_H}</strong><span>{LAN_VSTORE_GUIDE_ORDERS_STAT_T}</span></div>
	<div class="bg-feature-card"><i class="fas fa-user-circle bg-feature-icon"></i><strong>{LAN_VSTORE_GUIDE_ORDERS_DASH_H}</strong><span>{LAN_VSTORE_GUIDE_ORDERS_DASH_T}</span></div>
</div>';

$VSTORE_GUIDE_TEMPLATE['look'] = '
<div class="bg-overview-header">
	<h4><i class="fas fa-paint-brush"></i> {LAN_VSTORE_GUIDE_LOOK_TITLE}</h4>
	<p class="bg-overview-intro">{LAN_VSTORE_GUIDE_LOOK_INTRO}</p>
</div>

<h5 class="bg-section-title"><i class="fas fa-palette"></i> {LAN_VSTORE_GUIDE_LOOK_CSS_H}</h5>
<p>{LAN_VSTORE_GUIDE_LOOK_CSS_T}</p>
<code class="bg-path">.vstore { --vs-accent: #0d6efd; --vs-radius: 1.15rem; }</code>

<h5 class="bg-section-title"><i class="fas fa-puzzle-piece"></i> {LAN_VSTORE_GUIDE_LOOK_TPL_H}</h5>
<p>{LAN_VSTORE_GUIDE_LOOK_TPL_T}</p>
<code class="bg-path">e107_themes/&lt;theme&gt;/templates/vstore/vstore_template.php</code>

<h5 class="bg-section-title"><i class="fas fa-columns"></i> {LAN_VSTORE_GUIDE_LOOK_MENU_H}</h5>
<ul>
	<li>{LAN_VSTORE_GUIDE_LOOK_MENU1}</li>
	<li>{LAN_VSTORE_GUIDE_LOOK_MENU2}</li>
	<li>{LAN_VSTORE_GUIDE_LOOK_MENU3}</li>
	<li>{LAN_VSTORE_GUIDE_LOOK_MENU4}</li>
</ul>

<h5 class="bg-section-title"><i class="fas fa-sliders-h"></i> {LAN_VSTORE_GUIDE_LOOK_SHOW_H}</h5>
<p>{LAN_VSTORE_GUIDE_LOOK_SHOW_T}</p>
<table class="table table-striped table-condensed">
	<tbody>
		<tr><th>{LAN_VSTORE_GUIDE_LOOK_SHOW_O1_N}</th><td>{LAN_VSTORE_GUIDE_LOOK_SHOW_O1_D}</td></tr>
		<tr><th>{LAN_VSTORE_GUIDE_LOOK_SHOW_O2_N}</th><td>{LAN_VSTORE_GUIDE_LOOK_SHOW_O2_D}</td></tr>
		<tr><th>{LAN_VSTORE_GUIDE_LOOK_SHOW_O3_N}</th><td>{LAN_VSTORE_GUIDE_LOOK_SHOW_O3_D}</td></tr>
		<tr><th>{LAN_VSTORE_GUIDE_LOOK_SHOW_O4_N}</th><td>{LAN_VSTORE_GUIDE_LOOK_SHOW_O4_D}</td></tr>
		<tr><th>{LAN_VSTORE_GUIDE_LOOK_SHOW_O5_N}</th><td>{LAN_VSTORE_GUIDE_LOOK_SHOW_O5_D}</td></tr>
	</tbody>
</table>
<div class="alert alert-info"><i class="fas fa-lightbulb"></i> {LAN_VSTORE_GUIDE_LOOK_NOTE}</div>';

$VSTORE_GUIDE_TEMPLATE['trouble'] = '
<div class="bg-overview-header">
	<h4><i class="fas fa-life-ring"></i> {LAN_VSTORE_GUIDE_TROUBLE_TITLE}</h4>
	<p class="bg-overview-intro">{LAN_VSTORE_GUIDE_TROUBLE_INTRO}</p>
</div>
<dl>
	<dt>{LAN_VSTORE_GUIDE_TROUBLE_Q1}</dt><dd>{LAN_VSTORE_GUIDE_TROUBLE_A1}</dd>
	<dt>{LAN_VSTORE_GUIDE_TROUBLE_Q2}</dt><dd>{LAN_VSTORE_GUIDE_TROUBLE_A2}</dd>
	<dt>{LAN_VSTORE_GUIDE_TROUBLE_Q3}</dt><dd>{LAN_VSTORE_GUIDE_TROUBLE_A3}</dd>
	<dt>{LAN_VSTORE_GUIDE_TROUBLE_Q4}</dt><dd>{LAN_VSTORE_GUIDE_TROUBLE_A4}</dd>
	<dt>{LAN_VSTORE_GUIDE_TROUBLE_Q5}</dt><dd>{LAN_VSTORE_GUIDE_TROUBLE_A5}</dd>
	<dt>{LAN_VSTORE_GUIDE_TROUBLE_Q6}</dt><dd>{LAN_VSTORE_GUIDE_TROUBLE_A6}</dd>
</dl>';

$VSTORE_GUIDE_TEMPLATE['credits'] = '
<div class="bg-overview-header">
	<h4><i class="fas fa-heart"></i> {LAN_VSTORE_GUIDE_CREDITS_TITLE}</h4>
	<p class="bg-overview-intro">{LAN_VSTORE_GUIDE_CREDITS_INTRO}</p>
</div>

<h5 class="bg-section-title"><i class="fas fa-user"></i> {LAN_VSTORE_GUIDE_CREDITS_AUTHOR_H}</h5>
<p>{LAN_VSTORE_GUIDE_CREDITS_AUTHOR_T}</p>

<h5 class="bg-section-title"><i class="fas fa-cubes"></i> {LAN_VSTORE_GUIDE_CREDITS_TECH_H}</h5>
<div class="bg-features-grid">
	<div class="bg-feature-card"><i class="fas fa-server bg-feature-icon"></i><strong>e107 CMS</strong><span>{LAN_VSTORE_GUIDE_CREDITS_TECH_E107}</span></div>
	<div class="bg-feature-card"><i class="fas fa-plug bg-feature-icon"></i><strong>Omnipay</strong><span>{LAN_VSTORE_GUIDE_CREDITS_TECH_OMNIPAY}</span></div>
	<div class="bg-feature-card"><i class="fas fa-file-pdf bg-feature-icon"></i><strong>TCPDF</strong><span>{LAN_VSTORE_GUIDE_CREDITS_TECH_TCPDF}</span></div>
	<div class="bg-feature-card"><i class="fas fa-globe-europe bg-feature-icon"></i><strong>ibericode/vat</strong><span>{LAN_VSTORE_GUIDE_CREDITS_TECH_VAT}</span></div>
	<div class="bg-feature-card"><i class="fab fa-bootstrap bg-feature-icon"></i><strong>Bootstrap</strong><span>{LAN_VSTORE_GUIDE_CREDITS_TECH_BS}</span></div>
	<div class="bg-feature-card"><i class="fas fa-flag bg-feature-icon"></i><strong>Font Awesome</strong><span>{LAN_VSTORE_GUIDE_CREDITS_TECH_FA}</span></div>
</div>

<h5 class="bg-section-title"><i class="fas fa-handshake"></i> {LAN_VSTORE_GUIDE_CREDITS_THANKS_H}</h5>
<ul>
	<li>{LAN_VSTORE_GUIDE_CREDITS_THANKS_ORIG}</li>
	<li>{LAN_VSTORE_GUIDE_CREDITS_THANKS_E107}</li>
	<li>{LAN_VSTORE_GUIDE_CREDITS_THANKS_TRANS}</li>
</ul>

<div class="alert alert-info"><i class="fas fa-balance-scale"></i> {LAN_VSTORE_GUIDE_CREDITS_LICENSE}</div>

<p class="text-muted">{LAN_VSTORE_GUIDE_CREDITS_INVOLVED}</p>';
