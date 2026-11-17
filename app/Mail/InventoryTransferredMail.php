<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InventoryTransferredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $inventory;
    public $fromInventory;

    /**
     * @param  object       $inventory      New / destination inventory payload
     * @param  object|null  $fromInventory  Source inventory payload (optional)
     */
    public function __construct($inventory, $fromInventory = null)
    {
        $this->inventory = $inventory;
        $this->fromInventory = $fromInventory;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $inventoryNo = $this->inventory->inventory_no ?? '';

        return $this->subject('Inventory Transferred - ' . $inventoryNo)
            ->view('emails.inventory_transferred')
            ->with([
                'inventory' => $this->inventory,
                'fromInventory' => $this->fromInventory,
            ]);
    }
}
