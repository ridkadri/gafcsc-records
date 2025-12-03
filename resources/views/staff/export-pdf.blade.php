<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>GAFCSC Staff Directory Export</title>
    <style>
        body {
            font-family: serif;
            font-size: 9pt;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        
        .header h1 {
            color: #333;
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        
        .header .meta {
            color: #666;
            font-size: 11px;
        }
        
        .stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #2563eb;
        }
        
        .stat-label {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        
        .section-title {
            background-color: #374151;
            color: white;
            padding: 10px;
            margin-top: 25px;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th {
            background-color: #374151;
            color: white;
            font-weight: bold;
            padding: 8px 5px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
        }
        
        td {
            padding: 8px 5px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9px;
        }
        
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .rank-badge {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: 500;
        }
        
        .grade-badge {
            background-color: #ddd6fe;
            color: #5b21b6;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: 500;
        }

        .status-badge {
            background-color: #d1fae5;
            color: #065f46;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: 500;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6b7280;
            font-style: italic;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Ghana Armed Forces Command & Staff College</h1>
        <h2 style="margin: 10px 0; font-size: 16px;">Staff Directory</h2>
        <div class="meta">
            Generated on {{ now()->format('F j, Y \a\t g:i A') }}
            @if(request('search') || request('type') || request('department'))
                <br>
                @if(request('search'))
                    Search: "{{ request('search') }}"
                @endif
                @if(request('type'))
                    | Type: {{ ucfirst(request('type')) }}
                @endif
                @if(request('department'))
                    | Department: {{ request('department') }}
                @endif
            @endif
        </div>
    </div>

    <div class="stats">
        <div class="stat-item">
            <div class="stat-number">{{ $stats['total_staff'] }}</div>
            <div class="stat-label">Total Staff</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $stats['military_count'] }}</div>
            <div class="stat-label">Military Personnel</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $stats['civilian_count'] }}</div>
            <div class="stat-label">Civilian Personnel</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $stats['total_departments'] }}</div>
            <div class="stat-label">Departments</div>
        </div>
    </div>

    @if($militaryStaff->count() > 0)
        <div class="section-title">🎖️ MILITARY PERSONNEL ({{ $militaryStaff->count() }})</div>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 3%">No.</th>
                    <th style="width: 10%">Service No.</th>
                    <th style="width: 15%">Name</th>
                    <th style="width: 9%">Contact</th>
                    <th style="width: 9%">Rank</th>
                    <th style="width: 12%">Appointment</th>
                    <th style="width: 4%">Sex</th>
                    <th style="width: 8%">Trade</th>
                    <th style="width: 8%">Arm</th>
                    <th style="width: 8%">Status</th>
                    <th style="width: 14%">Department</th>
                </tr>
            </thead>
            <tbody>
                @foreach($militaryStaff as $index => $member)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $member->service_number }}</strong></td>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->contact ?: '-' }}</td>
                        <td>
                            @if($member->rank)
                                <span class="rank-badge">{{ $member->rank }}</span>
                            @else
                                <span style="color: #999; font-style: italic;">N/A</span>
                            @endif
                        </td>
                        <td>{{ $member->appointment ?: '-' }}</td>
                        <td style="text-align: center;">{{ $member->sex ?: '-' }}</td>
                        <td>{{ $member->trade ?: '-' }}</td>
                        <td>{{ $member->arm_of_service ?: '-' }}</td>
                        <td>
                            @if($member->deployment)
                                <span class="status-badge">{{ $member->deployment }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $member->department ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($civilianStaff->count() > 0)
        @if($militaryStaff->count() > 0)
            <div class="page-break"></div>
        @endif
        
        <div class="section-title">👔 CIVILIAN PERSONNEL ({{ $civilianStaff->count() }})</div>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 3%">No.</th>
                    <th style="width: 12%">Service No.</th>
                    <th style="width: 18%">Name</th>
                    <th style="width: 10%">Contact</th>
                    <th style="width: 12%">Grade</th>
                    <th style="width: 13%">Appointment</th>
                    <th style="width: 10%">Location</th>
                    <th style="width: 10%">Category</th>
                    <th style="width: 12%">Department</th>
                </tr>
            </thead>
            <tbody>
                @foreach($civilianStaff as $index => $member)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $member->service_number }}</strong></td>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->contact ?: '-' }}</td>
                        <td>
                            @if($member->present_grade)
                                <span class="grade-badge">{{ $member->present_grade }}</span>
                            @else
                                <span style="color: #999; font-style: italic;">N/A</span>
                            @endif
                        </td>
                        <td>{{ $member->appointment ?: '-' }}</td>
                        <td>{{ $member->location ?: '-' }}</td>
                        <td>{{ $member->staff_category ?: '-' }}</td>
                        <td>{{ $member->department ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($staff->count() == 0)
        <div class="no-data">
            <p>No staff members found matching the current filters.</p>
        </div>
    @endif

    <div class="footer">
        <p><strong>CONFIDENTIAL DOCUMENT</strong></p>
        <p>Ghana Armed Forces Command & Staff College - Staff Management System</p>
        <p>Total Records: {{ $staff->count() }} (Military: {{ $militaryStaff->count() }}, Civilian: {{ $civilianStaff->count() }}) | Generated: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>