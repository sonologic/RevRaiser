<?php

/*
**    File	  : index.php
**    Description :
**    Author	  : Koen Martens <gmc@revspace.nl>
**
**    Copyright (c) 2011 by Koen Martens
**
**    This file is part of RevRaiser.
**
**    RevRaiser is free software: you can redistribute it and/or modify
**    it under the terms of the GNU General Public License as published by
**    the Free Software Foundation, either version 3 of the License, or
**    (at your option) any later version.
**
**    RevRaiser is distributed in the hope that it will be useful,
**    but WITHOUT ANY WARRANTY; without even the implied warranty of
**    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**    GNU General Public License for more details.
**
**    You should have received a copy of the GNU General Public License
**    along with RevRaiser.  If not, see <http://www.gnu.org/licenses/>.
*/

header("Content-type: application/xhtml+xml");

require_once('inc/common.php');


// include page rendering code dependingn on p GET parameter
$p=(isset($_GET['p']))?$_GET['p']:'*';
if(!preg_match('/^[a-z*]+\z/',$p)) die('Invalid parameter value for p.');

if(isset($dispatch[$p])) {
  require_once($dispatch[$p]);
} else {
  die('Invalid parameter value for p.');
}

// set up session data
$session_data->DB=$DB;
// TODO: implement campaign vhosting, for now select default campaign
$session_data->campaign=get_campaign($session_data,DEFAULTCAMPAIGN);
$session_data->campaign->PLEDGED=get_pledge_total($session_data);

// process form
process_form($session_data);

// render page body
$title=render_title($session_data);
$body=render_body($session_data);

// at this point, amount pledged may have changed due to confirmation or
// cancellation, so we re-calculate
$session_data->campaign->PLEDGED=get_pledge_total($session_data);

// render html output
$args=array(
  'TITLE' => $title,
  'BODY' => $body,
  'GOAL' => render_amount($session_data->campaign->GOAL),
  'PLEDGED' => render_amount($session_data->campaign->PLEDGED),
  'DIFFERENCE' => render_amount($session_data->campaign->GOAL - $session_data->campaign->PLEDGED),
  'WEBMASTEREMAIL' => WEBMASTEREMAIL,
);

// output to browser
echo render($session_data->campaign->TEMPLATE,'page',$args);

?>
