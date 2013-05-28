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
 *  Lousson\Record\Builtin\Builder\BuiltinRecordBuilderJSONTest definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record\Builtin\Builder;

/** Dependencies: */
use Lousson\Record\AbstractRecordBuilderTest;
use Lousson\Record\Builtin\Builder\BuiltinRecordBuilderJSON;
use Lousson\Record\Builtin\Parser\BuiltinRecordParserJSON;
use ReflectionMethod;

/**
 *  A test case for the builtin JSON record builder
 *
 *  @since      lousson/record-0.1.0
 *  @package    org.lousson.record
 *  @link       http://www.phpunit.de/manual/current/en/
 */
final class BuiltinRecordBuilderJSONTest extends AbstractRecordBuilderTest
{
    /**
     *  Obtain the record builder to test
     *
     *  The getRecordBuilder() method returns the record builder instance
     *  that is used in the tests.
     *
     *  @return \Lousson\Record\AnyRecordBuilder
     *          A record builder instance is returned on success
     */
    public function getRecordBuilder()
    {
        $builder = new BuiltinRecordBuilderJSON();
        return $builder;
    }

    /**
     *  Obtain the associated record parser, if any
     *
     *  The getRecordParser() method returns either a record parser
     *  instance that can reverse the builder's operation or NULL, in
     *  case no such parser is available.
     *
     *  @return \Lousson\Record\AnyRecordParser
     *          A record parser instance is returned on success,
     *          NULL otherwise
     */
    public function getRecordParser()
    {
        $parser = new BuiltinRecordParserJSON();
        return $parser;
    }

    /**
     *  Test the error handling
     *
     *  The testCheckRecordSequence() method is a test case for scenarios
     *  where the JSON encoding in buildRecord() fails.
     *
     *  @expectedException          \Lousson\Record\AnyRecordException
     *  @test
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case the test is successful
     *
     *  @throws \ReflectionException
     *          Raised in case of an internal error
     */
    public function testCheckRecordSequence()
    {
        $builder = $this->getRecordBuilder();
        $method = new ReflectionMethod($builder, "checkRecordSequence");
        $method->setAccessible(true);
        $method->invoke($builder, false, "UNKNOWN ERROR");
    }
}

