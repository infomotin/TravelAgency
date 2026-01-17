<?php

namespace App\Http\Controllers\Hajj;

use App\Http\Controllers\Controller;

class HajjInvoiceController extends Controller
{
    public function index()
    {
        return view('hajj.invoices.index');
    }
}

