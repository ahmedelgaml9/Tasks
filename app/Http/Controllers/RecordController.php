<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\AirtableService;

class RecordController extends Controller
{
    
    public function index(AirtableService $airtable)
    {

        $records = $airtable->getRecords();

        return view('records.index', compact('records'));
    }
    
    public function create()
    {

        return view('records.create');
    }
    
    public function store(RecordRequest $request, AirtableService $airtable)
    {

        $airtable->createRecord($request->validated());

        return redirect()->route('records.index')->with('success', 'Record created successfully.');
    }
    
    public function edit($id, AirtableService $airtable)
    {

        $record = $airtable->getRecord($id);

        return view('records.edit', compact('record'));
    }
    
    public function update(RecordRequest $request, $id, AirtableService $airtable)
    {

        $airtable->updateRecord($id, $request->validated());

        return redirect()->route('records.index')->with('success', 'Record updated successfully.');
    }
    
    public function destroy($id, AirtableService $airtable)
    {

        $airtable->deleteRecord($id);

        return redirect()->route('records.index')->with('success', 'Record deleted successfully.');
    }
    


}
