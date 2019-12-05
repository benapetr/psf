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

// Copyright (c) Petr Bena <petr@bena.rocks> 2015 - 2019

if (!defined("PSF_ENTRY_POINT"))
    die("Not a valid psf entry point");

class PsfPasswd_User
{
    public $Username;
    public $PasswordHash;

    public function __construct($name, $password_hash)
    {
        $this->Username = $name;
        $this->PasswordHash = $password_hash;
    }

    public function ToString()
    {
        return $this->Username . ':' . $this->PasswordHash;
    }

    public static function FromString($line)
    {
        $parts = explode(':', $line);
        return new PsfPasswd_User($parts[0], $parts[1]);
    }
}

//! This class is a simple interface to built-in primitive plain-text user DB
//
//! Format is:
//! username:password_hash
class PsfPasswd
{
    private $filename;
    private $users = [];
    private $salt = 'replace_me';
    
    public function __construct($file, $load_file = true, $default_salt = NULL)
    {
        $this->filename = $file;
        if ($default_salt !== NULL)
            $this->salt = $default_salt;
        if ($load_file)
            $this->Load();
    }

    public function Authenticate($username, $password)
    {
        $hash = crypt($password, $this->salt);
        foreach ($this->users as $user)
        {
            if ($user->Username == $username && $user->PasswordHash == $hash)
                return true;
        }
        return false;
    }

    public function Load()
    {
        $handle = fopen($this->filename, 'r');

        if (!$handle)
            throw new Exception('Unable to open file for reading: ' . $this->filename);

        while (($line = fgets($handle)) !== false)
        {
            $this->users[] = PsfPasswd_User::FromString($line);
        }
        fclose($handle);
    }
}
