[![Build Status](https://travis-ci.org/sop/crypto-util.svg?branch=master)](https://travis-ci.org/sop/crypto-util)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sop/crypto-util/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sop/crypto-util/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/sop/crypto-util/badge.svg?branch=master)](https://coveralls.io/github/sop/crypto-util?branch=master)
[![License](https://poser.pugx.org/sop/crypto-util/license)](https://github.com/sop/crypto-util/blob/master/LICENSE)

# CryptoUtil
A PHP library containing various utilities for cryptographic applications.

## Features
* PEM encoding/decoding
* ASN.1 types for asymmetric keys
    * [RSAPrivateKey](https://tools.ietf.org/html/rfc2437#section-11.1.2),
        [RSAPublicKey](https://tools.ietf.org/html/rfc2437#section-11.1.1)
    * [ECPrivateKey](https://tools.ietf.org/html/rfc5915#section-3),
        [ECPublicKey](https://tools.ietf.org/html/rfc5480#section-2.2)
    * [PrivateKeyInfo](https://tools.ietf.org/html/rfc5208#section-5)
        ([PKCS #8](https://tools.ietf.org/html/rfc5208))
    * [EncryptedPrivateKeyInfo](https://tools.ietf.org/html/rfc5208#section-6)
        ([PKCS #8](https://tools.ietf.org/html/rfc5208))
    * [SubjectPublicKeyInfo](https://tools.ietf.org/html/rfc5280#section-4.1)
        ([X.509](https://tools.ietf.org/html/rfc5280))
* Password-Based Cryptography ([PKCS #5](https://tools.ietf.org/html/rfc2898))
    * Encrypt/decrypt EncryptedPrivateKeyInfo
* Various algorithm identifiers

## Installation
This library is available on
[Packagist](https://packagist.org/packages/sop/crypto-util).

    composer require sop/crypto-util

## License
This project is licensed under the MIT License.
