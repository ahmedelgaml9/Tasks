<?php

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RecordTest extends TestCase
{
    /** @test */
    public function it_creates_a_new_record_in_airtable()
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
    public function it_reads_records_from_airtable()
    {
        // Mock Airtable API response for fetching records
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

        $response = $this->get(route('records.index'));

        $response->assertStatus(200);
        $response->assertViewHas('records', function ($records) {
            return $records[0]['fields']['Name'] === 'Sample Record';
        });
    }

    /** @test */
    public function it_deletes_a_record_in_airtable()
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

        $this->assertDatabaseMissing('records', [
            'id' => $record->id
        ]);
    }
}



