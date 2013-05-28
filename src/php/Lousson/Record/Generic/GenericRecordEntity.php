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
 *  Lousson\Record\Generic\GenericRecordEntity class definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record\Generic;

/** Dependencies: */
use Lousson\Record\AnyRecordEntity;
use Lousson\Record\Builtin\BuiltinRecordUtil;
use Lousson\Record\Error\RuntimeRecordError;
use Closure;

/**
 *  A generic record entity class
 *
 *  The GenericRecordEntity class implements the AnyRecordEntity interface
 *  on top of a Closure callback.
 *
 *  @since      lousson/record-0.2.0
 *  @package    org.lousson.record
 */
class GenericRecordEntity implements AnyRecordEntity
{
    /**
     *  Create a record entity instance
     *
     *  The constructor requires the provision of a callback Closure that
     *  actually implements the getRecord() functionality.
     *
     *  @param  Closure             $callback   The getRecord() callback
     */
    public function __construct(Closure $callback)
    {
        $this->callback = $callback;
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
        try {
            $callback = $this->callback;
            $data = $callback();
            $record = BuiltinRecordUtil::normalizeRecord($data);
            return $record;
        }
        catch (Exception $error) {
            $errorClass = get_class($error);
            $message = "Failed to retrieve record; caught $errorClass";
            $code = RuntimeRecordError::E_INTERNAL_ERROR;
            throw new RuntimeRecordError($message, $code, $error);
        }
    }

    /**
     *  The getRecord() entity callback
     *
     *  @var \Closure
     */
    private $callback;
}

