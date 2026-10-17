<?php

namespace Vstore\Support;

/**
 * Outcome of a business operation. Services never print messages: they return a code
 * (matching LAN_VSTORE_MSG_<CODE>) plus the sprintf() parameters, and the presentation layer translates it.
 */
final class Result
{
	const ERROR   = 'error';
	const WARNING = 'warning';

	/** @var mixed */
	private $value;

	/** @var string */
	private $code;

	/** @var array */
	private $params;

	/** @var string */
	private $severity;

	private function __construct($value, $code, array $params, $severity)
	{
		$this->value    = $value;
		$this->code     = $code;
		$this->params   = $params;
		$this->severity = $severity;
	}

	public static function ok($value = null)
	{
		return new self($value, '', array(), '');
	}

	public static function fail($code, array $params = array(), $value = null)
	{
		return new self($value, $code, $params, self::ERROR);
	}

	/** Operation succeeded but the user should be informed. */
	public static function warn($code, array $params = array(), $value = null)
	{
		return new self($value, $code, $params, self::WARNING);
	}

	public function isOk()
	{
		return $this->severity !== self::ERROR;
	}

	public function hasMessage()
	{
		return $this->code !== '';
	}

	public function value()
	{
		return $this->value;
	}

	public function code()
	{
		return $this->code;
	}

	public function params()
	{
		return $this->params;
	}

	public function severity()
	{
		return $this->severity;
	}
}
