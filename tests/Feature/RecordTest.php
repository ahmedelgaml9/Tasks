<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use App\Models\Record;

class RecordTest extends TestCase
{
   
    use RefreshDatabase;

    public function it_creates_a_new_record_in_airtable()
    {
      
        Http::fake([
            'api.airtable.com/v0/YOUR_BASE_ID/records' => Http::response([
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
        $response->assertSessionHas('status', 'Record created successfully.');

        $this->assertDatabaseHas('records', [
            'name' => 'Sample Record',
            'status' => 'Active'
        ]);
    }

    /** @test */
    public function it_reads_records_from_airtable()
    {
        
        Http::fake([
            'api.airtable.com/v0/YOUR_BASE_ID/records' => Http::response([
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
        $response->assertViewHas('records');
    }

  
    public function it_deletes_a_record_in_airtable()
    {
       
        Http::fake([
            'api.airtable.com/v0/YOUR_BASE_ID/records/rec123' => Http::response([], 200)
        ]);

        $record = Record::create([
            'name' => 'Sample Record',
            'description' => 'This is a test record',
            'status' => 'Active',
            'created_at' => now()
        ]);

        $response = $this->delete(route('records.destroy', $record->id));
        $response->assertStatus(302);
        $response->assertSessionHas('status', 'Record deleted successfully.');

        $this->assertDatabaseMissing('records', [
            'id' => $record->id
        ]);
    }
}
