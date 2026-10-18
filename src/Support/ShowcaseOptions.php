<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Copyright (c) 2026 Kanonimpresor.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Vstore\Support;

/**
 * Parameters of the product showcase menu (vstore_showcase_menu.php), read from the Menu Manager.
 */
final class ShowcaseOptions
{
        const ORDERS = array('auto', 'random', 'newest', 'name', 'price_asc', 'price_desc', 'manual');

        /** pills = filter in place, links = pills that open the category page, none = no pills */
        const NAVS = array('pills', 'links', 'none');

        const DEFAULT_LIMIT = 6;
        const MAX_LIMIT = 24;
        const MAX_COLUMNS = 4;

        /**
         * Menu parameters come as array, JSON or one key=value per line, depending on how they were saved.
         *
         * @param mixed $raw
         * @return array
         */
        public static function parse($raw)
        {
                if(is_array($raw))
                {
                        return $raw;
                }

                $raw = is_string($raw) ? trim($raw) : '';

                if($raw === '')
                {
                        return array();
                }

                if($raw[0] === '{' || $raw[0] === '[')
                {
                        $decoded = json_decode($raw, true);

                        if(is_array($decoded))
                        {
                                return $decoded;
                        }
                }

                parse_str(str_replace(array("\r\n", "\n", "\r", ';'), '&', $raw), $parms);

                return is_array($parms) ? $parms : array();
        }

        /**
         * @param array $parms
         * @return array caption, category (0 = all), limit, columns (0 = automatic), order, nav
         */
        public static function normalize(array $parms)
        {
                $limit = isset($parms['limit']) ? (int) $parms['limit'] : self::DEFAULT_LIMIT;
                $limit = $limit < 1 ? self::DEFAULT_LIMIT : min($limit, self::MAX_LIMIT);

                $columns = 3;

                if(isset($parms['columns']))
                {
                        $columns = ($parms['columns'] === 'auto' || $parms['columns'] === '') ? 0 : (int) $parms['columns'];
                        $columns = max(0, min($columns, self::MAX_COLUMNS));
                }

                $order = isset($parms['order']) ? (string) $parms['order'] : 'auto';
                $nav = isset($parms['nav']) ? (string) $parms['nav'] : 'pills';

                return array(
                        'caption'  => isset($parms['caption']) ? trim((string) $parms['caption']) : '',
                        'category' => isset($parms['category']) ? max(0, (int) $parms['category']) : 0,
                        'limit'    => $limit,
                        'columns'  => $columns,
                        'order'    => in_array($order, self::ORDERS, true) ? $order : 'auto',
                        'nav'      => in_array($nav, self::NAVS, true) ? $nav : 'pills',
                );
        }

        /**
         * ORDER BY of the product query (table alias i). Never built from user input.
         *
         * @param string $order one of ORDERS
         * @param bool $allCategories automatic order: random for all categories, manual order inside one category
         * @return string
         */
        public static function orderSql($order, $allCategories)
        {
                if($order === 'auto')
                {
                        $order = $allCategories ? 'random' : 'manual';
                }

                switch($order)
                {
                        case 'random':
                                return 'RAND()';
                        case 'newest':
                                return 'i.item_id DESC';
                        case 'name':
                                return 'i.item_name ASC';
                        case 'price_asc':
                                return '(i.item_price IS NULL) ASC, i.item_price ASC';
                        case 'price_desc':
                                return '(i.item_price IS NULL) ASC, i.item_price DESC';
                        default:
                                return 'i.item_order ASC, i.item_id ASC';
                }
        }
}
