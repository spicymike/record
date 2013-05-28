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
 *  Lousson\Record\Builtin\BuiltinRecordEntity class definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record\Builtin;

/** Dependencies: */
use Lousson\Record\AnyRecordEntity;
use Lousson\Record\Builtin\BuiltinRecordUtil;
use Lousson\Record\Error\RuntimeRecordError;

/**
 *  The builtin record entity class
 *
 *  The BuiltinRecordEntity class is the default implementation of the
 *  AnyRecordEntity interface. It operates as a register of record items.
 *  which allows the use of any
 *
 *  @since      lousson/record-0.2.0
 *  @package    org.lousson.record
 */
class BuiltinRecordEntity implements AnyRecordEntity
{
    /**
     *  Create a record entity
     *
     *  The static method create() spawns a new instance of the builtin
     *  record entity class or, more precisely, of the class it has been
     *  invoked on. The optional $data parameter can be used to define
     *  a preset record association instead of the empty, default one.
     *
     *  @param  array               $data       The default data, if any
     *
     *  @return \Lousson\Record\Builtin\BuiltinRecordEntity
     *          A new record entity instance is returned on success
     */
    public static function create(array $data = null)
    {
        $instance = new static();

        if (null !== $data) {
            $instance->setRecord($data);
        }

        return $instance;
    }

    /**
     *  Update a record property
     *
     *  The setRecordProperty() method is used to assign a new $value to
     *  the record property identified by the given $key.
     *
     *  @param  string                  $key        The property's name
     *  @param  mixed                   $value      The property's value
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case the $key is malformed or the $value does
     *          not fulfill the constraints associated with record items
     */
    public function setRecordProperty($key, $value)
    {
        $name = BuiltinRecordUtil::normalizeName($key);
        $property = BuiltinRecordUtil::normalizeItem($value);
        $this->record[$name] = $property;
    }

    /**
     *  Obtain a record property
     *
     *  The getRecordProperty() method is used to obtain the value of the
     *  record property identified by the given $key.
     *  The optional $fallback parameter, if provided, is used as return
     *  value in case no property is associated with the $key. Anyway, in
     *  case the method was invoked with only one parameter and there is
     *  no property available (not even NULL), an exception is thrown.
     *
     *  @param  string                  $key        The property's name
     *  @param  mixed                   $fallback   The fallback value
     *
     *  @return mixed
     *          The value associated with the $key is returned on success
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case the $key is malformed or neither a value
     *          nor a $fallback is available
     */
    public function getRecordProperty($key, $fallback = null)
    {
        $name = BuiltinRecordUtil::normalizeName($key);

        if (isset($this->record[$name])) {
            $property = $this->record[$name];
        }
        else if(array_key_exists($name, $this->record)) {
            $property = $fallback;
        }
        else {
            $message = "Failed to retrieve property \"$name\" ($key)";
            $code = InvalidRecordError::E_NOT_FOUND;
            throw new InvalidRecordError($message, $code);
        }

        return $property;
    }

    /**
     *  Determine whether a property is set
     *
     *  The hasRecordProperty() method returns a boolean that indicates
     *  whether a property value is associated with the given $key.
     *
     *  @param  string              $key        The property's name
     *
     *  @return bool
     *          TRUE is returned in case a value for $key is set,
     *          FALSE otherwise
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case the $key is malformed
     */
    public function hasRecordProperty($key)
    {
        if (isset($this->record[$key])) {
            $hasProperty = true;
        }
        else if (BuiltinRecordUtil::isValidName($key)) {
            $name = BuiltinRecordUtil::normalizeName($key);
            $hasProperty = isset($this->record[$name]);
        }
        else {
            $hasProperty = false;
        }

        return $hasProperty;
    }

    /**
     *  Update record data
     *
     *  The setRecord() method is used to replace the record associated
     *  with the entity with the provided $data.
     *
     *  @param  array               $data       The record data
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case the $key is malformed or the $data does
     *          not fulfill the constraints associated with records
     */
    public function setRecord(array $data)
    {
        $record = BuiltinRecordUtil::normalizeData($data);
        $this->record = $record;
    }

    /**
     *  Obtain record data
     *
     *  The getRecord() method is used to obtain the data associated with
     *  the record, in order to e.g. pass it on to a persistence manager
     *  or pass it back to a client.
     *
     *  @return array
     *          The requested record data is returned on success
     */
    public function getRecord()
    {
        return $this->record;
    }

    /**
     *  Create a record entity instance
     */
    final private function __construct()
    {
    }

    /**
     *  The data record associated with the entity
     *
     *  @var array
     */
    private $record;
}

