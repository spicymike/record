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
 *  Lousson\Record\Builtin\BuiltinRecordUtil class definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record\Builtin;

/** Dependencies: */
use Lousson\Record\Error\InvalidRecordError;

/**
 *  A utility for record entities
 *
 *  The BuiltinRecordUtil eases the implementation of many record entity
 *  interfaces, by providing common functionaliy like validation.
 *
 *  Note that the facilities provided by this class will, most likely, be
 *  provided by Traits in a future release. For now, the form of a static
 *  method container has been choosen, in oder to keep the builtin and
 *  generic classes clean and increase the overall performance.
 *
 *  @since      lousson/record-0.1.0
 *  @package    org.lousson.record
 */
final class BuiltinRecordUtil
{
    /**
     *  Determine whether record data is valid
     *
     *  The isValidData() method returns a boolean indicating whether the
     *  given $data is valid according to the constraints associated with
     *  records.
     *  The optional $message reference is left untouched in case the data
     *  is valid, otherwise it is populated with a text indicating why the
     *  validation has failed.
     *
     *  @param  array               $data       The record data
     *  @param  string              $message    The failure message
     *
     *  @return bool
     *          TRUE is returned if the $data is valid, FALSE otherwise
     */
    public static function isValidData(array $data, &$message = null)
    {
        $isValidData = true;

        try {
            self::validateData($data);
        }
        catch (InvalidRecordError $error) {
            $message = $error->getMessage();
            $isValidData = false;
        }

        return $isValidData;
    }


    /**
     *  Determine whether a media type is valid
     *
     *  The isValidType() method returns a boolean indicating whether the
     *  given media $type is valid (according to RFC 2046).
     *  The optional $message reference is left untouched in case the type
     *  is valid, otherwise it is populated with a text indicating why the
     *  validation has failed.
     *
     *  @param  string              $type       The media type
     *  @param  string              $message    The failure message
     *
     *  @return bool
     *          TRUE is returned if the $type is valid, FALSE otherwise
     */
    public static function isValidType($type, &$message = null)
    {
        static $pattern = "/^
            [a-z]+ ([+_.\\-]? [a-z0-9]+)* \\/
            [a-z]+ ([+_.\\-]? [a-z0-9]+)* \$/ix";

        $isValidType = true;

        if (!preg_match($pattern, $type)) {
            $message = "Invalid media type: $type";
            $isValidType = false;
        }

        return $isValidType;
    }

    /**
     *  Normalize record data
     *
     *  The normalizeData() method returns an array that represents the
     *  normalized form of the given record $data.
     *
     *  @param  array               $data       The record data
     *  @param  array               $index      The record index
     *
     *  @return array
     *          The normalized record data is returned on success
     *
     *  @throws \Lousson\Record\Error\InvalidRecordError
     *          Raised in case the record $data is invalid
     */
    public static function normalizeData(array $data, array $index = null)
    {
        $normalized = array();

        foreach ($data as $name => $item) {
            self::validateName($name);
            $index[] = $name;
            $normalized[$name] = self::normalizeItem($item, $index);
            array_pop($index);
        }

        return $normalized;
    }

    /**
     *  Normalize media types
     *
     *  The normalizeType() method returns a string that represents the
     *  normalized form of the given media $type.
     *
     *  @param  string              $type       The media type
     *
     *  @return string
     *          The normalized media type name is returned on success
     *
     *  @throws \Lousson\Record\Error\InvalidRecordError
     *          Raised in case the media $type is invalid
     */
    public static function normalizeType($type)
    {
        self::validateType($type);
        $normalized = strtolower($type);
        return $normalized;
    }

    /**
     *  Validate record data
     *
     *  The validateData() method is used to validate record $data at
     *  the given $index, which must either be an array of record names
     *  or absent.
     *
     *  @param  array               $data       The record data
     *  @param  array               $index      The record index
     *
     *  @throws \Lousson\Record\Error\InvalidRecordError
     *          Raised in case the record $data is invalid
     */
    public static function validateData(array $data, array $index = null)
    {
        foreach ($data as $name => $item) {
            self::validateName($name, $index);
            $index[] = $name;
            self::validateItem($item, $index);
            array_shift($index);
        }
    }

    /**
     *  Validate media types
     *
     *  The validateType() method is used to validate the media $type
     *  provided.
     *
     *  @param  string              $type       The media type
     *
     *  @throws \Lousson\Record\Error\InvalidRecordError
     *          Raised in case the media $type is invalid
     */
    public static function validateType($type)
    {
        if (!self::isValidType($type, $message)) {
            $code = InvalidRecordError::E_NOT_SUPPORTED;
            throw new InvalidRecordError($message, $code);
        }
    }

