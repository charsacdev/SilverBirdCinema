<div>
    {{-- Generate Ticket. --}}
    <div class="generate-container">
        <div class="generate-form-section">
            <h1 class="page-title">Generate Tickets</h1>
            <p class="page-description">Please fill the information below to generate new tickets.</p>

            <form id="generateTicketsForm" class="generate-form" wire:submit.prevent="generateAndDownloadTickets">
                <div class="form-group">
                    <label for="partnerName">Partner Name</label>
                    <div class="select-wrapperd">
                        <select wire:model.live="partnerName" class="form-select" required>
                            <option value="">Select Partner</option>
                            @foreach ($partners as $partner)
                                <option value="{{ $partner->partner_name }}">{{ $partner->partner_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <a href="/partners" class="add-link">Add new partner</a>
                    @error('partnerName') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="ticketCount">No. of Tickets</label>
                    <input wire:model.live="ticketCount" type="number" id="ticketCount" class="form-input" value="1" min="1" required>
                    @error('ticketCount') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="ticketType">Type of Ticket</label>
                    <div class="select-wrapper">
                        <select wire:model.live="ticketType" class="form-select" required>
                            <option value="">Select Ticket Type</option>
                            <option value="Gift Vouchers">Gift Vouchers</option>
                            <option value="VVIP Tickets">VVIP Tickets</option>
                            <option value="Regular Tickets">Regular Tickets</option>
                        </select>
                        <i class="fas fa-chevron-down select-icon"></i>
                    </div>
                    @error('ticketType') <span class="error">{{ $message }}</span> @enderror
                </div>

              
                <div class="form-group" id="priceGroup">
                    <label for="ticketPrice">Price per Ticket(N)</label>
                    <input wire:model.live="ticketPrice" type="number" id="ticketPrice" class="form-input" placeholder="e.g., N8,500" required>
                    @error('ticketPrice') <span class="error">{{ $message }}</span> @enderror
                </div>
        

                <div class="form-group">
                    <label for="dateRange">Date Range</label>
                    <div class="date-picker-wrapper">
                        <input wire:model.live="dateRange" type="text" id="dateRange" class="form-input date-input" readonly required>
                        <i class="fas fa-calendar date-icon"></i>
                    </div>
                    @error('fromDate') <span class="error">{{ $message }}</span> @enderror
                    @error('toDate') <span class="error">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="generate-btn">Generate Tickets</button>
            </form>
        </div>

        <div class="preview-section">
            <div class="preview-header">
                <i class="fas fa-eye preview-icon"></i>
                <h2 class="preview-title">Preview</h2>
            </div>

            <div class="preview-card">
                <div class="preview-item">
                    <span class="preview-label">Partner Name:</span>
                    <span class="preview-value" id="previewPartner">{{ $partnerName }}</span>
                </div>

                <div class="preview-item">
                    <span class="preview-label">No. of Tickets:</span>
                    <span class="preview-value" id="previewCount">{{ $ticketCount }}</span>
                </div>

                <div class="preview-item">
                    <span class="preview-label">Ticket Type</span>
                    <span class="preview-value" id="previewType">{{ $ticketType }}</span>
                </div>

               
                <div class="preview-item" id="previewPriceItem">
                    <span class="preview-label">Price per Ticket</span>
                    <span class="preview-value" id="previewPrice">{{ $ticketPrice }}</span>
                </div>
               

                <div class="preview-item">
                    <span class="preview-label">Validity Range</span>
                    <span class="preview-value" id="previewDate">{{ $dateRange }}</span>
                </div>
            </div>
        </div>
    </div>

    

    @script
    <script>
        
        $(document).ready(function() {
            $('#dateRange').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'DD MMM YYYY'
                }
            }, function(start, end, label) {
                @this.set('dateRange', start.format('DD MMM YYYY') + ' - ' + end.format('DD MMM YYYY'));
                @this.call('updateDatesFromRange');
            });
        });

        Livewire.on('ticketsGenerated', () => {
            document.getElementById('successModal').style.display = 'flex';
        });

        Livewire.on('pdfDownloadReady', (data) => {
           
            // Livewire passes event data as an array, so access the first element
            var downloadUrl = data[0].url;
            var downloadname = data[0].name; // The batch ID

        
            // Update the download button in the modal to point to the new URL
            var downloadPdfBtn = document.getElementById('downloadPdfBtn');
            downloadPdfBtn.href = downloadUrl;
            //alert(downloadPdfBtn.href);
            downloadPdfBtn.download = downloadname; // Suggest a filename for the download
            downloadPdfBtn.target = '_blank'; // Open in new tab/window for download
            downloadPdfBtn.classList.remove('action-btn-disabled'); // Make button clickable if it was disabled
            downloadPdfBtn.removeAttribute('onclick'); // Remove old onclick
            downloadPdfBtn.textContent = 'Download PDF'; // Update text
        
        });


        document.getElementById('closeSuccessModal').addEventListener('click', () => {
            document.getElementById('successModal').style.display = 'none';
        });

        // Optional: Listen for changes on ticketType to reset price if not applicable
        document.querySelector('select[wire\\:model\\.live="ticketType"]').addEventListener('change', function() {
            const selectedType = this.value;
            if (selectedType === 'Gift Vouchers') {
                @this.set('ticketPrice', 'N/A'); // Set to N/A or empty if Gift Voucher
            } else if (@this.get('ticketPrice') === 'N/A' || @this.get('ticketPrice') === 'N0.00') {
                 @this.set('ticketPrice', ''); // Clear if it was N/A or default 'N0.00' and type changed to paid
            }
        });
    </script>
    @endscript

    {{-- Success Modal Generate Tickets --}}
    <div class="modal-overlay" id="successModal" wire:ignore.self>
        <div class="success-modal">
            <button class="modal-close" id="closeSuccessModal">
                <i class="fas fa-times"></i>
            </button>
            <div class="success-icon">
                <img src="{{ asset('vendor/images/success.png') }}">
            </div>
            <h3 class="success-title">Your tickets have been generated successfully.</h3>

            <a href="" target="" class="action-btn primary" id="downloadPdfBtn" wire:ignore>
                Download PDF (Check your browser's download)
            </a>

            <button class="action-btn secondary" id="emailBtn">
                <span>Send via Email</span>
                <img src="https://upload.wikimedia.org/wikipedia/commons/7/7e/Gmail_icon_%282020%29.svg" alt="Gmail" class="btn-icon">
            </button>

            <button class="action-btn secondary" id="whatsappBtn">
                <span>Send via WhatsApp</span>
                <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp" class="btn-icon">
            </button>
        </div>
    </div>
</div>