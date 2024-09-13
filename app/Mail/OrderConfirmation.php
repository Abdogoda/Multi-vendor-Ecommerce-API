<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable{
    use Queueable, SerializesModels;

    public $order;

    public function __construct($order){
        $this->order = $order;
    }

    public function build(){
        return $this->view('emails.order_confirmation')
            ->subject('Your Order Confirmation')
            ->with(['order' => $this->order]);
    }
}