<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 textwidth=75: *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Copyright (c) 2013, The Lousson Project                               *
 *                                                                       *
 * All rights reserved.                                                  *
 *                                                                       *
 * Redistribution and use in source and binary forms, with or without    *
 * modification, are permitted provided that the following conditions    *
 * are met:                                                              *
 *                                                                       *
 * 1) Redistributions of source code must retain the above copyright     *
 *    notice, this list of conditions and the following disclaimer.      *
 * 2) Redistributions in binary form must reproduce the above copyright  *
 *    notice, this list of conditions and the following disclaimer in    *
 *    the documentation and/or other materials provided with the         *
 *    distribution.                                                      *
 *                                                                       *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS   *
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT     *
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS     *
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE        *
 * COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,            *
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES    *
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR    *
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)    *
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT,   *
 * STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)         *
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED   *
 * OF THE POSSIBILITY OF SUCH DAMAGE.                                    *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/**
 *  Lousson\Record\Builtin\BuiltinRecordUtilTest class definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record\Builtin;

/** Dependencies: */
use Lousson\Record\AbstractRecordTest;
use Lousson\Record\Builtin\BuiltinRecordUtil;
use Lousson\Record\Error\InvalidRecordError;

/**
 *  A test case for the builtin record utility
 *
 *  @since      lousson/record-0.1.0
 *  @package    org.lousson.record
 *  @link       http://www.phpunit.de/manual/current/en/
 */
