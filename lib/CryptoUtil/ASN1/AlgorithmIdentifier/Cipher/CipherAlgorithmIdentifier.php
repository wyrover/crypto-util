<?php

namespace CryptoUtil\ASN1\AlgorithmIdentifier\Cipher;

use CryptoUtil\ASN1\AlgorithmIdentifier\SpecificAlgorithmIdentifier;


/**
 * Base class for cipher algorithm identifiers.
 */
abstract class CipherAlgorithmIdentifier extends SpecificAlgorithmIdentifier
{
	/**
	 * Initialization vector.
	 *
	 * @var string|null $_initializationVector
	 */
	protected $_initializationVector;
	
	/**
	 * Get key size in bytes.
	 *
	 * @return int
	 */
	abstract public function keySize();
	
	/**
	 * Get the initialization vector size in bytes.
	 *
	 * @return int
	 */
	abstract public function ivSize();
	
	/**
	 * Get initialization vector.
	 *
	 * @return string|null
	 */
	public function initializationVector() {
		return $this->_initializationVector;
	}
	
	/**
	 * Get copy of the object with given initialization vector.
	 *
	 * @param string|null $iv Initialization vector or null to remove
	 * @throws \UnexpectedValueException If initialization vector size is
	 *         invalid
	 * @return self
	 */
	public function withInitializationVector($iv) {
		$this->_checkIVSize($iv);
		$obj = clone $this;
		$obj->_initializationVector = $iv;
		return $obj;
	}
	
	/**
	 * Check that initialization vector size is valid for the cipher.
	 *
	 * @param string|null $iv
	 * @throws \UnexpectedValueException
	 */
	protected function _checkIVSize($iv) {
		if (null !== $iv && strlen($iv) != $this->ivSize()) {
			throw new \UnexpectedValueException("Invalid IV size.");
		}
	}
}
