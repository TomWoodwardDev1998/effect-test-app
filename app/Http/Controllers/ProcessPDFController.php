<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePDFContentRequest;
use App\Models\PDFUpload;
use Aws\Textract\TextractClient;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class ProcessPDFController extends Controller
{
    public function __invoke(StorePDFContentRequest $request)
    {
        $file = $request->file('pdf')->store('sample-pdfs');

        $absolutePath = Storage::path($file);

        $s3 = App::make('aws')->createClient('s3');
        $s3->putObject(array(
            'Bucket'     => config('aws.bucket'),
            'Key'        => basename($file),
            'SourceFile' => $absolutePath,
        ));

        $uploadedPDF = new TextractClient([
            'version' => 'latest',
            'region' => config('aws.region'),
            'credentials' => [
                'key' => config('aws.credentials.key'),
                'secret' => config('aws.credentials.secret'),
            ],
        ]);

        $result = $uploadedPDF->startDocumentTextDetection([
            'DocumentLocation' => [
                'Bytes' => [
                    'S3Object' => [
                        'Bucket' => config('aws.bucket'),
                        'Name' => basename($file),
                    ],
                ],
            ],
        ]);

        PDFUpload::create([
            'extracted_text' => $result,
            'uploaded_at' => Carbon::now(),
        ]);

        return response()->json(['message' => 'Request processed successfully']);
    }
}
