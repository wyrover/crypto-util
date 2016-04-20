<?php

namespace CryptoUtil\PEM;


class PEMBundle implements \Countable, \IteratorAggregate
{
	/**
	 * PEMs
	 *
	 * @var PEM[] $_pems
	 */
	protected $_pems;
	
	/**
	 * Constructor
	 *
	 * @param PEM ...$pems
	 */
	public function __construct(PEM ...$pems) {
		$this->_pems = $pems;
	}
	
	/**
	 * Initialize from string
	 *
	 * @param string $str
	 * @throws \InvalidArgumentException
	 * @return self
	 */
	public static function fromString($str) {
		if (!preg_match_all(PEM::PEM_REGEX, $str, $matches, PREG_SET_ORDER)) {
			throw new \InvalidArgumentException("No PEM blocks");
		}
		$pems = array_map(
			function ($match) {
				$payload = preg_replace('/\s+/', "", $match[2]);
				$data = base64_decode($payload, true);
				if (false === $data) {
					throw new \InvalidArgumentException(
						"Failed to decode PEM data");
				}
				return new PEM($match[1], $data);
			}, $matches);
		return new self(...$pems);
	}
	
	/**
	 * Initialize from file
	 *
	 * @param string $filename
	 * @throws \InvalidArgumentException
	 * @return self
	 */
	public static function fromFile($filename) {
		if (!is_readable($filename)) {
			throw new \InvalidArgumentException("$filename is not readable");
		}
		$str = file_get_contents($filename);
		if ($str === false) {
			throw new \InvalidArgumentException("Failed to read $filename");
		}
		return self::fromString($str);
	}
	
	/**
	 * Get all PEMs in a bundle
	 *
	 * @return PEM[]
	 */
	public function all() {
		return $this->_pems;
	}
	
	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see Countable::count()
	 */
	public function count() {
		return count($this->_pems);
	}
	
	/**
	 * Get iterator for PEMs
	 *
	 * @see IteratorAggregate::getIterator()
	 * @return \ArrayIterator|PEM[]
	 */
	public function getIterator() {
		return new \ArrayIterator($this->_pems);
	}
}
