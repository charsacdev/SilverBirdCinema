<div>
    {{-- Dashboard Livewire --}}
    <div class="page-header">
            <h1 class="page-title">Home</h1>
            <select class="today-selector">
                <option>All time</option>
                <option>Today</option>
                <option>7days ago</option>
                <option>One month ago</option>
                <option>3 month ago</option>
            </select>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <section>
                    <div class="stat-header">
                        <div class="stat-icon blue">
                            <img src="vendor/images/Ticket.png">
                        </div>
                        <span class="stat-title">Total No. of Tickets</span>
                    </div>
                    <div class="stat-value">300</div>
                </section>
               
                <div class="stat-subtitle">For 25th May, 2025</div>
            </div>
            <div class="stat-card">
                <section>
                    <div class="stat-header">
                        <div class="stat-icon green">
                            <img src="vendor/images/Ticket.png">
                        </div>
                        <span class="stat-title">Total Used Tickets</span>
                    </div>
                    <div class="stat-value">187</div>
                </section>
              
                <div class="stat-subtitle">For 25th May, 2025</div>
            </div>
            <div class="stat-card">
                <section>
                    <div class="stat-header">
                        <div class="stat-icon red">
                            <img src="vendor/images/Ticket.png">
                        </div>
                        <span class="stat-title">Total Expired Tickets</span>
                    </div>
                    <div class="stat-value">48</div>
                </section>
                <div class="stat-subtitle">For 25th May, 2025</div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="activity-section">
            <div class="activity-header">
                <h2 class="activity-title">Recent Activity</h2>
                <a href="#" class="see-more">See More</a>
            </div>
            
            <div class="activity-tabs">
                <button class="tab active">Gift Vouchers</button>
                <button class="tab">VVIP Tickets</button>
                <button class="tab">Regular Tickets</button>
            </div>

            <div class="activity-controls">
                <select class="filter-dropdown">
                    <option> All Partners&nbsp;<i class="fas fa-chevron-down"></i></option>
                    <option>Wema Bank</option>
                    <option>Zenith Bank</option>  
                </select>
                <button class="date-range-picker" id="dateRangePicker">
                    <i class="fas fa-calendar"></i>
                    01 April - 03 May 2025
                </button>
            </div>

            <div class="table-container">
                <table class="activity-table">
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>Price</th>
                            <th>Scanned By</th>
                            <th>Partner Name</th>
                            <th>Date Generated <i class="fas fa-chevron-up"></i></th>
                            <th>Status <i class="fas fa-chevron-down"></i></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#086-GHADT7690</td>
                            <td>N8,500</td>
                            <td>Ngozi-Abuja</td>
                            <td>Kuda Microfinance Bank</td>
                            <td>7th May, 2025</td>
                            <td><span class="status-badge status-used">Used</span></td>
                            <td><a href="#" class="view-link">View batch</a></td>
                        </tr>
                        <tr>
                            <td>#086-GHADT7690</td>
                            <td>N8,500</td>
                            <td>Nil</td>
                            <td>Dangote Refineries</td>
                            <td>7th May, 2025</td>
                            <td><span class="status-badge status-expired">Expired</span></td>
                            <td><a href="#" class="view-link">View batch</a></td>
                        </tr>
                        <tr>
                            <td>#086-GHADT7690</td>
                            <td>N8,500</td>
                            <td>---</td>
                            <td>Wema Bank</td>
                            <td>7th May, 2025</td>
                            <td><span class="status-badge status-unused">Unused</span></td>
                            <td><a href="#" class="view-link">View batch</a></td>
                        </tr>
                        <tr>
                            <td>#086-GHADT7690</td>
                            <td>N8,500</td>
                            <td>---</td>
                            <td>Wema Bank</td>
                            <td>7th May, 2025</td>
                            <td><span class="status-badge status-unused">Unused</span></td>
                            <td><a href="#" class="view-link">View batch</a></td>
                        </tr>
                        <tr>
                            <td>#085-GHADT7690</td>
                            <td>N8,500</td>
                            <td>Ngozi-Abuja</td>
                            <td>Sterling</td>
                            <td>7th May, 2025</td>
                            <td><span class="status-badge status-used">Used</span></td>
                            <td><a href="#" class="view-link">View batch</a></td>
                        </tr>
                        <tr>
                            <td>#086-GHADT7690</td>
                            <td>N8,500</td>
                            <td>---</td>
                            <td>Kuda Microfinance Bank</td>
                            <td>7th May, 2025</td>
                            <td><span class="status-badge status-unused">Unused</span></td>
                            <td><a href="#" class="view-link">View batch</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
</div>
