<?php

// Part of php simple framework (psf)

// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// Copyright (c) Petr Bena <petr@bena.rocks> 2015 - 2026

if (!defined("PSF_ENTRY_POINT"))
    die("Not a valid psf entry point");

//! Small dependency-free text template renderer.
//
//! Variables are HTML-escaped by default:
//!     Hello {{user.name}}
//
//! Triple braces insert trusted content without escaping:
//!     {{{html_content}}}
class PsfTemplate
{
    private $source;
    private $variables = [];

    public function __construct($source = '', array $variables = [])
    {
        $this->source = (string)$source;
        $this->SetVariables($variables);
    }

    public static function FromFile($file, array $variables = [])
    {
        if (!is_file($file) || !is_readable($file))
            throw new Exception("Template file couldn't be read: " . $file);

        $source = file_get_contents($file);
        if ($source === false)
            throw new Exception("Template file couldn't be read: " . $file);

        return new PsfTemplate($source, $variables);
    }

    public function Set($name, $value)
    {
        if (!self::IsValidVariableName($name))
            throw new Exception("Invalid template variable name: " . $name);

        $this->variables[$name] = $value;
        return $this;
    }

    public function SetVariables(array $variables)
    {
        foreach ($variables as $name => $value)
            $this->Set($name, $value);

        return $this;
    }

    public function Render(array $variables = [])
    {
        $values = $this->variables;
        foreach ($variables as $name => $value)
        {
            if (!self::IsValidVariableName($name))
                throw new Exception("Invalid template variable name: " . $name);
            $values[$name] = $value;
        }

        $pattern = '/\{\{\{\s*([A-Za-z_][A-Za-z0-9_.-]*)\s*\}\}\}|\{\{\s*([A-Za-z_][A-Za-z0-9_.-]*)\s*\}\}/';
        $template_without_placeholders = preg_replace($pattern, '', $this->source);
        if ($template_without_placeholders === NULL)
            throw new Exception('Unable to parse template');

        if (preg_match('/\{\{.*?\}\}/s', $template_without_placeholders, $matches))
            throw new Exception("Malformed template placeholder: " . $matches[0]);

        $result = preg_replace_callback(
            $pattern,
            function ($matches) use ($values)
            {
                $raw = isset($matches[1]) && $matches[1] !== '';
                $name = $raw ? $matches[1] : $matches[2];
                $value = self::ResolveVariable($values, $name);
                $text = self::ValueToString($value, $name);

                if ($raw)
                    return $text;

                return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            },
            $this->source
        );

        if ($result === NULL)
            throw new Exception('Unable to render template');

        return $result;
    }

    private static function IsValidVariableName($name)
    {
        return is_string($name) && preg_match('/^[A-Za-z_][A-Za-z0-9_.-]*$/', $name) === 1;
    }

    private static function ResolveVariable(array $variables, $name)
    {
        if (array_key_exists($name, $variables))
            return $variables[$name];

        $parts = explode('.', $name);
        $value = $variables;
        foreach ($parts as $part)
        {
            if (is_array($value) && array_key_exists($part, $value))
            {
                $value = $value[$part];
                continue;
            }

            if (is_object($value))
            {
                $object_variables = get_object_vars($value);
                if (array_key_exists($part, $object_variables))
                {
                    $value = $object_variables[$part];
                    continue;
                }
            }

            throw new Exception("Missing template variable: " . $name);
        }

        return $value;
    }

    private static function ValueToString($value, $name)
    {
        if ($value === NULL)
            return '';

        if (is_string($value) || is_numeric($value))
            return (string)$value;

        if (is_bool($value))
            return $value ? 'true' : 'false';

        if (is_object($value) && method_exists($value, '__toString'))
            return (string)$value;

        throw new Exception("Template variable isn't a scalar value: " . $name);
    }
}
