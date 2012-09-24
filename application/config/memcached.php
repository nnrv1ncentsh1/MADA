<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$memcached['servers'] = array(
	'lilith' => array(
		'host' => '172.17.0.46',
		'port' => '11211',
		'weight' => '1',
		'persistent' => FALSE
	)
	/*
	'net50101' => array(
		'host' => '172.17.0.30',
		'port' => '11211',
		'weight' => '1',
		'persistent' => FALSE
	),
	'net50102' => array(
		'host' => '172.17.0.30',
		'port' => '11212',
		'weight' => '1',
		'persistent' => FALSE
	)
 */
);

$memcached['config'] = array(
	'prefix' => '' , // Prefixes every key value (useful for multi environment setups)
	'compression' => FALSE , // Default: FALSE or MEMCACHE_COMPRESSED Compression Method (Memcache only).
	// Not necessary if you already are using 'compression'
	'auto_compress_tresh' => FALSE , // Controls the minimum value length before attempting to compress automatically.
	'auto_compress_savings' => 0.2 , // Specifies the minimum amount of savings to actually store the value compressed. The supplied value must be between 0 and 1.
	'expiration' => 3600 , // Default content expiration value (in seconds)
	'delete_expiration' => 0 // Default time between the delete command and the actual delete action occurs (in seconds) 
);

$config['memcached'] = $memcached;
/* End of file memcached.php */
/* Location: ./system/application/config/memcached.php */
//$this->load->library('Memcached_library','','memcached');
//$this->memcached->delete('test');	
//$this->memcached->add('test','memcached test');
//echo $this->memcached->get('test');	


