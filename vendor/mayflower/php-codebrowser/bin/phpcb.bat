@echo off

REM PHP_CodeBrowser shell script Wrapper for WIN
REM Bash file called from the command line
REM
REM Copyright (c) 2007-2009, Mayflower GmbH
REM All rights reserved.
REM
REM Redistribution and use in source and binary forms, with or without
REM modification, are permitted provided that the following conditions
REM are met:
REM
REM   * Redistributions of source code must retain the above copyright
REM     notice, this list of conditions and the following disclaimer.
REM
REM   * Redistributions in binary form must reproduce the above copyright
REM     notice, this list of conditions and the following disclaimer in
REM     the documentation and/or other materials provided with the
REM     distribution.
REM
REM   * Neither the name of Mayflower GmbH nor the names of his
REM     contributors may be used to endorse or promote products derived
REM     from this software without specific prior written permission.
REM
REM THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
REM "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
REM LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
REM FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
REM COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
REM INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
REM BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
REM LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
REM CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
REM LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
REM ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
REM POSSIBILITY OF SUCH DAMAGE.
REM
REM @category   PHP_CodeBrowser
REM @package    PHP_CodeBrowser
REM @subpackage bin
REM @author     Elger Thiele <elger.thiele@mayflower.de>
REM @copyright  2007-2009 Mayflower GmbH
REM @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
REM @version    SVN: $Id: phpcb 5182 2009-09-03 12:34:35Z elger $
REM @link       http://www.phpunit.de/
REM @since      File available since 0.1.0

set PHPBIN=@php_bin@
"@php_bin@" "@bin_dir@\phpcb" %*
