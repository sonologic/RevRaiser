<?php

/*
**    File	  : confirm.php
**    Description : Render page body for pledge confirmation page
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

function send_error_mail($session_data,$error) {
	      // the emails should already have been validated, so if this happens
              // someone messed with it
            if(!valid_email($session_data->campaign->ADMIN_EMAIL))
		die('An error occured, but campaign does not have a valid admin_email defined.');
            if(!valid_email($session_data->pledge->EMAIL))
		die('An error occured, but pledge does not have a valid email defined.');

	    $message=render($session_data->campaign->TEMPLATE,'pledge_error_mail',array(
				'HASH'=>$session_data->pledge->CONFIRM_HASH,
				'AMOUNT'=>render_amount($session_data->pledge->AMOUNT),
				'EMAIL'=>$session_data->pledge->EMAIL,
				'ERROR'=>$error,
	    ));

      	    $ec=mail(
              $session_data->campaign->ADMIN_EMAIL,
              'Error on campaign '.$session_data->campaign->SHORTDESC,
              $message
            );

	    return $ec;
}

function process_form($session_data) {
  $session_data->error=array();

  if( !isset($_GET['hash']) || !preg_match('/^([0-9a-zA-Z]+)\z/',$_GET['hash'],$matches)) {
    array_push($session_data->error,'Invalid hash or missing confirm option!');
  } else {
    $hash=$matches[1];

    $pledge=get_pledge($session_data,$hash);

    $session_data->confirm_sub='error';

    if($pledge==NULL) {
      array_push($session_data->error,'Could not find your pledge!');
    } else {
      $session_data->pledge=$pledge;
      if(!isset($_GET['confirm'])) {
	// user just clicked link in email
	$session_data->confirm_sub='overview';
      } else {
	// user clicked cancel or confirm on site
	if($_GET['confirm']==0) {
	  // cancel
	  $session_data->confirm_sub='cancelled';
	  if(!cancel_pledge($session_data,$hash)) {
            array_push($session_data->error,'Unable to cancel pledge due to database error..');
            $session_data->confirm_sub='error';
	    
	    send_error_mail($session_data,'Unable to cancel pledge due to database error.');
          }
        } else {
          // confirm
	  $session_data->confirm_sub='confirmed';
	  if(!confirm_pledge($session_data,$hash)) {
            array_push($session_data->error,'Unable to confirm pledge due to database error..');
            $session_data->confirm_sub='error';

	    send_error_mail($session_data,'Unable to confirm pledge due to database error.');
          }
        }
      }
    }
  }
}

function render_title($session_data) {
  return "Pledge confirmation";
}

function render_body($session_data) {

  $errors="";
  if(count($session_data->error)) {
      foreach($session_data->error as $msg) {
        $errors.=$msg."<br/>";
      }
  }

  if($session_data->confirm_sub!='error') {
	// TODO: if already confirmed...
    if($session_data->confirm_sub=='overview' && $session_data->pledge->CONFIRMED != NULL) {
      $errors="<b>NOTE: Your pledge was already confirmed on ".$session_data->pledge->CONFIRMED."</b><br/>".$errors;
    }
	
    $values=array(
      'HASH' => $session_data->pledge->CONFIRM_HASH,
      'NAME' => $session_data->pledge->NAME,
      'STREET1' => $session_data->pledge->STREET_ADDRESS1,
      'STREET2' => $session_data->pledge->STREET_ADDRESS2,
      'ZIP' => $session_data->pledge->ZIPCODE,
      'CITY' => $session_data->pledge->CITY,
      'COUNTRY' => $session_data->pledge->COUNTRY,
      'EMAIL' => $session_data->pledge->EMAIL,
      'AMOUNT' => render_amount($session_data->pledge->AMOUNT),
    );
  }
  $values['FEEDBACK']=$errors;

  return render(
		$session_data->campaign->TEMPLATE,'confirm',
		array(
		  'CONTENT' => render(
			$session_data->campaign->TEMPLATE,
			'confirm_'.$session_data->confirm_sub,
                        $values
                  )
                )
          );

}

?>
