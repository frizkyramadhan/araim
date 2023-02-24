<?php

namespace App\Http\Controllers\Api;

use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\InventoryController;

class InventoryApiController extends Controller
{

    protected $inventoryController;

    public function __construct(InventoryController $inventoryController)
    {
        $this->inventoryController = $inventoryController;
    }

    public function qrcode_json($id)
    {
        // take qrcodeJson method from InventoryController
        $qrcode = $this->inventoryController->qrcodeJson($id);
        return $qrcode;
    }
}
