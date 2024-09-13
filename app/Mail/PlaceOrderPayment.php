<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PlaceOrderPayment extends Mailable{
    use Queueable, SerializesModels;

    
    public $order;

    public function __construct($order){
        $this->order = $order;
    }

    public function build(){
        return $this->view('emails.order_payment')
            ->subject('Your Order Confirmation')
            ->with(['order' => $this->order]);
    }
}