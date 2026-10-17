<?php
/**
 * Vstore - PSR-4 autoloader for the Vstore\ namespace (src/).
 *
 * Independent from vendor/autoload.php so the business layer also loads when Composer is not installed.
 */

spl_autoload_register(function ($class)
{
	$prefix = 'Vstore\\';

	if (strncmp($class, $prefix, strlen($prefix)) !== 0)
	{
		return;
	}

	$file = __DIR__ . '/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';

	if (is_file($file))
	{
		require $file;
	}
});
