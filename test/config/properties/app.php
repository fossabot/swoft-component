<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
return [
    'version'      => '1.0',
    'autoInitBean' => true,
    'beanScan'     => [
        'SwoftTest\\Db\\Testing' => SRC_PATH . '/db/test/Testing',
        'Swoft\\Db'              => SRC_PATH . '/db/src',
        'SwoftTest\\Aop'         => SRC_PATH . '/framework/test/Cases/Aop',
        'SwoftTest\\Bean'        => SRC_PATH . '/framework/test/Cases/Bean',
        'SwoftTest\\Pool'        => SRC_PATH . '/framework/test/Cases/Pool',
    ],
    'env'          => 'Base',
    'db'           => require __DIR__ . DS . 'db.php',
    'provider'     => require __DIR__ . DS . 'provider.php',
];
