<?php

/*
**    File	  : template.php
**    Description : Poor man's templating
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


function render($prefix,$template,$values) {
  $file='templates/'.$prefix.$template.'.tmpl';
  $tmpl=implode('',file($file))
    or die('No such template: '.$file);

  foreach($values as $key => $value) {
    $tmpl=str_replace('%'.$key.'%',$value,$tmpl);
  }
  $tmpl=str_replace('%URLBASE%',URLBASE,$tmpl);

  return $tmpl;
}


?>
