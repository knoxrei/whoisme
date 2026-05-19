<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GateController extends Controller
{
    /**
     * Display the entry gate portal selection page.
     */
    public function index()
    {
        $title = 'Entry Portal';
        
        $torLink = config('services.gate.tor');
        $clearnetLink = config('services.gate.clearnet');
        
        return view('gate.portal', compact('title', 'torLink', 'clearnetLink'));
    }

    /**
     * Route and redirect connection through the Tor Network.
     */
    public function tor()
    {
        $torLink = config('services.gate.tor');
        return redirect()->away($torLink);
    }

    /**
     * Route and redirect connection through Clearnet.
     */
    public function clearnet()
    {
        $clearnetLink = config('services.gate.clearnet');
        return redirect()->away($clearnetLink);
    }
}
