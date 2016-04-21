<?php

namespace CryptoUtil\ASN1\AlgorithmIdentifier\Signature;

use CryptoUtil\ASN1\AlgorithmIdentifier\SpecificAlgorithmIdentifier;
use CryptoUtil\ASN1\AlgorithmIdentifier\Feature\SignatureAlgorithmIdentifier;
use ASN1\Element;


/* @formatter:off *//*

From RFC 5758 - 3.2.  ECDSA Signature Algorithm

   When the ecdsa-with-SHA224, ecdsa-with-SHA256, ecdsa-with-SHA384, or
   ecdsa-with-SHA512 algorithm identifier appears in the algorithm field
   as an AlgorithmIdentifier, the encoding MUST omit the parameters
   field.

*//* @formatter:on */

/**
 *
 * @link https://tools.ietf.org/html/rfc5758#section-3.2
 * @link https://tools.ietf.org/html/rfc5480#appendix-A
 */
abstract class ECSignatureAlgorithmIdentifier extends SpecificAlgorithmIdentifier implements 
	SignatureAlgorithmIdentifier
{
	protected static function _fromASN1Params(Element $params = null) {
		return new static();
	}
	
	protected function _paramsASN1() {
		return null;
	}
}