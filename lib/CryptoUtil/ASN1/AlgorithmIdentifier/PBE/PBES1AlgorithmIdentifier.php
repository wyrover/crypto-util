<?php

namespace CryptoUtil\ASN1\AlgorithmIdentifier\PBE;

use ASN1\Type\Constructed\Sequence;
use ASN1\Type\Primitive\Integer;
use ASN1\Type\Primitive\OctetString;
use ASN1\Type\UnspecifiedType;


/* @formatter:off *//*

From RFC 2898 - A.3 PBES1:

   For each OID, the parameters field associated with the OID in an
   AlgorithmIdentifier shall have type PBEParameter:

   PBEParameter ::= SEQUENCE {
       salt OCTET STRING (SIZE(8)),
       iterationCount INTEGER }

*//* @formatter:on */

/**
 * Base class for PBES1 encryption scheme.
 *
 * @link https://tools.ietf.org/html/rfc2898#section-6.1
 * @link https://tools.ietf.org/html/rfc2898#appendix-A.3
 */
abstract class PBES1AlgorithmIdentifier extends PBEAlgorithmIdentifier
{
	/**
	 * Constructor
	 *
	 * @param string $salt Salt
	 * @param int $iteration_count Iteration count
	 * @throws \UnexpectedValueException
	 */
	public function __construct($salt, $iteration_count) {
		if (strlen($salt) !== 8) {
			throw new \UnexpectedValueException("Salt length must be 8 octets.");
		}
		parent::__construct($salt, $iteration_count);
	}
	
	protected static function _fromASN1Params(UnspecifiedType $params = null) {
		if (!isset($params)) {
			throw new \UnexpectedValueException("No parameters.");
		}
		$seq = $params->asSequence();
		$salt = $seq->at(0)
			->asOctetString()
			->string();
		$iteration_count = $seq->at(1)
			->asInteger()
			->number();
		return new static($salt, $iteration_count);
	}
	
	protected function _paramsASN1() {
		return new Sequence(new OctetString($this->_salt), 
			new Integer($this->_iterationCount));
	}
}
