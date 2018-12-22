<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * The TaskAssigment instance.
     *
     * @var Order
     */
    public $emailTemplateObj;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($emailTemplate){
        $this->emailTemplateObj = $emailTemplate;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function AddReplyTo($reply_to_email='', $name=''){

        if(!empty(trim($reply_to_email) && !empty(trim($name)))) {
            $this->replyTo($reply_to_email, $name);
        }
        return $this;
    }
    public function build(){

        $subject = $this->emailTemplateObj->subject;
        $body    = $this->emailTemplateObj->template_content;
        $name = "";
        $reply_to_email = "";
        if(!empty($this->emailTemplateObj->name)){
            $name = $this->emailTemplateObj->name;
        }
        if(!empty($this->emailTemplateObj->reply_to_email)){
            $reply_to_email = $this->emailTemplateObj->reply_to_email;
        }
        return $this->view('emails.sendmail')
            ->with([ 'body'=> $body ])
            ->subject($subject)
            ->AddReplyTo($reply_to_email, $name);
        //->from('Testingmail@gmail.com', $subject)
        /* ->cc($address, $name)
         ->bcc($address, $name)
         ->replyTo($address, $name)*/

    }
}