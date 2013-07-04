<?php
return array(
'VERSION' => '3.6.1.1',

'DB' => array(
	'host' => 'localhost',
	'user' => 'root',
	'password' => '123123',
	'dbname' => 'mom',
),

'TABLE_PRE_FIX' => 'mom_',

'UPLOAD_IMAGE' => array(
	'size' => 2000,
	'type' => array('.jpg','.png'),	
	'path' => 'source/',
),

'ADMIN_TPL' => array(
	1 => 'user',
	2 => 'product',
	3 => 'profile',
	4 => 'reply',
	5 => 'case',
),

'WEIBO_CONF' => array(
	'client_id' => '3811326615',
	'client_secret' => '142e1db6be74fd704fa8e6110d7cecc6',
	'grant_type' => 'authorization_code',
	'code' => isset($_GET['code']) ? $_GET['code'] : null,
	'redirect_uri' => 'http://loykay.sinaapp.com',
),

'IMAGE_PER_PAGE' => 20,

'TABLES_PER_PAGE' => 20,

);
