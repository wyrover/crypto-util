<?php

namespace CryptoUtil\ASN1\AlgorithmIdentifier\Signature;

use CryptoUtil\ASN1\AlgorithmIdentifier;
use CryptoUtil\ASN1\AlgorithmIdentifier\Feature\HashAlgorithmIdentifier;
use CryptoUtil\ASN1\AlgorithmIdentifier\Feature\SignatureAlgorithmIdentifier;


/**
 * Factory class for constructing signature algorithm identifiers.
 */
abstract class SignatureAlgorithmIdentifierFactory
{
	/**
	 * Mapping of hash algorithm OID's to RSA signature algorithm OID's.
	 *
	 * @internal
	 *
	 * @var array
	 */
	const MAP_RSA_OID = array(
		/* @formatter:off */
		AlgorithmIdentifier::OID_MD5 => AlgorithmIdentifier::OID_MD5_WITH_RSA_ENCRYPTION,
		AlgorithmIdentifier::OID_SHA1 => AlgorithmIdentifier::OID_SHA1_WITH_RSA_ENCRYPTION,
		AlgorithmIdentifier::OID_SHA224 => AlgorithmIdentifier::OID_SHA224_WITH_RSA_ENCRYPTION,
		AlgorithmIdentifier::OID_SHA256 => AlgorithmIdentifier::OID_SHA256_WITH_RSA_ENCRYPTION,
		AlgorithmIdentifier::OID_SHA384 => AlgorithmIdentifier::OID_SHA384_WITH_RSA_ENCRYPTION,
		AlgorithmIdentifier::OID_SHA512 => AlgorithmIdentifier::OID_SHA512_WITH_RSA_ENCRYPTION
		/* @formatter:on */
	);
	
	/**
	 * Mapping of hash algorithm OID's to EC signature algorithm OID's.
	 *
	 * @internal
	 *
	 * @var array
	 */
	const MAP_EC_OID = array(
		/* @formatter:off */
		AlgorithmIdentifier::OID_SHA1 => AlgorithmIdentifier::OID_ECDSA_WITH_SHA1,
		AlgorithmIdentifier::OID_SHA224 => AlgorithmIdentifier::OID_ECDSA_WITH_SHA224,
		AlgorithmIdentifier::OID_SHA256 => AlgorithmIdentifier::OID_ECDSA_WITH_SHA256,
		AlgorithmIdentifier::OID_SHA384 => AlgorithmIdentifier::OID_ECDSA_WITH_SHA384,
		AlgorithmIdentifier::OID_SHA512 => AlgorithmIdentifier::OID_ECDSA_WITH_SHA512
		/* @formatter:on */
	);
	
	/**
	 * Get signature algorithm identifier of given asymmetric cryptographic type
	 * utilizing given hash algorithm.
	 *
	 * @param AlgorithmIdentifier $crypto_algo Cryptographic algorithm
	 *        identifier, eg. RSA or EC
	 * @param HashAlgorithmIdentifier $hash_algo Hash algorithm identifier
	 * @throws \UnexpectedValueException
	 * @return SignatureAlgorithmIdentifier
	 */
	public static function algoForAsymmetricCrypto(
			AlgorithmIdentifier $crypto_algo, HashAlgorithmIdentifier $hash_algo) {
		switch ($crypto_algo->oid()) {
		case AlgorithmIdentifier::OID_RSA_ENCRYPTION:
			$oid = self::_oidForRSA($hash_algo);
			break;
		case AlgorithmIdentifier::OID_EC_PUBLIC_KEY:
			$oid = self::_oidForEC($hash_algo);
			break;
		default:
			throw new \UnexpectedValueException(
				"Crypto algorithm " . $crypto_algo->name() . " not supported.");
		}
		$cls = AlgorithmIdentifier::MAP_OID_TO_CLASS[$oid];
		return new $cls();
	}
	
	/**
	 * Get RSA signature algorithm OID for the given hash algorithm identifier.
	 *
	 * @param HashAlgorithmIdentifier $hash_algo
	 * @throws \UnexpectedValueException
	 * @return string
	 */
	private static function _oidForRSA(HashAlgorithmIdentifier $hash_algo) {
		if (!array_key_exists($hash_algo->oid(), self::MAP_RSA_OID)) {
			throw new \UnexpectedValueException(
				"No RSA signature algorithm for " . $hash_algo->name() . ".");
		}
		return self::MAP_RSA_OID[$hash_algo->oid()];
	}
	
	/**
	 * Get EC signature algorithm OID for the given hash algorithm identifier.
	 *
	 * @param HashAlgorithmIdentifier $hash_algo
	 * @throws \UnexpectedValueException
	 * @return string
	 */
	private static function _oidForEC(HashAlgorithmIdentifier $hash_algo) {
		if (!array_key_exists($hash_algo->oid(), self::MAP_EC_OID)) {
			throw new \UnexpectedValueException(
				"No EC signature algorithm for " . $hash_algo->name() . ".");
		}
		return self::MAP_EC_OID[$hash_algo->oid()];
	}
}