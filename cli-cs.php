<?php
/*
 * Copyright (c) 2012 Go Daddy Operating Company, LLC
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */
require_once('init.php');
require_once('cs-config.php');

if ( !isset($argv[1]) ) { display_help(); exit; }
$command = $argv[1];

switch($command) {
	case 'vmls':
		$flags = getFlags($argc, $argv);
		$arguments = getArguments($argc, $argv);
		vmls($flags, $arguments);
		break;
	case 'vmcreate':
		vmcreate();
		break;
	case 'nwls':
		nwls();
		break;
	case 'tpls':
		tpls();
		break;
	default:
		display_help();
		exit;
}
exit;

/*
 * Matches any command line argument that starts with "-"
 * and smushes it together, stripping all non-alphanumeric. 
 */
function getFlags($argc, $argv)
{
	$ret = "";
	for ($i=1; $i<$argc; $i++)
	{
		$var = $argv[$i];
		if (strpos($var, "-")===0)
		{
			$ret .= preg_replace("/[^a-zA-Z0-9]/", "", $var);
		}
	}
	return $ret;
}
/*
 * Matches all command line arguments that do not start with "-"
 * and returns each as an array.
 */
function getArguments($argc, $argv)
{
	$ret = array();
	for ($i=2; $i<$argc; $i++)
	{
		$var = $argv[$i];
		if (strpos($var, "-")===false)
		{
			$ret[] = $var;
		}
	}
	return $ret;
}

function vmls($flags=null, $arguments=array()) 
{
	if ( strpos($flags, "h")!==false ) { display_help("vmls"); return; }
	
	$verbose = false;
	if ( strpos($flags, "v")!==false ) { $verbose = true; }
	
	$filter = array();
	foreach($arguments as $arg)
	{
		list($key, $val) = explode("=", $arg, 2);
		if ($key)
			$filter[$key] = $val;
	}

	$client = new \GDAPI\Client(CS_URL, CS_ACCESS_KEY, CS_SECRET_KEY);
	$machines = $client->virtualmachine->query($filter);
	
	foreach ( $machines as $machine )
	{
	  print $machine->getName() . "\t" . $machine->getId() . "\t" . $machine->getState();
	  if ($verbose)
	  {
	  	print "\t".$machine->getAutoRestart();
	  	print "\t".$machine->getBits();
	  	print "\t".$machine->getCreated();
	  	print "\t".$machine->getGuestOs();
	  	print "\t".$machine->getGuestOsVersion();
	  	print "\t".$machine->getHostname();
	  	print "\t".$machine->getNetworkId();
	  	print "\t".$machine->getTemplateId();
	  	print "\t".$machine->getOffering();
	  	print "\t".$machine->getPrivateIpv4Address();
	  	print "\t".$machine->getPrivateMacAddress();
	  }
	  print "\n";
	}
}

function vmcreate()
{
	
}

function nwls()
{
	
}

function tpls()
{
	
}

function display_help($command=null) {
	if (!$command)
	{
		echo "Usage: php cli-cs.php command [cmd_arg1] ...
Where command is one of:
	vmls	: List virtual machines
	vmcreate: Create virtual machine
	nwls	: List networks
	tpls	: List templates
	
	For help on a specific command:  php cli-cs.php command --help
";
		return;
	}
	
	switch($command) {
		case 'vmls':
			echo "Usage: php cli-cs.php vmls [-h] [-v] [filter]
	-h	: Print the help and exit.  If the h flag is present, only help will display.
	-v	: verbose output
	filter	: i.e. name=web01
";	
			break;
		default:
			echo "Usage: php cli-cs.php command [cmd_arg1] ...
Where command is one of:
	vmls	: List virtual machines
	vmcreate: Create virtual machine
	nwls	: List networks
	tpls	: List templates
	
	For help on a specific command:  php cli-cs.php command --help
";		
	}
}
