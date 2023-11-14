<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    
    /** @test */
    public function can_upload_a_pdf(): void
    {        
        Storage::fake('pdfs');

        $file = UploadedFile::fake()
            ->createWithContent('document.pdf', base64_encode('This is some content.'));

        $this->post(route('process-pdf', [
            'pdf' => $file,
        ]))->assertOk();

        $this->assertDatabaseHas('pdf_uploads', [
            'extracted_text' => 'This is some content.',
            'uploaded_at' => Carbon::now(),
        ]);
    }
    
    /** @test */
    public function cannot_upload_a_pdf_when_pdf_is_null(): void
    {        
        Storage::fake('pdfs');

        $response = $this
            ->followingRedirects()
            ->post(route('pdf.process', [
                'pdf' => null,
            ]))
            ->assertOk();

        $response->assertSessionHasErrors([
            'pdf' => 'The pdf field is required.'
        ]);
    }
}
