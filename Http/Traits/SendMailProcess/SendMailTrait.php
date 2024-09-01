<?php
namespace Modules\Settings\Http\Traits\SendMailProcess;

use Modules\Settings\Mail\SendEmailJob;

trait SendMailTrait 
{
   public function SendVerificationCodeThroughMail($blade,$message,$subject,$type,$email,$name,$verification_code='',$password='',$url='',$extra_data='')
   {
      $details_of_email =
      [
         'verification_code'   =>  $verification_code,
         'email'               =>  $email,
         'password'            =>  $password,
         'name'                =>  $name,
         'url_link'            =>  $url,
         'blade'               =>  $blade,
         'subject'             =>  $subject,
         'type'                =>  $type,
         'extra_data'          =>  $extra_data,
         'message'             =>  $message
      ];
      $job = (new SendEmailJob($details_of_email));
      dispatch($job);
   }
   
}
  