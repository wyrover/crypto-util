<?php

namespace CryptoUtil\ASN1\AlgorithmIdentifier\Hash;


/**
 * SHA-256 algorithm identifier.
 *
 * @link http://oid-info.com/get/2.16.840.1.101.3.4.2.1
 * @link https://tools.ietf.org/html/rfc4055#section-2.1
 * @link https://tools.ietf.org/html/rfc5754#section-2.2
 */
class SHA256AlgorithmIdentifier extends SHA2AlgorithmIdentifier
{
	public function __construct() {
		$this->_oid = self::OID_SHA256;
		parent::__construct();
	}
	
	public function name() {
		return "sha256";
	}
}
