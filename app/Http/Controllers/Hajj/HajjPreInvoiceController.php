<?php

namespace App\Http\Controllers\Hajj;

use App\Http\Controllers\Controller;

class HajjPreInvoiceController extends Controller
{
    public function index()
    {
        return view('hajj.pre_invoices.index');
    }
}

