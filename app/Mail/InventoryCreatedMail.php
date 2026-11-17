<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InventoryCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $inventory;

    /**
     * @param  object  $inventory  Inventory payload with related display fields
     */
    public function __construct($inventory)
    {
        $this->inventory = $inventory;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $inventoryNo = $this->inventory->inventory_no ?? '';

        return $this->subject('Inventory Created - ' . $inventoryNo)
            ->view('emails.inventory_created')
            ->with([
                'inventory' => $this->inventory,
            ]);
    }
}
