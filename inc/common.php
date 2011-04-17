<?php

/*
**    File	  : common.php
**    Description :
**    Author	  : Koen Martens <gmc@revspace.nl>
**
**    Copyright (c) 2011 by Koen Martens
**
**    This file is part of Foobar.
**
**    Foobar is free software: you can redistribute it and/or modify
**    it under the terms of the GNU General Public License as published by
**    the Free Software Foundation, either version 3 of the License, or
**    (at your option) any later version.
**
**    Foobar is distributed in the hope that it will be useful,
**    but WITHOUT ANY WARRANTY; without even the implied warranty of
**    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**    GNU General Public License for more details.
**
**    You should have received a copy of the GNU General Public License
**    along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once('inc/template.php');
require_once('inc/adodb5/adodb.inc.php');
require_once('inc/local_settings.php');

$DB = NewADOConnection(DBTYPE);

$DB->Connect(DBHOST,DBUSER,DBPASS,DBNAME);

function get_campaign($id) {
  global $DB;

  $rs = $DB->Execute("select * from campaign where id=?",array($id));
  if(!$rs->EOF) {
    return $rs->FetchRow();
  }

  return NULL; 
}


?>
