<?php

namespace Database\Factories;

use App\Models\PDFUpload;
use Illuminate\Database\Eloquent\Factories\Factory;

class PDFUploadFactory extends Factory
{
    protected $model = PDFUpload::class;

    public function definition(): array
    {
        return [
            'extracted_text',
            'upload_time',
        ];
    }
}
