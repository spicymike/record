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
 *  Lousson\Record\AbstractRecordBuilderTest class definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record;

/** Dependencies: */
use Lousson\Record\AbstractRecordTest;
use Lousson\Record\AnyRecordBuilder;

/**
 *  An abstract test case for record builders
 *
 *  @since      lousson/record-0.1.0
 *  @package    org.lousson.record
 *  @link       http://www.phpunit.de/manual/current/en/
 */
abstract class AbstractRecordBuilderTest extends AbstractRecordTest
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
    abstract public function getRecordBuilder();

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
        return null;
    }

    /**
     *  Test the buildRecord() method
     *
     *  The testBuildRecordError() method verifies that the builder's
     *  buildRecord() implementation raises a record exception if it is
     *  provided with invalid data.
     *
     *  @dataProvider               provideInvalidData
     *  @expectedException          \Lousson\Record\AnyRecordException
     *  @test
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case of success
     *
     *  @throws \Exception
     *          Raised in case of internal errors
     */
    public function testBuildRecordError(array $data)
    {
        $this->testBuildRecordOnce($data);
    }

    /**
     *  Test the buildRecord() method
     *
     *  The testBuildRecordOnce() method is a smoke test for the builder's
     *  buildRecord() implementation. It verifies that the builder returns
     *  a byte sequence if it is provided with valid data.
     *
     *  @param  array               $data       The record's data
     *
     *  @dataProvider               provideValidData
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case getRecordBuilder() does not return a record
     *          builder or buildRecord() does not return a byte sequence
     *
     *  @throws \Exception
     *          Raised in case of internal errors
     */
    public function testBuildRecordOnce(array $data)
    {
        $builder = $this->getRealRecordBuilder();
        $this->invokeBuildRecord($builder, $data);
    }

    /**
     *  Test the buildRecord() method
     *
     *  If the test case is provided with a parser (see getRecordParser()),
     *  the testBuildRecordTwice() method can verify that the buildRecord()
     *  method returns consistent values when builder and parser are used
     *  to provide each other's input paramters.
     *
     *  @param  array               $data       The record's data
     *
     *  @dataProvider               provideValidData
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case getRecordBuilder() does not return a record
     *          builder or buildRecord() does not return the same sequence
     *          of bytes twice
     *
     *  @throws \Exception
     *          Raised in case of internal errors
     */
    public function testBuildRecordTwice(array $data)
    {
        $builder = $this->getRealRecordBuilder();
        $builderClass = get_class($builder);
        $parser = $this->getRealRecordParser();
        $parserClass = get_class($parser);
        $sequence[] = $this->invokeBuildRecord($builder, $data);
        $record[] = $parser->parseRecord($sequence[0]);
        $sequence[] = $this->invokeBuildRecord($builder, $record[0]);
        $this->assertEquals(
            $sequence[0], $sequence[1],
            "The $builderClass::buildRecord() must return the same ".
            "result when invoked with the $parserClass::parseRecord() ".
            "result for the first sequence returned"
        );

        $record[] = $parser->parseRecord($sequence[1]);
        $this->assertEquals(
            $record[0], $record[1],
            "The sequence returned by $builderClass::buildRecord() ".
            "must equal the returned sequence when given the result ".
            "of a $parserClass::parseRecord() invocation with the ".
            "first sequence returned"
        );
    }

    /**
     *  Invoke a builder's buildRecord() method
     *
     *  The invokeBuildRecord() method is used internally to invoke the
     *  buildRecord() method of the given record $builder.
     *  It validates the returned byte sequence before passing it on to
     *  the caller.
     *
     *  @param  AnyRecordBuilder    $builder    The record builder
     *  @param  array               $data       The record data
     *
     *  @return string
     *          A byte sequence is returned on success
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case the record $data invalid
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case buildRecord() does not return a string
     *
     *  @throws \Exception
     *          Raised in case the $builder caused an internal error
     */
    final protected function invokeBuildRecord(
        AnyRecordBuilder $builder,
        array $data
    ) {
        $sequence = $builder->buildRecord($data);
        $builderClass = get_class($builder);

        $this->assertInternalType(
            "string", $sequence,
            "The $builderClass::buildRecord() method must return a ".
            "byte sequence"
        );

        return $sequence;
    }

    /**
     *  Obtain a record builder
     *
     *  The getRealRecordBuilder() method is used internally to invoke the
     *  test's getRecordBuilder() method and validate the result before it
     *  is returned.
     *
     *  @return \Lousson\Record\AnyRecordBuilder
     *          A record builder instance is returned on success
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case getRecordBuilder() does not return a builder
     */
    private function getRealRecordBuilder()
    {
        $builder = $this->getRecordBuilder();
        $testClass = get_class($this);

        $this->assertInstanceOf(
            "Lousson\\Record\\AnyRecordBuilder", $builder,
            "The $testClass::getRecordBuilder() method must return an ".
            "instance of the AnyRecordBuilder interface"
        );

        return $builder;
    }

    /**
     *  Obtain a record parser
     *
     *  The getRealRecordParser() method is used internally to invoke the
     *  test's getRecordParser() method and validate the result before it
     *  is returned.
     *
     *  @return \Lousson\Record\AnyRecordParser
     *          A record parser instance is returned on success
     *
     *  @throws \PHPUnit_Framework_SkippedTestError
     *          Raised in case getRecordParser() returns NULL
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case getRecordParser() returns neither a parser
     *          instance nor NULL
     */
    private function getRealRecordParser()
    {
        $parser = $this->getRecordParser();
        $testClass = get_class($this);

        if (null === $parser) {
            $this->markTestSkipped();
        }

        $this->assertInstanceOf(
            "Lousson\\Record\\AnyRecordParser", $parser,
            "The $testClass::getRecordParser() method must return an ".
            "instance of the AnyRecordParser interface or NULL"
        );

        return $parser;
    }
}

