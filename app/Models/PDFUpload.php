<?php

namespace App\Models;

use Database\Factories\PDFUploadFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PDFUpload extends Model
{
    use HasFactory;

    protected string $table = 'pdf_uploads';

    protected array $fillable = [
        'extracted_text',
        'uploaded_at',
    ];

    protected static function newFactory(): PDFUploadFactory
    {
        return PDFUploadFactory::new();
    }
}
