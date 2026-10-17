<?php
if (!defined('e107_INIT')) { exit; }

// {LAN_PLUGIN_VSTORE_ABOUT_*} are resolved by vstore_admin_common::_resolveLans(); {VSTORE_ABOUT_*} by the shortcode batch.

$VSTORE_ABOUT_TEMPLATE['body'] = '
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">{LAN_PLUGIN_VSTORE_ABOUT_TITLE}</h3>
	</div>
	<div class="panel-body">
		<p class="lead">{LAN_PLUGIN_VSTORE_ABOUT_TAGLINE}</p>
		<p>{LAN_PLUGIN_VSTORE_ABOUT_INTRO}</p>

		<h4>{LAN_PLUGIN_VSTORE_ABOUT_METADATA_TITLE}</h4>
		<table class="table table-bordered">
			{VSTORE_ABOUT_METADATA_ROWS}
		</table>

		<h4>{LAN_PLUGIN_VSTORE_ABOUT_CREDITS_TITLE}</h4>
		<p>{LAN_PLUGIN_VSTORE_ABOUT_CREDITS}</p>

		{VSTORE_ABOUT_BUTTONS}

		<hr />
		<p class="text-muted text-center">
			<small>&copy; {VSTORE_ABOUT_YEAR} {VSTORE_ABOUT_AGENCY} &middot;
			<a href="{VSTORE_ABOUT_LICENSE_URL}" target="_blank" rel="noopener">{VSTORE_ABOUT_LICENSE}</a></small>
		</p>
	</div>
</div>';
