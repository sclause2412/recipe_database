<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Unit::class, 'unit');
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('units.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {

        return view('units.show', ['unit' => $unit]);
    }

}
