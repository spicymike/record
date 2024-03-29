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
 *  Lousson\Record\Builtin\Handler\BuiltinRecordBuilderJSON definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record\Builtin\Builder;

/** Dependencies: */
use Lousson\Record\Builtin\BuiltinRecordBuilder;
use Lousson\Record\Error\RuntimeRecordError;

/**
 *  A JSON record builder
 *
 *  @since      lousson/record-0.1.0
 *  @package    org.lousson.record
 */
class BuiltinRecordBuilderJSON extends BuiltinRecordBuilder
{
    /**
     *  Build record content
     *
     *  The buildRecord() method returns a byte sequence representing the
     *  given $record in its serialized form.
     *
     *  @param  array               $data       The record's data
     *
     *  @return string
     *          The serialized record is returned on success
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case of malformed $data or internal errors
     */
    public function buildRecord(array $data)
    {
        $record = $this->normalizeData($data);
        $setup = ini_set("track_errors", true);
        $php_errormsg = "UKNOWN ERROR";
        $sequence = json_encode($record, JSON_HEX_TAG|JSON_HEX_APOS);
        $error = $php_errormsg;
        ini_set("track_errors", $setup);
        $this->checkRecordSequence($sequence, $error);
        return $sequence;
    }

    /**
     *  Verify byte sequences built
     *
     *  The checkRecordSequence() method is used internally to check the
     *  byte sequence built by buildRecord(). This used to be done inline,
     *  but since it's tricky to actually trigger a scenario where the
     *  operation fails, which made the snippet hard to test, it has been
     *  moved into it's own method.
     *
     *  @param  string              $sequence       The byte sequence
     *  @param  string              $error          The error message
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case $sequence is FALSE
     */
    private function checkRecordSequence($sequence, $error)
    {
        if (false === $sequence) {
            $message = "Failed to build JSON record: $error";
            $code = RuntimeRecordError::E_INTERNAL_ERROR;
            throw new RuntimeRecordError($message, $code);
        }
    }
}