    /**
     *  Normalize item lists
     *
     *  The normalizeList() method is used internally to normalize the
     *  item $list at the given $index.
     *
     *  @param  array               $list       The item list
     *  @param  array               $index      The record index
     *
     *  @return array
     *          The normalized item list is returned on success
     *
     *  @throws \Lousson\Record\Error\InvalidRecordError
     *          Raised in case the item $list is invalid
     */
    private static function normalizeList(array $list, array $index = null)
    {
        $normalized = array();

        foreach ($list as $item) {
            $normalized[] = self::normalizeItem($item, $index);
        }

        return $normalized;
    }

    /**
     *  Normalize record items
     *
     *  The normalizeItem() method is used internally to normalize the
     *  record $item at the given $index.
     *
     *  @param  mixed               $item       The record item
     *  @param  array               $index      The record index
     *
     *  @return mixed
     *          The normalized item is returned on success
     *
     *  @throws \Lousson\Record\Error\InvalidRecordError
     *          Raised in case the record $item is invalid
     */
    private static function normalizeItem($item, $index = null)
    {
        if (!is_array($item)) {
            self::validateItem($item, $index);
            $normalized = $item;
        }
        else if (self::isNumericIndexed($item)) {
            $normalized = self::normalizeList($item, $index);
        }
        else {
            $normalized = self::normalizeData($item, $index);
        }

        return $normalized;
    }

    /**
     *  Validate item lists
     *
     *  The validateList() method is used internally to validate the
     *  item $list at the given $index.
     *
     *  @param  array               $list       The item list
     *  @param  array               $index      The record index
     *
     *  @throws \Lousson\Record\Error\InvalidRecordError
     *          Raised in case the item $list is invalid
     */
    private static function validateList(array $list, array $index = null)
    {
        $i = -1;

        foreach ($list as $item) {
            $index[] = ++$i;
            self::validateItem($item, $index);
            array_shift($index);
        }
    }

    /**
     *  Validate item names
     *
     *  The validateName() method is used internally to validate the
     *  item $name at the given $index.
     *
     *  @param  string              $name       The item name
     *  @param  array               $index      The record index
     *
     *  @throws \Lousson\Record\Error\InvalidRecordError
     *          Raised in case the item $name is invalid
     */
    private static function validateName($name, array $index = null)
    {
        static $pattern = "/^
            [a-z]+ ([+_.\\-]? [a-z0-9]+)* \$/ix";

        if (!preg_match($pattern, $name)) {
            $path = $index? implode("/", $index): "";
            $message = "Invalid record key at /$path: \"$name\"";
            $code = InvalidRecordError::E_INVALID_RECORD;
            throw new InvalidRecordError($message, $code);
        }
    }


    /**
     *  Validate record items
     *
     *  The validateItem() method is used internally to validate the
     *  record $item at the given $index.
     *
     *  @param  mixed               $item       The record item
     *  @param  array               $index      The record index
     *
     *  @throws \Lousson\Record\Error\InvalidRecordError
     *          Raised in case the record $item is invalid
     */
    private static function validateItem($item, array $index = null)
    {
        if (is_array($item)) {
            self::isNumericIndexed($item)
                ? self::validateList($item, $index)
                : self::validateData($item, $index);
        }
        else if (null !== $item && !is_scalar($item)) {
            $path = implode("/", $index);
            $type = is_object($item)? get_class($item): gettype($item);
            $message = "Invalid record item at /$path: $type";
            $code = InvalidRecordError::E_INVALID_RECORD;
            throw new InvalidRecordError($message, $code);
        }
    }

    /**
     *  Determine whether array indices are numeric only
     *
     *  The isNumericIndexed() method is used internally to determine
     *  whether an array has only numeric inidices, in which case it is
     *  considered as a list rather than a map.
     *
     *  @param  array               $data       The array to check
     *
     *  @return bool
     *          TRUE is returned if numeric indices are encountered
     *          exclusively, FALSE otherwise
     */
    private static function isNumericIndexed(array $data)
    {
        $isNumericIndexed = true;

        foreach (array_keys($data) as $key) {
            if (!is_int($key) && !ctype_digit($key)) {
                $isNumericIndexed = false;
                break;
            }
        }

        return $isNumericIndexed;
    }
}

