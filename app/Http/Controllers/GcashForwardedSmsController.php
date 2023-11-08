<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GcashForwardedSmsController extends Controller
{
    public function index(Request $request)
    {
        info('index');
        info($request->all());
    }

    public function create(Request $request)
    {
        info('create');
        info($request->all());
    }

    public function store(Request $request)
    {
        info('store');
        info($request->all());
    }

    public function show(Request $request, string $id)
    {
        info('show');
        info($request->all());
    }

    public function edit(Request $request, string $id)
    {
        info('edit');
        info($request->all());
    }

    public function update(Request $request, string $id)
    {
        info('update');
        info($request->all());
    }

    public function destroy(Request $request, string $id)
    {
        info('destroy');
        info($request->all());
    }
}
