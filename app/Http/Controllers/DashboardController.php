<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff; // Adjust this to match your Staff model name

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        // Get all staff membersfor dashboard statistics
        $staff = Staff::all();
        
        return view('dashboard', compact('staff'));
    }
}