final class BuiltinRecordUtilTest extends AbstractRecordTest
{
    /**
     *  Test the validateData() method
     *
     *  The testValidateValidData() method tests the implementation of
     *  the BuiltinRecordUtil::validateData() method with a set of valid
     *  $data records.
     *
     *  @param  array               $data       The record's data
     *
     *  @dataProvider               provideValidData
     *  @test
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised if a data record is considered invalid
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testValidateValidData(array $data)
    {
        BuiltinRecordUtil::validateData($data);
    }

    /**
     *  Test the validateData() method
     *
     *  The testValidateInvalidData() method tests the implementaion of
     *  the BuiltinRecordUtil::validateData() method with a set of invalid
     *  $data records.
     *
     *  @param  array               $data       The record's data
     *
     *  @dataProvider               provideInvalidData
     *  @expectedException          \Lousson\Record\AnyRecordException
     *  @test
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised if a data record is considered invalid
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testValidateInvalidData(array $data)
    {
        $this->testValidateValidData($data);
    }

    /**
     *  Test the isValidData() method
     *
     *  The testNormalizeInalidData() method tests the implementation of
     *  the BuiltinRecordUtil::isValidData() method with an arbitrary set
     *  of record data - including the expected consistency of the return
     *  value with the behavior of the validateData() method.
     *
     *  @param  array               $data       The record's data
     *
     *  @dataProvider               provideArbitraryData
     *  @test
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised if a data record is considered invalid
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testValidateArbitraryData(array $data)
    {
        if (!BuiltinRecordUtil::isValidData($data, $message)) {
            $this->setExpectedException(
                "Lousson\\Record\\Error\\InvalidRecordError",
                $message, InvalidRecordError::E_INVALID_RECORD
            );
        }

        $this->testValidateValidData($data);
    }

    /**
     *  Test the normalizeData() method
     *
     *  The testNormalizeValidData() method tests the implementation of
     *  the BuiltinRecordUtil::normalizeData() method with a set of valid
     *  $data records.
     *
     *  @param  array               $data       The record's data
     *
     *  @dataProvider               provideValidData
     *  @test
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised if a data record is considered invalid
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised if normalizeData() does not return an array
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testNormalizeValidData(array $data)
    {
        $normalized = BuiltinRecordUtil::normalizeData($data);
        $this->assertInternalType("array", $normalized);
    }

    /**
     *  Test the normalizeData() method
     *
     *  The testNormalizeInalidData() method tests the implementation
     *  of the BuiltinRecordUtil::normalizeData() method with a set of
     *  invalid $data records.
     *
     *  @param  array               $data       The record's data
     *
     *  @dataProvider               provideInvalidData
     *  @expectedException          \Lousson\Record\AnyRecordException
     *  @test
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised if a data record is considered invalid
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised if normalizeData() does not return an array
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testNormalizeInvalidData(array $data)
    {
        $this->testNormalizeValidData($data);
    }

    /**
     *  Test the isValidData() method
     *
     *  The testNormalizeInalidData() method tests the implementation of
     *  the BuiltinRecordUtil::isValidData() method with an arbitrary set
     *  of record data - including the expected consistency of the return
     *  value with the behavior of the normalizeData() method.
     *
     *  @param  array               $data       The record's data
     *
     *  @dataProvider               provideArbitraryData
     *  @test
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised if a type is considered invalid
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised if normalizeData() does not return an array
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testNormalizeArbitraryData(array $data)
    {
        if (!BuiltinRecordUtil::isValidData($data, $message)) {
            $this->setExpectedException(
                "Lousson\\Record\\Error\\InvalidRecordError",
                $message, InvalidRecordError::E_INVALID_RECORD
            );
        }

        $this->testNormalizeValidData($data);
    }

    /**
     *  Test the validateType() method
     *
     *  The testValidateValidType() method tests the implementation of
     *  the BuiltinRecordUtil::validateType() method with a set of valid
     *  media types.
     *
     *  @param  string              $type       The media type name
     *
     *  @dataProvider               provideValidMediaTypes
     *  @test
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised if a type is considered invalid
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testValidateValidType($type)
    {
        BuiltinRecordUtil::validateType($type);
    }

    /**
     *  Test the validateType() method
     *
     *  The testValidateInvalidType() method tests the implementation of
     *  the BuiltinRecordUtil::validateType() method with a set of invalid
     *  media types.
     *
     *  @param  string              $type       The media type name
     *
     *  @dataProvider               provideInvalidMediaTypes
     *  @expectedException          \Lousson\Record\AnyRecordException
     *  @test
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised if a type is considered invalid
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testValidateInvalidType($type)
    {
        $this->testValidateValidType($type);
    }

    /**
     *  Test the isValidType() method
     *
     *  The testNormalizeInalidType() method tests the implementation of
     *  the BuiltinRecordUtil::isValidType() method with an arbitrary set
     *  of media types - including the expected consistency of the return
     *  value with the behavior of the validateType() method.
     *
     *  @param  string              $type       The media type name
     *
     *  @dataProvider               provideArbitraryMediaTypes
     *  @test
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised if a type is considered invalid
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testValidateArbitraryType($type)
    {
        if (!BuiltinRecordUtil::isValidType($type, $message)) {
            $this->setExpectedException(
                "Lousson\\Record\\Error\\InvalidRecordError",
                $message, InvalidRecordError::E_NOT_SUPPORTED
            );
        }

        $this->testValidateValidType($type);
    }

    /**
     *  Test the normalizeType() method
     *
     *  The testNormalizeValidType() method tests the implementation of
     *  the BuiltinRecordUtil::normalizeType() method with a set of valid
     *  media types.
     *
     *  @param  string              $type       The media type name
     *
     *  @dataProvider               provideValidMediaTypes
     *  @test
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised if a type is considered invalid
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised if normalizeType() does not return a string
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testNormalizeValidType($type)
    {
        $normalized = BuiltinRecordUtil::normalizeType($type);
        $this->assertInternalType("string", $normalized);
    }

    /**
     *  Test the normalizeType() method
     *
     *  The testNormalizeInalidType() method tests the implementation of
     *  the BuiltinRecordUtil::normalizeType() method with a set of invalid
     *  media types.
     *
     *  @param  string              $type       The media type name
     *
     *  @dataProvider               provideInvalidMediaTypes
     *  @expectedException          \Lousson\Record\AnyRecordException
     *  @test
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised if a type is considered invalid
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised if normalizeType() does not return a string
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testNormalizeInvalidType($type)
    {
        $this->testNormalizeValidType($type);
    }

    /**
     *  Test the isValidType() method
     *
     *  The testNormalizeInalidType() method tests the implementation of
     *  the BuiltinRecordUtil::isValidType() method with an arbitrary set
     *  of media types - including the expected consistency of the return
     *  value with the behavior of the normalizeType() method.
     *
     *  @param  string              $type       The media type name
     *
     *  @dataProvider               provideArbitraryMediaTypes
     *  @test
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised if a type is considered invalid
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised if normalizeType() does not return a string
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testNormalizeArbitraryType($type)
    {
        if (!BuiltinRecordUtil::isValidType($type, $message)) {
            $this->setExpectedException(
                "Lousson\\Record\\Error\\InvalidRecordError",
                $message, InvalidRecordError::E_NOT_SUPPORTED
            );
        }

        $this->testNormalizeValidType($type);
    }
}

