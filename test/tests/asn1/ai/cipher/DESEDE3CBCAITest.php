<?php

use CryptoUtil\ASN1\AlgorithmIdentifier;
use CryptoUtil\ASN1\AlgorithmIdentifier\Cipher\DESEDE3CBCAlgorithmIdentifier;
use ASN1\Type\Constructed\Sequence;


/**
 * @group asn1
 * @group algo-id
 */
class DESEDE3CBCAITest extends PHPUnit_Framework_TestCase
{
	const IV = "12345678";
	
	public function testEncode() {
		$ai = new DESEDE3CBCAlgorithmIdentifier(self::IV);
		$seq = $ai->toASN1();
		$this->assertInstanceOf(Sequence::class, $seq);
		return $seq;
	}
	
	/**
	 * @depends testEncode
	 *
	 * @param Sequence $seq
	 */
	public function testDecode(Sequence $seq) {
		$ai = AlgorithmIdentifier::fromASN1($seq);
		$this->assertInstanceOf(DESEDE3CBCAlgorithmIdentifier::class, $ai);
		return $ai;
	}
	
	/**
	 * @depends testDecode
	 *
	 * @param DESEDE3CBCAlgorithmIdentifier $ai
	 */
	public function testIV(DESEDE3CBCAlgorithmIdentifier $ai) {
		$this->assertEquals(self::IV, $ai->initializationVector());
	}
}