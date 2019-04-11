<?php

namespace App\Http\Controllers;

use App\equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('module2.equipment.home');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('module2.equipment.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function show(equipment $equipment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function edit(equipment $equipment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, equipment $equipment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function destroy(equipment $equipment)
    {
        //
    }
}
