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
 *  Lousson\Record\AbstractRecordTest class definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record;

/** Dependencies: */
use PHPUnit_Framework_TestCase;

/**
 *  An abstract test case for record builders
 *
 *  @since      lousson/record-0.1.0
 *  @package    org.lousson.record
 *  @link       http://www.phpunit.de/manual/current/en/
 */
abstract class AbstractRecordTest extends PHPUnit_Framework_TestCase
{
    /**
     *  Provide valid record data parameters
     *
     *  The provideValidData() method returns an array of multiple items,
     *  each of whose is an array with one item; an associative, valid
     *  record data array.
     *
     *  @return array
     *          A list of record data parameters is returned on success
     */
    public function provideValidData()
    {
        $data[][] = array();
        $data[][] = array("foo" => "bar", "baz" => array());
        $data[][] = array("foo" => array("bar", "baz"));
        $data[][] = array("foo" => null);
        $data[][] = array("foo" => array("bar" => array("baz" => null)));

        return $data;
    }

    /**
     *  Provide invalid record data parameters
     *
     *  The provideInvalidData() method returns an array of multiple items,
     *  each of whose is an array with one item; an invalid record data
     *  array.
     *
     *  @return array
     *          A list of record data parameters is returned on success
     */
    public function provideInvalidData()
    {
        $data[][] = array("foobar");
        $data[][] = array(null);
        $data[][] = array(null, "foo" => "bar");
        $data[][] = array("object" => (object) array());
        $data[][] = array("resource" => STDERR);
        $data[][] = array("umläutz" => "prohibited");
        $data[][] = array("1two" => "prohibited");

        return $data;
    }

    /**
     *  Provide valid media type parameters
     *
     *  The provideValidMediaTypes() method returns an array of multiple
     *  items, each of whose is an array with one string item representing
     *  a wellformed media type.
     *
     *  @return array
     *          A list of media type parameters is returned on success
     */
    public function provideValidMediaTypes()
    {
        return array(
            array("application/json"),
            array("application/textedit"),
            array("application/vnd.php.serialized"),
            array("application/xml"),
            array("application/xml+xhtml"),
            array("text/json"),
            array("text/plain"),
            array("text/x-json"),
            array("zz-application/zz-winassoc-ini"),
        );
    }

    /**
     *  Provide invalid media type parameters
     *
     *  The provideInvalidMediaTypes() method returns an array of multiple
     *  items, each of whose is an array with one string item representing
     *  a malformed media type.
     *
     *  @return array
     *          A list of media type parameters is returned on success
     */
    public function provideInvalidMediaTypes()
    {
        return array(
            array("*/*"),
            array("something-very-long-without-a-slash"),
            array("invälid/germän-ümläütz"),
            array("invalid/too/many/slashes"),
        );
    }

    /**
     *  Provide arbitrary record data parameters
     *
     *  The provideArbitraryData() method returns an array of multiple
     *  items, each of whose is an array with one item; either an invalid
     *  or a valid record data array.
     *
     *  @return array
     *          A list of record data parameters is returned on success
     */
    public function provideArbitraryData()
    {
        $valid = $this->provideValidData();
        $invalid = $this->provideInvalidData();
        $both = array_merge($valid, $invalid);
        sort($both);
        return $both;
    }

    /**
     *  Provide arbitrary media type parameters
     *
     *  The provideArbitraryMediaTypes() method returns an array of
     *  multiple items, each of whose is an array with one string item;
     *  either a valid or an invalid media type.
     *
     *  @return array
     *          A list of media type parameters is returned on success
     */
    public function provideArbitraryMediaTypes()
    {
        $valid = $this->provideValidMediaTypes();
        $invalid = $this->provideInvalidMediaTypes();
        $both = array_merge($valid, $invalid);
        sort($both);
        return $both;
    }
}

