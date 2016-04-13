<?php

namespace CryptoUtil\ASN1;

use ASN1\Element;
use ASN1\Type\Constructed\Sequence;


/**
 * Implements X.509 SubjectPublicKeyInfo ASN.1 type.
 *
 * @link https://tools.ietf.org/html/rfc5280#section-4.1
 */
class PublicKeyInfo
{
	/**
	 * Algorithm
	 *
	 * @var AlgorithmIdentifier $_algo
	 */
	protected $_algo;
	
	/**
	 * Public key data
	 *
	 * @var string $_publicKey
	 */
	protected $_publicKey;
	
	/**
	 * Constructor
	 *
	 * @param AlgorithmIdentifier $algo Algorithm
	 * @param string $key Public key data
	 */
	public function __construct(AlgorithmIdentifier $algo, $key) {
		$this->_algo = $algo;
		$this->_publicKey = $key;
	}
	
	/**
	 * Initialize from ASN.1
	 *
	 * @param Sequence $seq
	 * @return self
	 */
	public static function fromASN1(Sequence $seq) {
		$algo = AlgorithmIdentifier::fromASN1(
			$seq->at(0, Element::TYPE_SEQUENCE));
		$key = $seq->at(1, Element::TYPE_STRING)->str();
		return new self($algo, $key);
	}
	
	/**
	 * Initialize from DER data
	 *
	 * @param string $data
	 * @return self
	 */
	public static function fromDER($data) {
		return self::fromASN1(Sequence::fromDER($data));
	}
	
	/**
	 * Get algorithm
	 *
	 * @return AlgorithmIdentifier
	 */
	public function algorithmIdentifier() {
		return $this->_algo;
	}
	
	/**
	 * Get public key data
	 *
	 * @return string
	 */
	public function publicKeyData() {
		return $this->_publicKey;
	}
}
