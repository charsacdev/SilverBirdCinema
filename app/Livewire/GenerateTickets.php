<?php

namespace App\Livewire;

use Livewire\Component;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;
use setasign\Fpdi\Tcpdf\Fpdi; // For PDF generation
use App\Models\ParternsManagement;
use App\Models\TicketHistoryManagement;
use App\Models\GeneratingTicket;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class GenerateTickets extends Component
{
    public $partnerName;
    public $ticketCount = 1;
    public $ticketType;
    public $ticketPrice;
    public $dateRange;
    public $fromDate;
    public $toDate;

    protected $listeners = ['dateRangeChanged' => 'updateDatesFromRange']; // Listen for JS date change

    public function mount()
    {
        // Default date range for demonstration; ensure it's in a parseable format
        $this->dateRange = Carbon::now()->format('d M Y') . ' - ' . Carbon::now()->addDays(30)->format('d M Y');
        $this->updateDatesFromRange();
        $this->ticketPrice = 'N0.00'; // Default value for price
    }

    // This method is called explicitly from JavaScript when dateRangePicker updates
    public function updateDatesFromRange($newDateRange = null)
    {
        // Use the passed value if available, otherwise use the Livewire property
        $range = $newDateRange ?? $this->dateRange;
        $dates = explode(' - ', $range);

        if (count($dates) === 2) {
            $this->fromDate = Carbon::parse(trim($dates[0]))->format('Y-m-d');
            $this->toDate = Carbon::parse(trim($dates[1]))->format('Y-m-d');
        }
    }

    public function generateAndDownloadTickets()
    {
        
        // Define validation rules
        $rules = [
            'partnerName' => 'required|string|exists:parterns_management,partner_name',
            'ticketCount' => 'required|integer|min:1',
            'ticketType' => 'required|string',
            'fromDate' => 'required|date',
            'toDate' => 'required|date|after_or_equal:fromDate',
        ];

        // Run validation
        $this->validate($rules);

       
        // Initialize PDF object
        $pdf = new Fpdi();
        $pdf->SetAutoPageBreak(true, 10); // Enable auto page breaks with 10mm margin at bottom

        // Assuming your cinema logo path
        $cinemaLogoPath = public_path('vendor/images/logo.png');
        if (!file_exists($cinemaLogoPath)) {
            $cinemaLogoPath = null;
        }

         //-------Save Ticket Information in generate Ticket Table---
        GeneratingTicket::create([       
                'partner_id'=>ParternsManagement::where('partner_name', $this->partnerName)->value('id'),
                'user_id'=>auth()->id(),
                'num_tickets'=>$this->ticketCount,
                'ticket_type' => $this->ticketType,
                'ticket_price' => $this->ticketPrice,
                'from_date' => $this->fromDate,
                'to_date' => $this->toDate,
        ]);


        // Loop to generate each individual ticket
        for ($i = 0; $i < $this->ticketCount; $i++) {

             // Generate a unique ID for this batch of tickets
             $generateId = strtoupper(Str::random(6));

            // Generate a unique ticket ID for each ticket in the batch
             $ticketId = 'TKT-' . $generateId;

           
            // --- QR Code Generation ---
            $qrCode = new QrCode($ticketId);

            $writer = new PngWriter();
            $qrCodeResult = $writer->write($qrCode);
            $qrCodeImage = $qrCodeResult->getString(); // Get image data as string

            // Store QR code image temporarily to embed in PDF
            $qrCodeTempPath = tempnam(sys_get_temp_dir(), 'qrcode_');
            file_put_contents($qrCodeTempPath, $qrCodeImage);

            // --- PDF Page for Current Ticket ---
            $pdf->AddPage('L', 'mm', [182, 66]); // Landscape ticket-style page

            // Margins
            $leftMargin = 10;
            $topMargin = 10;

            // Total page dimensions
            $pageWidth = $pdf->GetPageWidth();
            $pageHeight = $pdf->GetPageHeight();

            // === LEFT COLUMN: Ticket Image or Poster ===
            switch (strtolower($this->ticketType)) {
                case 'regular tickets':
                    $ticketPosterPath = public_path('vendor/images/regular.png');
                    break;
                case 'vvip tickets':
                    $ticketPosterPath = public_path('vendor/images/vvip.png');
                    break;
                case 'gift vouchers':
                    $ticketPosterPath = public_path('vendor/images/gift.png');
                    break;
                default:
                    $ticketPosterPath = public_path('vendor/images/regular.png');
                    break;
            }

            $imageWidth = 90;
            $imageHeight = 100;
            $imageX = 0;
            $imageY = 0; // Vertically center

            if (!empty($ticketPosterPath)) {
                $pdf->Image($ticketPosterPath, $imageX, $imageY, $imageWidth, $imageHeight, '', '', '', true, 300, '', false, false, 0);
            }

            // === MIDDLE COLUMN: Ticket Details ===
            $middleX = $imageX + $imageWidth + 10;
            $pdf->SetFont('helvetica', 'B', 13);
            $pdf->SetXY($middleX, 20);
            $pdf->MultiCell(80, 8, strtoupper($this->ticketType . ' (' . $this->partnerName . ')'), 0, 'L');

            $pdf->SetFont('helvetica', '', 10);
            $pdf->SetXY($middleX, 30);
            $pdf->Cell(80, 6, 'This ticket is valid from:', 0, 1, 'L');

            $pdf->SetFont('helvetica', 'B', 11);
            $pdf->SetXY($middleX, 36);
            $pdf->Cell(80, 6, Carbon::parse($this->fromDate)->format('jS F Y') . ' - ' . Carbon::parse($this->toDate)->format('jS F Y'), 0, 1, 'L');

            $pdf->SetFont('helvetica', '', 10);
            $pdf->SetXY($middleX, 48);
            $pdf->Cell(80, 6, 'Ticket ID:', 0, 0, 'L');

            $pdf->SetFont('helvetica', 'B', 11);
            $pdf->SetXY($middleX + 20, 48);
            $pdf->Cell(80, 6, '#' . $ticketId, 0, 1, 'L');

            if (in_array($this->ticketType, ['Regular Tickets', 'VVIP Tickets'])) {
                $pdf->SetFont('helvetica', 'B', 11);
                $pdf->SetXY($middleX, 58);
                $pdf->Cell(80, 6, 'Price: ' . $this->ticketPrice, 0, 1, 'L');
            }

            // === RIGHT COLUMN: QR Code + Logo ===
            $qrCodeSize = 60;
            $qrX = $pageWidth - $qrCodeSize - 20;
            $qrY = 25;

            $pdf->Image($qrCodeTempPath, $qrX, $qrY, $qrCodeSize, $qrCodeSize, 'PNG');
            unlink($qrCodeTempPath); // Delete temp QR

            // QR Label
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetXY($qrX, $qrY - 6);
            $pdf->Cell($qrCodeSize, 5, 'Scan QR Code', 0, 1, 'C');

            // Logo beneath QR
            if ($cinemaLogoPath) {
                $logoWidth = 25;
                $logoHeight = 10;
                $logoX = $qrX + ($qrCodeSize - $logoWidth) / 2;
                $logoY = $qrY + $qrCodeSize + 6;
                $pdf->Image($cinemaLogoPath, $logoX, $logoY, $logoWidth, $logoHeight, '', '', '', true);
            }

           

            // --- Save Ticket Information to Database ---
            TicketHistoryManagement::create([
                'partner_id' => ParternsManagement::where('partner_name', $this->partnerName)->value('id'),
                'user_id' => auth()->id(), // Assuming a user is logged in
                'scanner_id'=>"",
                'generate_id' => $generateId, // Store as string
                'ticket_id' => $ticketId,
                'ticket_type' => $this->ticketType,
                'ticket_price' => $this->ticketPrice,
                'ticket_status' => 'generated', // Default status
                'ticket_qrcode' => $ticketId, // Store the JSON string encoded in QR
                'from_date' => $this->fromDate,
                'to_date' => $this->toDate,
            ]);
        }

            // 1. Capture PDF content as a string
            $pdfContent = $pdf->Output('', 'S');

            // 2. Define the path for the new folder within the public directory
            // I've named it 'generated_tickets_pdfs' to be distinct.
            $publicFolderPath = public_path('generated_tickets_pdfs');

            // 3. Ensure the folder exists. Create it if it doesn't.
            if (!File::isDirectory($publicFolderPath)) {
                File::makeDirectory($publicFolderPath, 0755, true); // 0755 permissions, recursive creation
            }

            // 4. Define the filename for the PDF
            $filename = 'tickets_batch_' . $generateId . '_' . Carbon::now()->format('YmdHis') . '.pdf';
            $filePath = $publicFolderPath . '/' . $filename;

            // 5. Store the PDF file directly in the public folder
            File::put($filePath, $pdfContent);

            // 6. Generate the public URL using the asset() helper
            $downloadUrl = asset('generated_tickets_pdfs/' . $filename);

            // 7. Dispatch the event to the frontend with the public URL and the desired filename
            $this->dispatch('pdfDownloadReady', [
                'url' => $downloadUrl,
                'name' => 'tickets_' . Str::uuid() . '.pdf' // This is the filename the browser will suggest
            ]);
       
            // return response()->download($tempPdfPath, 'tickets_' . Str::uuid() . '.pdf', [
            //     'Content-Type' => 'application/pdf',
            // ])->deleteFileAfterSend(true);

            #dd($idaa);

        
        // Emit an event to show the success modal on the frontend
        $this->dispatch('ticketsGenerated');
    }

    public function render()
    {
        $partners = ParternsManagement::all();
        return view('livewire.generate-tickets', compact('partners'));
    }
}