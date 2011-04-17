<?php

/*
**    File	  : pledge.php
**    Description : Render page body for pledge confirmation page
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

function render_title($session_data) {
  return "Pledge confirmation";
}

function render_body($session_data) {
  return render($session_data->campaign->TEMPLATE,'confirm',array());
}

?>
