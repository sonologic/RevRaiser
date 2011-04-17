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

// TODO: implement campaign vhosting, for now select default campaign
$campaign=get_campaign(DEFAULTCAMPAIGN);

// render landing page body
$body=render($campaign['template'],'landing',array());

// render html output
$args=array(
  'TITLE' => 'Pledge',
  'BODY' => $body,
);
echo render($campaign['template'],'page',$args);

?>
