<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\AirtableService;
use App\Http\Requests\RecordRequest;
use App\Models\Record;


class RecordController extends Controller
{
    
    public function index()
    {
        //$response = $airtable->getRecords();
        //$records = $response['records'] ?? [];

        $records = Record::paginate();

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

        $record = Record::create($data);
        $airtableId = $airtable->createRecord($data);
        $record->update(['airtable_id' => $airtableId]);

        return redirect()->route('records.index')->with('success', 'Record created successfully.');
    }
    
    
    public function edit($id, AirtableService $airtable)
    {

        $record = Record::findOrFail($id);

        return view('records.edit', compact('record'));
    }
    

    public function update($id, RecordRequest $request, AirtableService $airtable)
    {

        $data = $request->validated();
        $record = Record::findOrFail($id);
        $record->update($data);
        $airtable->updateRecord($record->airtable_id, $data);
    
        return redirect()->route('records.index')->with('success', 'Record updated successfully.');
    }
    
    
    public function destroy($id, AirtableService $airtable)
    {

        $record = Record::findOrFail($id);
        $record->delete();
        $airtable->deleteRecord($record->airtable_id);

        return redirect()->route('records.index')->with('success', 'Record deleted successfully.');
    }
    
}
