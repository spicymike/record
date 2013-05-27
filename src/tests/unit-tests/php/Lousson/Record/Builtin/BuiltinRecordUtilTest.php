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
     *  @dataProvider               provideValidData
     *  @test
     */
    public function testValidateValidData(array $data)
    {
        BuiltinRecordUtil::validateData($data);
        $this->assertTrue(BuiltinRecordUtil::isValidData($data));
    }

    /**
     *  @dataProvider               provideInvalidData
     *  @expectedException          \Lousson\Record\AnyRecordException
     *  @test
     */
    public function testValidateInvalidData(array $data)
    {
        $this->testValidateValidData($data);
    }

    /**
     *  @dataProvider               provideArbitraryData
     *  @test
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
     *  @dataProvider               provideValidData
     *  @test
     */
    public function testNormalizeValidData(array $data)
    {
        $normalized = BuiltinRecordUtil::normalizeData($data);
        $this->assertInternalType("array", $normalized);
        $this->assertTrue(BuiltinRecordUtil::isValidData($data));
    }

    /**
     *  @dataProvider               provideInvalidData
     *  @expectedException          \Lousson\Record\AnyRecordException
     *  @test
     */
    public function testNormalizeInvalidData(array $data)
    {
        $this->testNormalizeValidData($data);
    }

    /**
     *  @dataProvider               provideArbitraryData
     *  @test
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
     *  @dataProvider               provideValidMediaTypes
     *  @test
     */
    public function testValidateValidType($type)
    {
        BuiltinRecordUtil::validateType($type);
        $this->assertTrue(BuiltinRecordUtil::isValidType($type));
    }

    /**
     *  @dataProvider               provideInvalidMediaTypes
     *  @expectedException          \Lousson\Record\AnyRecordException
     *  @test
     */
    public function testValidateInvalidType($type)
    {
        $this->testValidateValidType($type);
    }

    /**
     *  @dataProvider               provideArbitraryMediaTypes
     *  @test
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
     *  @dataProvider               provideValidMediaTypes
     *  @test
     */
    public function testNormalizeValidType($type)
    {
        $normalized = BuiltinRecordUtil::normalizeType($type);
        $this->assertInternalType("string", $normalized);
        $this->assertTrue(BuiltinRecordUtil::isValidType($type));
    }

    /**
     *  @dataProvider               provideInvalidMediaTypes
     *  @expectedException          \Lousson\Record\AnyRecordException
     *  @test
     */
    public function testNormalizeInvalidType($type)
    {
        $this->testNormalizeValidType($type);
    }

    /**
     *  @dataProvider               provideArbitraryMediaTypes
     *  @test
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

