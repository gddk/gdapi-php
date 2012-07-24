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

$flags = getFlags($argc, $argv);
$arguments = getArguments($argc, $argv);

switch($command) {
	case 'vmls':
		vmls($flags, $arguments);
		break;
	case 'vmcreate':
		vmcreate($flags, $arguments);
		break;
	case 'vmdelete':
		vmdelete($flags, $arguments);
		break;
	case 'nwls':
		nwls($flags, $arguments);
		break;
	case 'tpls':
		tpls($flags, $arguments);
		break;
	case 'lbls':
		lbls($flags, $arguments);
		break;
	case 'ipls':
		ipls($flags, $arguments);
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

function nwls($flags=null, $arguments=array()) 
{
	if ( strpos($flags, "h")!==false ) { display_help("nwls"); return; }
	
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
	$networks = $client->network->query($filter);
	
	foreach ( $networks as $network )
	{
	  print $network->getName() . "\t" . $network->getId() . "\t" . $network->getState();
	  if ($verbose)
	  {
	  	print "\t".$network->getCreated();
	  	print "\t".$network->getDefaultIpv4Gateway();
	  	print "\t".$network->getPrimaryIpv4Address();
	  	print "\t".$network->getDomain();
	  	print "\t".$network->getIpv4Cidr();
	  }
	  print "\n";
	}
}

function tpls($flags=null, $arguments=array()) 
{
	if ( strpos($flags, "h")!==false ) { display_help("tpls"); return; }
	
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
	$templates = $client->template->query($filter);
	
	foreach ( $templates as $template )
	{
	  print $template->getName() . "\t" . $template->getId() . "\t" . $template->getState();
	  if ($verbose)
	  {
	  	print "\t".$template->getTemplateType();
  		print "\t".$template->getCreated();
	  	print "\t".$template->getDescription();
	  	print "\t".$template->getGuestOs();
	  	print "\t".$template->getGuestOsVersion();
	  	print "\t".$template->getBits();
	  	print "\t".$template->getSourceSnapshotId();
	  }
	  print "\n";
	}
}

function lbls($flags=null, $arguments=array()) 
{
	if ( strpos($flags, "h")!==false ) { display_help("lbls"); return; }
	
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
	$loadbalancers = $client->loadbalancer->query($filter);
	
	foreach ( $loadbalancers as $loadbalancer )
	{
	  print $loadbalancer->getName() . "\t" . $loadbalancer->getId() . "\t" . $loadbalancer->getPublicStartPort();
	  if ($verbose)
	  {
	  	print "\t".$loadbalancer->getPrivatePort();
	  	print "\t".$loadbalancer->getAlgorithm();
  		print "\t".$loadbalancer->getCreated();
	  	print "\t".$loadbalancer->getNetworkId();
	  	print "\t".$loadbalancer->getPublicIpv4AddressId();
	  	print "\t".$loadbalancer->getPolicy();
	  	print "\t".implode(",", $loadbalancer->getVirtualMachineIds());
	  }
	  print "\n";
	}
}

function ipls($flags=null, $arguments=array()) 
{
	if ( strpos($flags, "h")!==false ) { display_help("ipls"); return; }
	
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
	$ipaddresses = $client->publicipaddress->query($filter);
	
	foreach ( $ipaddresses as $ipaddress )
	{
	  print $ipaddress->getName() . "\t" . $ipaddress->getId() . "\t" . $ipaddress->getAddress();
	  if ($verbose)
	  {
	  	print "\t".$ipaddress->getIpAddressType();
	  	print "\t".$ipaddress->getNetworkId();
  		print "\t".$ipaddress->getPrimary();
	  }
	  print "\n";
	}
}

function vmcreate($flags=null, $arguments=array())
{
	if ( strpos($flags, "h")!==false ) { display_help("vmcreate"); return; }
	
	$verbose = false;
	if ( strpos($flags, "v")!==false ) { $verbose = true; }

	$filter = array();
	foreach($arguments as $arg)
	{
		list($key, $val) = explode("=", $arg, 2);
		if ($key)
			$filter[$key] = $val;
	}

	$name = $filter['name'];
	$network_id = $filter['networkId'];
	$template_id = $filter['templateId'];
	$offering = $filter['offering'];

	if ( !$offering )
		$offering = '1gb-4vcpu';

	if (!$name || !$network_id || !$template_id || !$offering ) {
		display_help("vmcreate");
		return;
		
	}

	$client = new \GDAPI\Client(CS_URL, CS_ACCESS_KEY, CS_SECRET_KEY);
	
	$props = array(
		'name' => $name,
		'networkId' => $network_id,
		'templateId' => $template_id,
		'offering' => $offering
	);
	
	print_r($props);
	$client->virtualmachine->create($props);
}

function vmdelete($flags=null, $arguments=array())
{
	if ( strpos($flags, "h")!==false ) { display_help("vmdelete"); return; }
	
	$verbose = false;
	if ( strpos($flags, "v")!==false ) { $verbose = true; }

	$filter = array();
	foreach($arguments as $arg)
	{
		list($key, $val) = explode("=", $arg, 2);
		if ($key)
			$filter[$key] = $val;
	}
	
	$id = $filter['id'];
	$name = $filter['name'];
	
	if ( !($id || $name) ) {
		display_help("vmdelete");
		return;
		
	}
	
	$client = new \GDAPI\Client(CS_URL, CS_ACCESS_KEY, CS_SECRET_KEY);
	if ($id)
	{
		$machine = $client->virtualmachine->getById($id);
		echo "Deleting ".$machine->getName() . "\n";
		$result = $machine->remove();
	}
	if ($name)
	{
		$machines = $client->virtualmachine->query( array('name'=>$name) );
		$id = null;
		foreach ( $machines as $machine )
		{
			echo "Deleting ".$machine->getName() . " " . $machine->getId() . "\n";
		  	$result = $machine->remove();
		}
	}
}


function display_help($command=null) {
	
	switch($command) {
		case 'vmls':
			echo "Usage: php cli-cs.php vmls [-h] [-v] [filter]
	-h	: Print the help and exit.  If the h flag is present, only help will display.
	-v	: verbose output
	filter	: i.e. name=web01
";	
			break;
			
		case 'nwls':
			echo "Usage: php cli-cs.php nwls [-h] [-v] [filter]
	-h	: Print the help and exit.  If the h flag is present, only help will display.
	-v	: verbose output
	filter	: i.e. name=network01
";	
			break;
			
		case 'tpls':
			echo "Usage: php cli-cs.php tpls [-h] [-v] [filter]
	-h	: Print the help and exit.  If the h flag is present, only help will display.
	-v	: verbose output
	filter	: i.e. name=template01 (doesn't work)
";
			break;
			
		case 'lbls':
			echo "Usage: php cli-cs.php lbls [-h] [-v] [filter]
	-h	: Print the help and exit.  If the h flag is present, only help will display.
	-v	: verbose output
	filter	: i.e. publicStartPort=modifier lt=1024, i.e. removed=modifier
";	
			break;
			
		case 'ipls':
			echo "Usage: php cli-cs.php ipls [-h] [-v]
	-h	: Print the help and exit.  If the h flag is present, only help will display.
	-v	: verbose output
";	
			break;
			
		case 'vmcreate':
			echo "Usage: php cli-cs.php vmcreate [-h] name=<str> networkId=<str> templateId=<str> [offering=<str>]
	-h	: Print the help and exit.  If the h flag is present, only help will display.
	name	: Name of the VM to create
	networkId	: The Id of the network to place the VM
	templateId	: The Id of the template to use
	offering	: [optional] The offering to use; default 1gb-4vcpu
";	
			break;
	
		case 'vmdelete':
			echo "Usage: php cli-cs.php vmdelete [-h] [id=<str>] [name=<str>]
	-h	: Print the help and exit.  If the h flag is present, only help will display.
	id	: Id of the VM to delete.  Optional, but one of id or name must be present.
	name	: Name of the VM(s) to delete. Optional, but one of id or name must be present.
";	
			break;
			
		default:
			echo "Usage: php cli-cs.php command [cmd_arg1] ...
Where command is one of:
	vmls	: List virtual machines
	vmcreate: Create virtual machine
	vmdelete: Delete virtual machine
	nwls	: List networks
	tpls	: List templates
	lbls	: List load balancer
	ipls	: List public IP Addresses
	
	For help on a specific command:  php cli-cs.php command --help
";		
	}
}
