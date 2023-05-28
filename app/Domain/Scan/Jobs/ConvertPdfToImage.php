<?php

namespace Domain\Scan\Jobs;

use App\Exceptions\FileConversionException;
use Domain\Scan\Events\ScanStarted;
use Domain\Scan\Models\Scan;
use Grafika\EditorInterface;
use Grafika\Grafika;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use mikehaertl\pdftk\Pdf;
use TitasGailius\Terminal\Terminal;

class ConvertPdfToImage implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    /**
     * @var Scan
     */
    protected $scanId;

    protected EditorInterface $editor;

    protected array $filePaths;

    protected string $outputPath;

    public function __construct($scanId, array $filePaths, string $outputPath)
    {
        $this->filePaths = $filePaths;
        $this->outputPath = $outputPath;
        $this->scanId = $scanId;
    }

    /**
     * @throws FileConversionException
     */
    public function handle()
    {
        $scan = Scan::find($this->scanId);

        ScanStarted::dispatch($scan);

        $this->editor = Grafika::createEditor();

        $imagePaths = [];
        $totalPagesCount = 0;
        $tempDir = config('saham.storage.tmp_dir') . '/' . date('dmYHis');

        if (! File::isDirectory($this->outputPath)) {
            File::makeDirectory($this->outputPath, 0777, true);
        }

        if (! File::isDirectory($tempDir)) {
            File::makeDirectory($tempDir, 0777, true);
        }

        foreach ($this->filePaths as $filePath) {
            if (! File::isFile($filePath)) {
                throw new FileConversionException("[FileConversionException] Le fichier à traiter n'existe pas.");
            }

            $fileNameWithoutExtension = str_replace('.pdf', '', File::basename($filePath));

            $burster = new Pdf($filePath);
            $splitSucceeded = $burster->burst("{$tempDir}/{$fileNameWithoutExtension}_%d.pdf");
            $pdfFiles = glob("{$tempDir}/*.pdf");
            $totalPagesCount += count($pdfFiles);

            if (! $splitSucceeded) {
                throw new FileConversionException(sprintf("[FileConversionException] Le découpage du fichier a échoué. %s  %s", $filePath, $burster->getError()));
            }

            foreach ($pdfFiles as $file) {
                $imagePath = $this->outputPath . '/' . str_replace('.pdf', '.jpeg', File::basename($file));

                $response = Terminal::builder()
                    ->timeout(60 * 60)
                    ->with('pdf', $file)
                    ->with('image', $imagePath)
                    ->run('convert -density 500 {{$pdf}} {{$image}}');

                if (! $response->successful()) {
                    throw new FileConversionException(sprintf('[FileConversionException] La conversion du fichier en image a échoué. %s', implode(',', $response->lines())));
                }

                $response = Terminal::builder()
                    ->timeout(60 * 60)
                    ->with('image', $imagePath)
                    ->run('convert {{$image}} -background "gray(255)" -trim +repage {{$image}}');

                if (! $response->successful()) {
                    throw new FileConversionException(sprintf("[FileConversionException] Le traitement de l'image convertie a échoué. %s", implode(',', $response->lines())));
                }

                $imagePaths[] = $this->cropImage($imagePath);
            }
        }

        $scan->update([
            'total_count' => $totalPagesCount,
            'files_paths' => ['image_paths' => $imagePaths, 'output_path' => $this->outputPath],
        ]);

        app('queue.worker')->shouldQuit  = 1;
    }

    private function cropImage(string $imagePath): string
    {
        $this->editor->open($image, $imagePath);
        $this->editor->crop($image, 1400, 1400, 'smart');
        $this->editor->save($image, $imagePath);
        $this->editor->free($image);

        return $imagePath;
    }
}
