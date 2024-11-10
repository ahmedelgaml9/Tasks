<?php

use Illuminate\Support\Facades\Http;
use App\Models\Record;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_new_record_in_database_and_airtable()
    {
        
        Http::fake([
            'api.airtable.com/v0/' . env('AIRTABLE_BASE_ID') . '/' . env('AIRTABLE_TABLE_NAME') => Http::response([
                'id' => 'rec123',
                'fields' => [
                    'Name' => 'Sample Record',
                    'Description' => 'This is a test record',
                    'Status' => 'Active',
                    'Created_At' => now()->toDateString(),
                ]
            ], 200)
        ]);

        $data = [
            'name' => 'Sample Record',
            'description' => 'This is a test record',
            'status' => 'Active',
            'created_at' => now()->toDateString(),
        ];

        $response = $this->post(route('records.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Record created successfully.');

        $this->assertDatabaseHas('records', [
            'name' => 'Sample Record',
            'description' => 'This is a test record',
            'status' => 'Active',
        ]);

       
        Http::assertSent(function ($request) use ($data) {
            return $request->url() === 'https://api.airtable.com/v0/' . env('AIRTABLE_BASE_ID') . '/' . env('AIRTABLE_TABLE_NAME') &&
                   $request['fields'] == [
                       'Name' => $data['name'],
                       'Description' => $data['description'],
                       'Status' => $data['status']
                   ];
        });
    }

    /** @test */
    public function it_reads_records_from_database_and_airtable()
    {
        // Mock Airtable API response for retrieving records
        Http::fake([
            'api.airtable.com/v0/' . env('AIRTABLE_BASE_ID') . '/' . env('AIRTABLE_TABLE_NAME') => Http::response([
                'records' => [
                    [
                        'id' => 'rec123',
                        'fields' => [
                            'Name' => 'Sample Record',
                            'Description' => 'This is a test record',
                            'Status' => 'Active',
                            'Created_At' => now()->toDateString(),
                        ]
                    ]
                ]
            ], 200)
        ]);

        Record::create([
            'name' => 'Sample Record',
            'description' => 'This is a test record',
            'status' => 'Active',
            'created_at' => now(),
        ]);

        $response = $this->get(route('records.index'));

        $response->assertStatus(200);
        $response->assertViewHas('records', function ($records) {
            return $records[0]['fields']['Name'] === 'Sample Record';
        });
    }

 
    public function it_updates_a_record_in_database_and_airtable()
    {
        // Define data for Airtable mock response and database assertion
        $data = [
            'name' => 'Updated Record',
            'description' => 'This is an updated record',
            'status' => 'Inactive',
        ];
    
        Http::fake([
            'api.airtable.com/v0/' . env('AIRTABLE_BASE_ID') . '/' . env('AIRTABLE_TABLE_NAME') . '/rec123' => 
                Http::response(['id' => 'rec123', 'fields' => $data], 200)
        ]);
    
        $record = Record::create([
            'name' => 'Sample Record',
            'description' => 'This is a test record',
            'status' => 'Active',
            'created_at' => now(),
        ]);
    
        $response = $this->put(route('records.update', $record->id), $data);
        $response->assertStatus(302)->assertSessionHas('success', 'Record updated successfully.');
        $this->assertDatabaseHas('records', ['id' => $record->id] + $data);
    
        Http::assertSent(function ($request) use ($data) {
            return $request->url() === 'https://api.airtable.com/v0/' . env('AIRTABLE_BASE_ID') . '/' . env('AIRTABLE_TABLE_NAME') . '/rec123' &&
                   $request['fields'] == [
                       'Name' => $data['name'],
                       'Description' => $data['description'],
                       'Status' => $data['status']
                   ];
        });
    }
    
    /** @test */
    public function it_deletes_a_record_in_database_and_airtable()
    {
        // Mock Airtable API response for record deletion
        Http::fake([
            'api.airtable.com/v0/' . env('AIRTABLE_BASE_ID') . '/' . env('AIRTABLE_TABLE_NAME') . '/rec123' => Http::response([], 200)
        ]);

        $record = Record::create([
            'name' => 'Sample Record',
            'description' => 'This is a test record',
            'status' => 'Active',
            'created_at' => now()
        ]);

        $response = $this->delete(route('records.destroy', $record->id));

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Record deleted successfully.');

        // Assert record was removed from database
        $this->assertDatabaseMissing('records', [
            'id' => $record->id
        ]);

        Http::assertSent(function ($request) use ($record) {
            return $request->url() === 'https://api.airtable.com/v0/' . env('AIRTABLE_BASE_ID') . '/' . env('AIRTABLE_TABLE_NAME') . '/rec123';
        });
    }
}
