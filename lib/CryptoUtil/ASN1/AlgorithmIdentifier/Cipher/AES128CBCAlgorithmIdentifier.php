<?php

namespace CryptoUtil\ASN1\AlgorithmIdentifier\Cipher;


/**
 * Algorithm identifier for AES with 128-bit key in CBC mode.
 *
 * @link https://tools.ietf.org/html/rfc3565.html#section-4.1
 * @link http://www.alvestrand.no/objectid/2.16.840.1.101.3.4.1.2.html
 * @link http://www.oid-info.com/get/2.16.840.1.101.3.4.1.2
 */
class AES128CBCAlgorithmIdentifier extends AESCBCAlgorithmIdentifier
{
	/**
	 * Constructor.
	 *
	 * @param string|null $iv Initialization vector
	 */
	public function __construct($iv = null) {
		$this->_oid = self::OID_AES_128_CBC;
		parent::__construct($iv);
	}
	
	/**
	 *
	 * @return string
	 */
	public function name() {
		return "aes128-CBC";
	}
	
	/**
	 *
	 * @return int
	 */
	public function keySize() {
		return 16;
	}
}
