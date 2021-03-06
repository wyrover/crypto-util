<?php

namespace CryptoUtil\ASN1\AlgorithmIdentifier\Hash;


/**
 * HMAC with SHA-224 algorithm identifier.
 *
 * @link https://tools.ietf.org/html/rfc4231#section-3.1
 */
class HMACWithSHA224AlgorithmIdentifier extends RFC4231HMACAlgorithmIdentifier
{
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->_oid = self::OID_HMAC_WITH_SHA224;
	}
	
	/**
	 *
	 * @return string
	 */
	public function name() {
		return "hmacWithSHA224";
	}
}
