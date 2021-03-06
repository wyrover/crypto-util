<?php

namespace CryptoUtil\ASN1;

use ASN1\Type\Constructed\Sequence;
use ASN1\Type\Primitive\Integer;
use ASN1\Type\Primitive\OctetString;
use CryptoUtil\ASN1\AlgorithmIdentifier\Crypto\ECPublicKeyAlgorithmIdentifier;
use CryptoUtil\ASN1\EC\ECPrivateKey;
use CryptoUtil\ASN1\RSA\RSAPrivateKey;
use CryptoUtil\PEM\PEM;


/**
 * Implements PKCS #8 PrivateKeyInfo ASN.1 type.
 *
 * @link https://tools.ietf.org/html/rfc5208#section-5
 */
class PrivateKeyInfo
{
	/**
	 * Algorithm identifier.
	 *
	 * @var AlgorithmIdentifier $_algo
	 */
	protected $_algo;
	
	/**
	 * Private key data.
	 *
	 * @var string $_privateKey
	 */
	protected $_privateKeyData;
	
	/**
	 * Constructor
	 *
	 * @param AlgorithmIdentifier $algo Algorithm
	 * @param string $key Private key data
	 */
	public function __construct(AlgorithmIdentifier $algo, $key) {
		$this->_algo = $algo;
		$this->_privateKeyData = $key;
	}
	
	/**
	 * Initialize from ASN.1.
	 *
	 * @param Sequence $seq
	 * @throws \UnexpectedValueException
	 * @return self
	 */
	public static function fromASN1(Sequence $seq) {
		$version = $seq->at(0)
			->asInteger()
			->number();
		if ($version != 0) {
			throw new \UnexpectedValueException("Version must be 0.");
		}
		$algo = AlgorithmIdentifier::fromASN1($seq->at(1)->asSequence());
		$key = $seq->at(2)
			->asOctetString()
			->string();
		// @todo parse attributes
		return new self($algo, $key);
	}
	
	/**
	 * Initialize from DER data.
	 *
	 * @param string $data
	 * @return self
	 */
	public static function fromDER($data) {
		return self::fromASN1(Sequence::fromDER($data));
	}
	
	/**
	 * Initialize from PEM.
	 *
	 * @param PEM $pem
	 * @throws \UnexpectedValueException
	 * @return self
	 */
	public static function fromPEM(PEM $pem) {
		if ($pem->type() != PEM::TYPE_PRIVATE_KEY) {
			throw new \UnexpectedValueException("Invalid PEM type.");
		}
		return self::fromDER($pem->data());
	}
	
	/**
	 * Get algorithm identifier.
	 *
	 * @return AlgorithmIdentifier
	 */
	public function algorithmIdentifier() {
		return $this->_algo;
	}
	
	/**
	 * Get private key data.
	 *
	 * @return string
	 */
	public function privateKeyData() {
		return $this->_privateKeyData;
	}
	
	/**
	 * Get private key.
	 *
	 * @throws \RuntimeException
	 * @return PrivateKey
	 */
	public function privateKey() {
		$algo = $this->algorithmIdentifier();
		switch ($algo->oid()) {
		// RSA
		case AlgorithmIdentifier::OID_RSA_ENCRYPTION:
			return RSAPrivateKey::fromDER($this->_privateKeyData);
		// elliptic curve
		case AlgorithmIdentifier::OID_EC_PUBLIC_KEY:
			$pk = ECPrivateKey::fromDER($this->_privateKeyData);
			// if private key doesn't encode named curve, assign from parameters
			if (!$pk->hasNamedCurve()) {
				if (!$algo instanceof ECPublicKeyAlgorithmIdentifier) {
					throw new \UnexpectedValueException("Not an EC algorithm.");
				}
				$pk = $pk->withNamedCurve($algo->namedCurve());
			}
			return $pk;
		}
		throw new \RuntimeException(
			"Private key " . $algo->oid() . " not supported.");
	}
	
	/**
	 * Get public key info corresponding to the private key.
	 *
	 * @return PublicKeyInfo
	 */
	public function publicKeyInfo() {
		return $this->privateKey()
			->publicKey()
			->publicKeyInfo();
	}
	
	/**
	 * Generate ASN.1 structure.
	 *
	 * @return Sequence
	 */
	public function toASN1() {
		$elements = array(new Integer(0), $this->_algo->toASN1(), 
			new OctetString($this->_privateKeyData));
		return new Sequence(...$elements);
	}
	
	/**
	 * Generate DER encoding.
	 *
	 * @return string
	 */
	public function toDER() {
		return $this->toASN1()->toDER();
	}
	
	/**
	 * Generate PEM.
	 *
	 * @return PEM
	 */
	public function toPEM() {
		return new PEM(PEM::TYPE_PRIVATE_KEY, $this->toDER());
	}
}
