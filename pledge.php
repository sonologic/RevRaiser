<?php

/*
**    File	  : pledge.php
**    Description : Render page body for pledge landing page
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

function process_form($session_data) {
  $session_data->error=array();

  if(isset($_POST['pledge'])) {

	// TODO: does adodb actually handle escaping securely ?
    $sql="insert into pledge (campaign_id,name,street_address1,street_address2,zipcode,city,country,email,amount,remark,confirm_hash) values (?,?,?,?,?,?,?,?,?,?,?)";

    $email=$_POST['email'];
    if(!valid_email($email)) array_push($session_data->error,"Email address not valid.");

    if(!strlen($_POST['name'])) array_push($session_data->error,"Please provide name.");
    if(!strlen($_POST['street1'])) array_push($session_data->error,"Please provide address.");
    if(!strlen($_POST['zip'])) array_push($session_data->error,"Please provide zip.");
    if(!strlen($_POST['city'])) array_push($session_data->error,"Please provide city.");
    if(!strlen($_POST['country'])) array_push($session_data->error,"Please provide country.");

    $amount=100 * str_replace(',','.',$_POST['amount']);

    if($amount<$session_data->campaign->MINIMUM) array_push($session_data->error,"Amount must be at least &euro;".
			sprintf("%d.%02d",$session_data->campaign->MINIMUM/100,$session_data->campaign->MINIMUM%100));

    $hash=hash('sha256','revraiser:'.time().':'.$email.':'.rand());

    $values=array(
	$session_data->campaign->ID,
	htmlentities($_POST['name']),
	htmlentities($_POST['street1']),
	htmlentities($_POST['street2']),
	htmlentities($_POST['zip']),
	htmlentities($_POST['city']),
        htmlentities($_POST['country']),
	$email,
	$amount,
	'',
	$hash,
    );
       
    $succes=$session_data->DB->Execute($sql,$values);

    if(!$succes) { 
      array_push($session_data->error,$session_data->DB->ErrorMsg());
    } else {

      $message=render($session_data->campaign->TEMPLATE,'pledge_mail',array('HASH'=>$hash));

      $ec=mail(
            $email,
            'Your pledge for project '.$session_data->campaign->SHORTDESC,
	    $message
	  );
      if(!$ec) {
        array_push($session_data->error,'Could not send confirmation email, please contact '.$session_data->campaign->ADMIN_EMAIL);
      }
    }

    $session_data->pledge=TRUE;
  } else {
    $session_data->pledge=NULL;
  }
}

function render_title($session_data) {
  return "Pledge";
}

function render_body($session_data) {

  if($session_data->pledge==NULL) {
    $values=array(
	'ACTION'=>URLBASE.'?p=pledge',
	'FEEDBACK'=>'',
	'NAME'=>'',
	'STREET1'=>'',
	'STREET2'=>'',
	'ZIP'=>'',
	'CITY'=>'',
	'COUNTRY'=>'',
	'EMAIL'=>'',
	'AMOUNT'=>'',
    );
    $form=render($session_data->campaign->TEMPLATE,'pledge_form',$values);
  } else {
    if(count($session_data->error)) {
      $errors="";
      foreach($session_data->error as $msg) {
        $errors.=$msg."<br/>";
      }

      $values=array(
	'ACTION'=>URLBASE.'?p=pledge',
	'FEEDBACK'=>$errors,
	'NAME'=>htmlentities($_POST['name']),
	'STREET1'=>htmlentities($_POST['street1']),
	'STREET2'=>htmlentities($_POST['street2']),
	'ZIP'=>htmlentities($_POST['zip']),
	'CITY'=>htmlentities($_POST['city']),
	'COUNTRY'=>htmlentities($_POST['country']),
	'EMAIL'=>htmlentities($_POST['email']),
	'AMOUNT'=>htmlentities($_POST['amount']),
      );

      $form=render($session_data->campaign->TEMPLATE,'pledge_form',$values);
    } else {

      $form=render($session_data->campaign->TEMPLATE,'pledge_form_thanks',array());
    }
  }

  return render($session_data->campaign->TEMPLATE,'landing',array('PLEDGEFORM'=>$form));
}

?>
