<?php
/*
The MIT License (MIT)

Copyright (c) 2014 Philipp Helo Rehs

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
 */

/**
 * @package      LIST_Libraries
 * @author       Philipp Helo Rehs <P.Rehs@gmx.net>
 * @version      1.0
 * @moss-version 2.0
 */
class mosslib
{
    private $allowed_languages = [
        "c", "cc", "java", "ml", "pascal", "ada", "lisp", "scheme", "haskell", "fortran", "ascii", "vhdl",
        "perl", "matlab", "python", "mips", "prolog", "spice", "vb", "csharp", "modula2", "a8086", "javascript",
        "plsql", "verilog",
    ];
    private $options = [];
    private $basefiles = [];
    private $files = [];
    private $server;
    private $port;
    private $userid;
    
    /**
     * @param int     $userid
     * @param string  $server
     * @param integer $port
     */
    public function __construct()
    {
        $ci =& get_instance();
        $ci->load->config('moss');
        $this->options['m'] = 10;
        $this->options['d'] = 0;
        $this->options['n'] = 250;
        $this->options['x'] = 0;
        $this->options['c'] = "";
        $this->options['l'] = "c";
        $this->server = $ci->config->item('moss_server');
        $this->port = $ci->config->item('moss_port');
        $this->userid = $ci->config->item('moss_user_id');
    }
    
    /**
     * set the language of the source files
     *
     * @param string $lang
     *
     * @throws Exception
     */
    public function setLanguage($lang): bool
    {
        if (in_array($lang, $this->allowed_languages)) {
            $this->options['l'] = $lang;
            return true;
        }
        
        throw new Exception("Unsupported language", 1);
    }
    
    /**
     * get a list with all supported languages
     *
     * @return array
     */
    public function getAllowedLanguages(): array
    {
        $supported_languages = $this->allowed_languages;
        sort($supported_languages);
        return $supported_languages;
    }
    
    /**
     * Enable Directory-Mode
     *
     * @param bool $enabled
     *
     * @throws Exception
     * @see -d in MOSS-Documentation
     */
    public function setDirectoryMode($enabled): bool
    {
        if (is_bool($enabled)) {
            $this->options['d'] = (int)$enabled;
            return true;
        }
        
        throw new Exception("DirectoryMode must be a boolean", 2);
    }
    
    /**
     * Add a basefile
     *
     * @param string $file
     *
     * @throws Exception
     * @see -b in MOSS-Documentation
     */
    public function addBaseFile($file): bool
    {
        if (file_exists($file) && is_readable($file)) {
            $this->basefiles[] = $file;
            return true;
        }
        
        throw new Exception("Can't find or read the basefile (" . $file . ")", 3);
    }
    
    /**
     * Occurences of a string over the limit will be ignored
     *
     * @param int $limit
     *
     * @throws Exception
     * @see -m in MOSS-Documentation
     */
    public function setIngoreLimit($limit): bool
    {
        if (is_int($limit) && $limit > 1) {
            $this->options['m'] = (int)$limit;
            return true;
        }
        
        throw new Exception("The limit needs to be greater than 1", 4);
    }
    
    /**
     * Set the comment for the request
     *
     * @param string $comment
     *
     * @see -s in MOSS-Documentation
     */
    public function setCommentString($comment): bool
    {
        $this->options['c'] = $comment;
        return true;
    }
    
    /**
     * Set the number of results
     *
     * @param int $limit
     *
     * @throws Exception
     * @see -n in MOSS-Documentation
     */
    public function setResultLimit($limit): bool
    {
        if (is_int($limit) && $limit > 1) {
            $this->options['n'] = (int)$limit;
            return true;
        }
        
        throw new Exception("The limit needs to be greater than 1", 5);
    }
    
    /**
     * Enable the Experimental Server
     *
     * @param bool $enabled
     *
     * @throws Exception
     * @see -x in MOSS-Documentation
     */
    public function setExperimentalServer($enabled): bool
    {
        if (is_bool($enabled)) {
            $this->options['x'] = (int)$enabled;
            return true;
        }
        
        throw new Exception("Needs to be a boolean", 6);
    }
    
    /**
     * Add a file to the request
     *
     * @param string $file
     *
     * @throws Exception
     */
    public function addFile($file): bool
    {
        if (file_exists($file) && is_readable($file)) {
            $this->files[] = $file;
            return true;
        }
        
        throw new Exception("Can't find or read the file (" . $file . ")", 7);
    }
    
    /**
     * Add files by a wildcard
     *
     * @param string $path
     *
     * @throws Exception
     * @example addByWildcard("/files/*.c")
     */
    public function addByWildcard($path): void
    {
        foreach (glob($path) as $file) {
            $this->addFile($file);
        }
    }
    
    /**
     * Send the request to the server and wait for the response
     *
     * @return string
     * @throws Exception
     */
    public function send(): string
    {
        $socket = fsockopen($this->server, $this->port, $errno, $errstr);
        if (!$socket) {
            throw new Exception(
                "Socket-Error: " . $this->server . ":" . $this->port . " - " . $errstr . " (" . $errno . ")",
                8
            );
        } else {
            fwrite($socket, "moss " . $this->userid . "\n");
            fwrite($socket, "directory " . $this->options['d'] . "\n");
            fwrite($socket, "X " . $this->options['x'] . "\n");
            fwrite($socket, "maxmatches " . $this->options['m'] . "\n");
            fwrite($socket, "show " . $this->options['n'] . "\n");
            
            //Language Check
            fwrite($socket, "language " . $this->options['l'] . "\n");
            $read = trim(fgets($socket));
            if ($read === "no") {
                fwrite($socket, "end\n");
                fclose($socket);
                throw new Exception("Unsupported language", 1);
            }
            
            foreach ($this->basefiles as $bfile) {
                $this->uploadFile($socket, $bfile, 0);
            }
            
            $i = 1;
            foreach ($this->files as $file) {
                $this->uploadFile($socket, $file, $i);
                $i++;
            }
            
            fwrite($socket, "query 0 " . $this->options['c'] . "\n");
            $read = fgets($socket);
            fwrite($socket, "end\n");
            fclose($socket);
            return $read;
        }
    }
    
    /**
     * Upload a file to the server
     *
     * @param socket $handle A handle from fsockopen
     * @param string $file   The Path of the file
     * @param int    $id     0 = Basefile, incrementing for every normal file
     *
     * @return void
     */
    private function uploadFile($handle, $file, $id): void
    {
        $size = filesize($file);
        $file_name_fixed = str_replace(" ", "_", $file);
        fwrite($handle, "file " . $id . " " . $this->options['l'] . " " . $size . " " . $file_name_fixed . "\n");
        fwrite($handle, file_get_contents($file));
    }
    
}
