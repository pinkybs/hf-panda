<?php

define('APP_DIR', ROOT_DIR . DIRECTORY_SEPARATOR . 'app');
define('CONFIG_DIR', APP_DIR . DIRECTORY_SEPARATOR . 'config');
define('MODULES_DIR', APP_DIR . DIRECTORY_SEPARATOR . 'modules');
define('LIB_DIR', ROOT_DIR . DIRECTORY_SEPARATOR . 'lib');
define('DOC_DIR', ROOT_DIR . DIRECTORY_SEPARATOR . 'www');
define('LOG_DIR', '/home/admin/logs/ipanda/debug');
define('TEMP_DIR', ROOT_DIR . DIRECTORY_SEPARATOR . 'temp');
define('SMARTY_TEMPLATES_C', ROOT_DIR . DIRECTORY_SEPARATOR . 'templates_c');

define('ENABLE_DEBUG', true);

define('SERVER_ID', '1002');

define('APP_ID', '12367754');
define('APP_KEY', '12367754');
define('APP_SECRET', '1f253c85e045a0071763187a531be74d');
define('APP_NAME', 'ipanda');
define('SHOP_OWNER_ID', '770763594');

define('DATABASE_NODE_NUM', 8);
define('MEMCACHED_NODE_NUM', 10);
define('USE_CACHE', 1);

define('HOST', 'http://tbipanda.hapyfish.com');
define('STATIC_HOST', 'http://tbipandastatic.hapyfish.com');

define('SEND_ACTIVITY', true);
define('SEND_MESSAGE', true);

define('APP_STATUS', 1);
define('APP_STATUS_DEV', 1); //1 正服  2 测服 3 开发服 4 本地开发

define('ECODE_NUM', 4);