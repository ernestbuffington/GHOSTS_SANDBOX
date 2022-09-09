<?php

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
	exit('Access Denied');
}

function cache_delete( $name, $cat='config' ) 
{
	global $nuke_cache;
	return $nuke_cache->delete($name, $cat);
}

function cache_set( $name, $cat='config', $fileData )
{
	global $nuke_cache;
	return $nuke_cache->save($name, $cat, $fileData);
}

function cache_load($name, $cat='config')
{
	global $nuke_cache;
	return $nuke_cache->load($name, $cat);
}

function cache_clear()
{
	global $nuke_cache;
	$nuke_cache->clear();
}

function cache_resync()
{
	global $nuke_cache;
	$nuke_cache->resync();
}

?>