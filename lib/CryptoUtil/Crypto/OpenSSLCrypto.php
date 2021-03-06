<?php

namespace CryptoUtil\Crypto;

use CryptoUtil\ASN1\AlgorithmIdentifier\Cipher\CipherAlgorithmIdentifier;
use CryptoUtil\ASN1\AlgorithmIdentifier\Feature\SignatureAlgorithmIdentifier;
use CryptoUtil\ASN1\AlgorithmIdentifier;
use CryptoUtil\ASN1\AlgorithmIdentifier\Cipher\RC2CBCAlgorithmIdentifier;
use CryptoUtil\ASN1\PrivateKeyInfo;
use CryptoUtil\ASN1\PublicKeyInfo;


/**
 * Crypto engine using OpenSSL extension.
 */
class OpenSSLCrypto extends Crypto
{
	/**
	 *
	 * @see \CryptoUtil\Crypto\Crypto::sign()
	 */
	public function sign($data, PrivateKeyInfo $privkey_info, 
			SignatureAlgorithmIdentifier $algo) {
		$this->_checkSignatureAlgoAndKey($algo, 
			$privkey_info->algorithmIdentifier());
		$result = openssl_sign($data, $signature, $privkey_info->toPEM(), 
			$this->_algoToDigest($algo));
		if (false === $result) {
			throw new \RuntimeException(
				"openssl_sign() failed: " . $this->_getLastError());
		}
		return new Signature($signature);
	}
	
	/**
	 *
	 * @see \CryptoUtil\Crypto\Crypto::verify()
	 */
	public function verify($data, Signature $signature, 
			PublicKeyInfo $pubkey_info, SignatureAlgorithmIdentifier $algo) {
		$this->_checkSignatureAlgoAndKey($algo, 
			$pubkey_info->algorithmIdentifier());
		$result = openssl_verify($data, $signature->octets(), 
			$pubkey_info->toPEM(), $this->_algoToDigest($algo));
		if (-1 == $result) {
			throw new \RuntimeException(
				"openssl_verify() failed: " . $this->_getLastError());
		}
		return 1 == $result ? true : false;
	}
	
