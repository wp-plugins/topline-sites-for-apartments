<?php 

function startsWith($haystack, $needles)
{
	foreach ((array) $needles as $needle)
	{
		if ($needle != '' && strpos($haystack, $needle) === 0) return true;
	}

	return false;
}

function dd($var) {
	var_dump($var);
	exit;
}