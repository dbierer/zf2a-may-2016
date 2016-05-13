<?php
/**
 * MvcLogEvent Module
 *
 * @package MvcLogEvent Module
 * @author Andrew Caya
 * @link https://github.com/andrewscaya
 * @version 1.0.0
 * @license http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

return [
    'service_manager' => [
        'factories' => [
            'log' => 'MvcLogEvent\Service\LogFactory',
        ],
        'services' => [
            'params' => [
                'hits' => 3,
                'log' => realpath(__DIR__ . '/../../../data/logs') . DIRECTORY_SEPARATOR . 'items_viewed.log',
            ],
        ],
    ],
];
