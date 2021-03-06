<?php

namespace CryptoUtil\ASN1\AlgorithmIdentifier\Signature;


/**
 *
 * @link https://tools.ietf.org/html/rfc5758#section-3.2
 */
class ECDSAWithSHA256AlgorithmIdentifier extends ECSignatureAlgorithmIdentifier
{
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->_oid = self::OID_ECDSA_WITH_SHA256;
	}
	
	/**
	 *
	 * @return string
	 */
	public function name() {
		return "ecdsa-with-SHA256";
	}
}
