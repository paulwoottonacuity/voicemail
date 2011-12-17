<?php
if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed'); }
//This file is part of FreePBX.
//
//    FreePBX is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, either version 3 of the License, or
//    (at your option) any later version.
//
//    FreePBX is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with FreePBX.  If not, see <http://www.gnu.org/licenses/>.
// Copyright (c) 2006, 2008, 2009 qldrob, rcourtna
//
//for translation only
if (false) {
_("Voicemail");
_("My Voicemail");
_("Dial Voicemail");
_("Voicemail Admin");
}

global $astman;
global $amp_conf;

$fcc = new featurecode('voicemail', 'myvoicemail');
$fcc->setDescription('My Voicemail');
$fcc->setDefault('*97');
$fcc->update();
unset($fcc);

$fcc = new featurecode('voicemail', 'dialvoicemail');
$fcc->setDescription('Dial Voicemail');
$fcc->setDefault('*98');
$fcc->setProvideDest();
$fcc->update();
unset($fcc);

//1.6.2
$ver = modules_getversion('voicemail');
if ($ver !== null && version_compare($ver,'1.6.2','lt')) { //we have to fix existing users with wrong values for vm ticket #1697
	if ($astman) {
		$sql = "select * from users where voicemail='disabled' or voicemail='';";
		$users = sql($sql,"getAll",DB_FETCHMODE_ASSOC);
		foreach($users as $user) {
			$astman->database_put("AMPUSER",$user['extension']."/voicemail","\"novm\"");
		}
	} else {
		echo _("Cannot connect to Asterisk Manager with ").$amp_conf["AMPMGRUSER"]."/".$amp_conf["AMPMGRPASS"];
		return false;
	}
	sql("update users set voicemail='novm' where voicemail='disabled' or voicemail='';");
}

// vmailadmin module functionality has been fully incporporated into this module
// so if it is installed we remove and delete it from the repository.
//
outn(_("checking if Voicemail Admin (vmailadmin) is installed.."));
$modules = module_getinfo('vmailadmin');
if (!isset($modules['vmailadmin'])) {
  out(_("not installed, ok"));
} else {
  out(_("installed."));
  out(_("Voicemail Admin being removed and merged with Voicemail"));
  outn(_("Attempting to delete.."));
  $result = module_delete('vmailadmin');
  if ($result === true) {
    out(_("ok"));
  } else {
    out($result);
  }
}

$freepbx_conf =& freepbx_conf::create();

// VM_SHOW_IMAP
//
$set['value'] = false;
$set['defaultval'] =& $set['value'];
$set['readonly'] = 0;
$set['hidden'] = 0;
$set['level'] = 3;
$set['module'] = 'voicemail';
$set['category'] = 'Voicemail Module';
$set['emptyok'] = 0;
$set['sortorder'] = 100;
$set['name'] = 'Provide IMAP Voicemail Fields';
$set['description'] = 'Installations that have configured Voicemail with IMAP should set this to true so that the IMAP username and password fields are provided in the Voicemail setup screen for extensions. If an extension alread has these fields populated, they will be displayed even if this is set to false.';
$set['type'] = CONF_TYPE_BOOL;
$freepbx_conf->define_conf_setting('VM_SHOW_IMAP',$set,true);
