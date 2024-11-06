<?php


namespace App\Services;

use Illuminate\Support\Facades\Http;

class AirtableService
{
    private $baseUrl;
    
    public function __construct()
    {
        $this->baseUrl = 'https://api.airtable.com/v0/' . env('AIRTABLE_BASE_ID') . '/' . env('AIRTABLE_TABLE_NAME');
    }

    public function getRecords()
    {
        return Http::withToken(env('AIRTABLE_API_KEY'))->get($this->baseUrl)->json();
    }

    public function createRecord($data)
    {
        return Http::withToken(env('AIRTABLE_API_KEY'))->post($this->baseUrl, ['fields' => $data])->json();
    }

    public function updateRecord($id, $data)
    {
        return Http::withToken(env('AIRTABLE_API_KEY'))->patch("{$this->baseUrl}/{$id}", ['fields' => $data])->json();
    }

    public function deleteRecord($id)
    {
        return Http::withToken(env('AIRTABLE_API_KEY'))->delete("{$this->baseUrl}/{$id}")->json();
    }
}


