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

$dispatch=array(
  '*' => 'pledge.php',
  'pledge' => 'pledge.php',
  'confirm' => 'confirm.php',
  'pay' => 'pay.php',
  'payed' => 'payed.php',
);

$DB = NewADOConnection(DBTYPE);

$DB->Connect(DBHOST,DBUSER,DBPASS,DBNAME);

function get_campaign($session_data,$id) {
  $rs = $session_data->DB->Execute("select * from campaign where id=?",array($id));
  if(!$rs->EOF) {
    return $rs->FetchObject();
  }

  return NULL; 
}

function get_pledge($session_data,$hash) {
  $rs = $session_data->DB->Execute("select * from pledge where confirm_hash=?",array($hash));
  if(!$rs->EOF) {
    return $rs->FetchObject();
  }

  return NULL; 
}

function cancel_pledge($session_data,$hash) {
  return $session_data->DB->Execute("delete from pledge where confirm_hash=?",array($hash));
}

function confirm_pledge($session_data,$hash) {
  return $session_data->DB->Execute("update pledge set confirmed=now() where confirm_hash=?",array($hash));
}

function render_amount($amount) {
  return sprintf("%d.%02d",$amount/100,$amount%100);
}

/**
Validate an email address.
Provide email address (raw input)
Returns true if the email address has the email 
address format and the domain exists.
Kindly borrowed from: http://www.linuxjournal.com/article/9585?page=0,3
*/
function valid_email($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
   }
   return $isValid;
}



?>
