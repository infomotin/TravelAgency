<?php

namespace App\Http\Controllers\Hajj;

use App\Http\Controllers\Controller;

class MoallemTransferController extends Controller
{
    public function index()
    {
        return view('hajj.moallem_transfers.index');
    }
}

