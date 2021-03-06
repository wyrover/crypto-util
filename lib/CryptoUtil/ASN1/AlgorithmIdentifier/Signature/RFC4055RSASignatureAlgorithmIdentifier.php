<?php

namespace CryptoUtil\ASN1\AlgorithmIdentifier\Signature;

use ASN1\Element;
use ASN1\Type\Primitive\NullType;
use ASN1\Type\UnspecifiedType;


/* @formatter:off *//*

From RFC 4055 - 5.  PKCS #1 Version 1.5 Signature Algorithm

   When any of these four object identifiers appears within an
   AlgorithmIdentifier, the parameters MUST be NULL.  Implementations
   MUST accept the parameters being absent as well as present.

*//* @formatter:on */

/**
 *
 * @link https://tools.ietf.org/html/rfc4055#section-5
 */
abstract class RFC4055RSASignatureAlgorithmIdentifier extends RSASignatureAlgorithmIdentifier
{
	/**
	 * Parameters.
	 *
	 * @var Element|null $_params
	 */
	protected $_params;
	
	public function __construct() {
		$this->_params = new NullType();
	}
	
	protected static function _fromASN1Params(UnspecifiedType $params = null) {
		$obj = new static();
		// store parameters so re-encoding doesn't change
		if (isset($params)) {
			$obj->_params = $params->asElement();
		}
		return $obj;
	}
	
	protected function _paramsASN1() {
		return $this->_params;
	}
}