	/**
	 *
	 * @see \CryptoUtil\Crypto\Crypto::encrypt()
	 */
	public function encrypt($data, $key, CipherAlgorithmIdentifier $algo) {
		$iv = $algo->initializationVector();
		$result = openssl_encrypt($data, $this->_algoToCipher($algo), $key, 
			OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
		if (false === $result) {
			throw new \RuntimeException(
				"openssl_encrypt() failed: " . $this->_getLastError());
		}
		return $result;
	}
	
	/**
	 *
	 * @see \CryptoUtil\Crypto\Crypto::decrypt()
	 */
	public function decrypt($data, $key, CipherAlgorithmIdentifier $algo) {
		$iv = $algo->initializationVector();
		$result = openssl_decrypt($data, $this->_algoToCipher($algo), $key, 
			OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
		if (false === $result) {
			throw new \RuntimeException(
				"openssl_decrypt() failed: " . $this->_getLastError());
		}
		return $result;
	}
	
	/**
	 * Get last OpenSSL error message.
	 *
	 * @return string|null
	 */
	protected function _getLastError() {
		// pump error message queue
		$msg = null;
		while (false !== ($err = openssl_error_string())) {
			$msg = $err;
		}
		return $msg;
	}
	
	/**
	 * Check that given signature algorithm supports key of given type.
	 *
	 * @param SignatureAlgorithmIdentifier $sig_algo Signature algorithm
	 * @param AlgorithmIdentifier $key_algo Key algorithm
	 * @throws \UnexpectedValueException If key is not supported
	 */
	protected function _checkSignatureAlgoAndKey(
			SignatureAlgorithmIdentifier $sig_algo, 
			AlgorithmIdentifier $key_algo) {
		if (!$sig_algo->supportsKeyAlgorithm($key_algo)) {
			throw new \UnexpectedValueException(
				"Signature algorithm '" . $sig_algo->name() .
					 "' does not support key algorithm '" . $key_algo->name() .
					 "'.");
		}
	}
	
	/**
	 * Mapping from algorithm OID to OpenSSL digest method name.
	 *
	 * @internal
	 *
	 * @var array
	 */
	const MAP_DIGEST_OID_TO_NAME = array(
		/* @formatter:off */
		AlgorithmIdentifier::OID_MD4_WITH_RSA_ENCRYPTION => "md4WithRSAEncryption",
		AlgorithmIdentifier::OID_MD5_WITH_RSA_ENCRYPTION => "md5WithRSAEncryption",
		AlgorithmIdentifier::OID_SHA1_WITH_RSA_ENCRYPTION => "sha1WithRSAEncryption",
		AlgorithmIdentifier::OID_SHA224_WITH_RSA_ENCRYPTION => "sha224WithRSAEncryption",
		AlgorithmIdentifier::OID_SHA256_WITH_RSA_ENCRYPTION => "sha256WithRSAEncryption",
		AlgorithmIdentifier::OID_SHA384_WITH_RSA_ENCRYPTION => "sha384WithRSAEncryption",
		AlgorithmIdentifier::OID_SHA512_WITH_RSA_ENCRYPTION => "sha512WithRSAEncryption",
		AlgorithmIdentifier::OID_ECDSA_WITH_SHA1 => "ecdsa-with-SHA1",
		AlgorithmIdentifier::OID_ECDSA_WITH_SHA224 => "sha224",
		AlgorithmIdentifier::OID_ECDSA_WITH_SHA256 => "sha256",
		AlgorithmIdentifier::OID_ECDSA_WITH_SHA384 => "sha384",
		AlgorithmIdentifier::OID_ECDSA_WITH_SHA512 => "sha512"
		/* @formatter:on */
	);
	
	/**
	 * Get OpenSSL digest method for given signature algorithm identifier.
	 *
	 * @param SignatureAlgorithmIdentifier $algo
	 * @throws \UnexpectedValueException
	 * @return string
	 */
	protected function _algoToDigest(SignatureAlgorithmIdentifier $algo) {
		$oid = $algo->oid();
		if (!array_key_exists($oid, self::MAP_DIGEST_OID_TO_NAME)) {
			throw new \UnexpectedValueException(
				"Digest method $oid not supported.");
		}
		return self::MAP_DIGEST_OID_TO_NAME[$oid];
	}
	
	/**
	 * Mapping from algorithm OID to OpenSSL cipher method name.
	 *
	 * @internal
	 *
	 * @var array
	 */
	const MAP_CIPHER_OID_TO_NAME = array(
		/* @formatter:off */
		AlgorithmIdentifier::OID_DES_CBC => "DES-CBC", 
		AlgorithmIdentifier::OID_DES_EDE3_CBC => "DES-EDE3-CBC",
		AlgorithmIdentifier::OID_AES_128_CBC => "AES-128-CBC",
		AlgorithmIdentifier::OID_AES_192_CBC => "AES-192-CBC",
		AlgorithmIdentifier::OID_AES_256_CBC => "AES-256-CBC"
		/* @formatter:on */
	);
	
	/**
	 * Get OpenSSL cipher method for given cipher algorithm identifier.
	 *
	 * @param CipherAlgorithmIdentifier $algo
	 * @throws \UnexpectedValueException
	 * @return string
	 */
	protected function _algoToCipher(CipherAlgorithmIdentifier $algo) {
		$oid = $algo->oid();
		if (array_key_exists($oid, self::MAP_CIPHER_OID_TO_NAME)) {
			return self::MAP_CIPHER_OID_TO_NAME[$oid];
		}
		if (AlgorithmIdentifier::OID_RC2_CBC == $oid) {
			if (!$algo instanceof RC2CBCAlgorithmIdentifier) {
				throw new \UnexpectedValueException("Not an RC2-CBC algorithm.");
			}
			return $this->_rc2AlgoToCipher($algo);
		}
		throw new \UnexpectedValueException(
			"Cipher method " . $algo->oid() . " not supported.");
	}
	
	/**
	 * Get OpenSSL cipher method for given RC2 algorithm identifier.
	 *
	 * @param RC2CBCAlgorithmIdentifier $algo
	 * @throws \UnexpectedValueException
	 * @return string
	 */
	protected function _rc2AlgoToCipher(RC2CBCAlgorithmIdentifier $algo) {
		switch ($algo->effectiveKeyBits()) {
		case 128:
			return "RC2-CBC";
		case 64:
			return "RC2-64-CBC";
		case 40:
			return "RC2-40-CBC";
		}
		throw new \UnexpectedValueException(
			$algo->effectiveKeyBits() . " bit RC2 not supported.");
	}
}
