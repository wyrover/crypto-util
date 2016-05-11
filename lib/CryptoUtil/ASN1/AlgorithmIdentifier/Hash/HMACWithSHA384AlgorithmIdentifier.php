<?php

namespace CryptoUtil\ASN1\AlgorithmIdentifier\Hash;


/**
 * HMAC with SHA-384 algorithm identifier.
 *
 * @link https://tools.ietf.org/html/rfc4231#section-3.1
 */
class HMACWithSHA384AlgorithmIdentifier extends RFC4231HMACAlgorithmIdentifier
{
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->_oid = self::OID_HMAC_WITH_SHA384;
	}
}