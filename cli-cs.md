gdapi-php cli-cs.php
=========

A Command Line Interface (CLI) for Go Daddy&reg; Cloud Servers, built atop gdapi-php

Requirements
---------
* gdapi-php (See gdapi-php README)
* An account in  [Go Daddy Cloud Servers&trade;](http://www.godaddy.com/hosting/cloud-computing.aspx)
* Your API Access and Secret key pair

Getting Started
--------
See the gdapi-php README to get an understanding of the API.
If you haven't already, create your API Access and Secret key pair
* Login to [Go Daddy Cloud Servers&trade;](https://cloud.secureserver.net/) and click on the API tab.
* Create an API Access and Secret key pair, record it.
Point your browser to [the schema page](https://api.cloud.secureserver.net/v1/schemas) and use your API Access and Secret key pair.
See the [documentation site](http://docs.cloud.secureserver.net/) to get an understanding of what it can do.
Prepare to perform actions on your local command line.

Setting Up
---------
### Get a copy of the source
To use the source code, clone it:
> git clone https://github.com/gddk/gdapi-php.git

### Create the cs-config.php file
> cp cs-config.template.php cs-config.php

Edit cs-config.php with your favorite editor and enter your API Access and Secret key pair.

### Try it out
> php cli-cs.php vmls

SSL Problems
--------
See the section in the gdapi-php README on SSL Problems if you have issues.

Usage
--------

### Get basic help
> php cli-cs.php
```bash
C:\git\gdapi-php>php cli-cs.php
Usage: php cli-cs.php command [cmd_arg1] ...
Where command is one of:
        vmls    : List virtual machines
        vmcreate: Create virtual machine
        vmdelete: Delete virtual machine
        nwls    : List networks
        tpls    : List templates
        lbls    : List load balancer
        ipls    : List public IP Addresses

        For help on a specific command:  php cli-cs.php command --help
```

### Get help on a command
> php cli-cs.php vmls --help
```bash
] php cli-cs.php vmls --help
Usage: php cli-cs.php vmls [-h] [-v] [filter]
        -h      : Print the help and exit.  If the h flag is present, only help will display.
        -v      : verbose output
        filter  : i.e. name=web01
```

### Example: php cli-cs.php vmls
This will show you all of your VMs and their Ids.
```bash
C:\git\gdapi-php>php cli-cs.php vmls
web01   1vmmN6gbNRKWzM  running
mysql01 1vmA0w:2lUn5gc  running
web02   1vm:MDU089:MWE  running
mysql02 1vmmT7l8I8YYbo  running
impop01 1vmi0jq3tv7dec  running
wpt     1vmYqDjYerjhc4  running
```

### Example: php cli-cs.php tpls
This will show you just the templates that are custom, i.e. the ones you made, and their templateId.
```bash
C:\git\gdapi-php>php cli-cs.php tpls -v | grep custom
web02   1tjF1kRZ2DgkQ   ready   custom  2012-07-19T01:46:46Z    basic lap build	CentOS   5.6     64
```

### Example: php cli-cs.php nwls
This will show you your available networks and their IDs
```bash
C:\git\gdapi-php>php cli-cs.php nwls
Net01	1nLI9z7ZPnzBg   running
Net02	1nY9g22qA4OtU   running
```

### Example: php cli-cs.php vmcreate
This will create a new vm.  Note: you need the networkId and the templateId from nwls and tpls
```bash
C:\git\gdapi-php>php cli-cs.php vmcreate name=test01 networkId=1nLI9z7ZPnzBg templateId=1tjF1kRZ2DgkQ
Array
(
    [name] => test01
    [networkId] => 1nLI9z7ZPnzBg
    [templateId] => 1tjF1kRZ2DgkQ
    [offering] => 1gb-4vcpu
)
```

### Example: php cli-cs.php vmdelete
This will delete any VM with the name test01.
```bash
C:\git\gdapi-php>php cli-cs.php vmdelete name=test01
Deleting test01 1vmexRyhxBRYls
```
