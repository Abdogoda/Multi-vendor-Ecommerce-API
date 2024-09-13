<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VendorNotification extends Mailable{
    use Queueable, SerializesModels;

    public $vendor;
    public $items;

    public function __construct($vendor, $items){
        $this->vendor = $vendor;
        $this->items = $items;
    }

    public function build(){
        return $this->view('emails.vendor_notification')
            ->subject('New Order Received')
            ->with(['vendor' => $this->vendor, 'items' => $this->items]);
    }
}