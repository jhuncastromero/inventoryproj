<?php

namespace App\Http\Controllers;

use App\reportsetting;
use Illuminate\Http\Request;

class ReportsettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\reportsetting  $reportsetting
     * @return \Illuminate\Http\Response
     */
    public function show(reportsetting $reportsetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\reportsetting  $reportsetting
     * @return \Illuminate\Http\Response
     */
    public function edit(reportsetting $reportsetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\reportsetting  $reportsetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, reportsetting $reportsetting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\reportsetting  $reportsetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(reportsetting $reportsetting)
    {
        //
    }

    public function report_settings($module_name)
    {
        //return $report_setting = reportsetting::return_report_setting($module_name);
    }
}
