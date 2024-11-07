<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\AirtableService;
use App\Http\Requests\RecordRequest;


class RecordController extends Controller
{
    
    public function index(AirtableService $airtable)
    {

        $response = $airtable->getRecords();
    
        $records = $response['records'] ?? [];

        return view('records.index', compact('records'));
    }
    
    public function create()
    {

        return view('records.create');
    }
    
    public function store(RecordRequest $request, AirtableService $airtable)
    {
       
        $data = $request->validated();
        $data = array_filter($data, function($key) {
            return $key !== '_token';
        }, ARRAY_FILTER_USE_KEY);
    
        $airtable->createRecord($data);
    
        return redirect()->route('records.index')->with('success', 'Record created successfully.');
    }
    
    
    public function edit($id, AirtableService $airtable)
    {

        $record = $airtable->getRecord($id);

        return view('records.edit', compact('record'));
    }
    

    public function update($id, RecordRequest $request, AirtableService $airtable)
    {
        $data = $request->validated();
        $data = array_filter($data, function($key) {
            return $key !== '_token';
        }, ARRAY_FILTER_USE_KEY);
    
        $airtable->updateRecord($id, $data);
    
        return redirect()->route('records.index')->with('success', 'Record updated successfully.');
    }
    
    
    public function destroy($id, AirtableService $airtable)
    {
        $airtable->deleteRecord($id);

        return redirect()->route('records.index')->with('success', 'Record deleted successfully.');
    }
    
}
