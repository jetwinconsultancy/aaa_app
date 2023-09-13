<?php 
	// this is a function that generate random alphanumeric string.
	function random_code($limit)
	{
	    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
	}
?>