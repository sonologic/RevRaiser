<?php

/*
**    File	  : pledge.php
**    Description : Render page body for payed page
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

function render_title($session_data) {
  return "Thank you!";
}

function render_body($session_data) {
  return render($session_data->campaign->TEMPLATE,'payed',array());
}

?>
