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

        $response = Http::withToken(env('AIRTABLE_API_KEY'))
            ->post($this->baseUrl, [
                'fields' => [
                    'Name' => $data['name'], 
                    'Description' => $data['description'],
                    'Status' => $data['status']
                ]
            ]);
      
        if ($response->successful()) {
            return $response->json()['id'];
        }
    
        \Log::error('Airtable create failed:', $response->json());
    
        return null;
    }
    
    public function getRecord($id)
    {
        
        $response = Http::withToken(env('AIRTABLE_API_KEY'))->get("{$this->baseUrl}/{$id}");

        if ($response->successful()) {
            return $response->json();
        }

        \Log::error('Failed to retrieve Airtable record', [
            'status' => $response->status(),
            'response' => $response->body(),
        ]);

        return null;
    }

    public function updateRecord($id, $data)
    {
        
        $response = Http::withToken(env('AIRTABLE_API_KEY'))
           ->patch("{$this->baseUrl}/{$id}", [
               'fields' => [
                  'Name' => $data['name'], 
                   'Description' => $data['description'],
                   'Status' => $data['status']
              ]
        ]);

        \Log::info('Airtable update response:', $response->json());
    
        if ($response->successful()) {
            return $response->json(); 
        }
    
        \Log::error('Airtable update failed:', $response->json());
    
        return null;
    }
    

    public function deleteRecord($id)
    {
        return Http::withToken(env('AIRTABLE_API_KEY'))->delete("{$this->baseUrl}/{$id}")->json();
    }
}


