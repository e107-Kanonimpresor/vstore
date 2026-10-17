<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Based on e107inc/vstore (CaMerOn, Achim Ennenbach, e107 Inc). Modified by Kanonimpresor, 2025-2026.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */
if (!defined('e107_INIT')) { exit; }

/**
 * Guide shortcodes - intentional stub.
 *
 * LAN tokens are substituted by vstore_admin_common::_resolveLans() before parseTemplate() runs.
 * Add only shortcodes with real logic here. Never use __call(): e107 checks method_exists().
 */
class plugin_vstore_vstore_guide_shortcodes extends e_shortcode
{
}
