<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEnquiryEmail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * The TaskAssigment instance.
     *
     * @var Order
     */
    public $enquiryObj;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($enquiry){
        $this->enquiryObj = $enquiry;
    }
    /**
     * Build the message.
     *
     * @return $this
     */

    public function build(){

        return $this->view('emails.send_enquiry_mail')
            ->with([ 'enquiry'=> $this->enquiryObj ])
            ->subject($this->enquiryObj->subject)
            ->replyTo($this->enquiryObj->sender_email, $this->enquiryObj->sender_name);
            //->from('Testingmail@gmail.com', $subject)
            /* ->cc($address, $name)
             ->bcc($address, $name)
             ->replyTo($address, $name)*/

    }
}