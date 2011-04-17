<?php

/*
**    File	  : index.php
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

require_once('inc/common.php');


// include page rendering code dependingn on p GET parameter
$p=(isset($_GET['p']))?$_GET['p']:'*';
if(!preg_match('/^[a-z*]+$/',$p)) die('Invalid parameter value for p.');

if(isset($dispatch[$p])) {
  require_once($dispatch[$p]);
} else {
  die('Invalid parameter value for p.');
}

// set up session data
$session_data->DB=$DB;
// TODO: implement campaign vhosting, for now select default campaign
$session_data->campaign=get_campaign($session_data,DEFAULTCAMPAIGN);

// render page body
$title=render_title($session_data);
$body=render_body($session_data);

//$body=render($campaign['template'],'landing',array());

// render html output
$args=array(
  'TITLE' => $title,
  'BODY' => $body,
);

// output to browser
echo render($session_data->campaign->TEMPLATE,'page',$args);

?>
