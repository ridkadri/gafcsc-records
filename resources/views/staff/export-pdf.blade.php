<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Staff Directory Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
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
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th {
            background-color: #374151;
            color: white;
            font-weight: bold;
            padding: 12px 8px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        tr:hover {
            background-color: #f3f4f6;
        }
        
        .rank-badge {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 500;
        }
        
        .office-tag {
            background-color: #f3f4f6;
            color: #374151;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Staff Directory</h1>
        <div class="meta">
            Generated on {{ now()->format('F j, Y \a\t g:i A') }}
            @if(request('search') || request('office'))
                <br>
                @if(request('search'))
                    Search: "{{ request('search') }}"
                @endif
                @if(request('office'))
                    | Office: {{ request('office') }}
                @endif
            @endif
        </div>
    </div>

    <div class="stats">
        <div class="stat-item">
            <div class="stat-number">{{ $staff->count() }}</div>
            <div class="stat-label">Total Staff</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $staff->pluck('office')->unique()->count() }}</div>
            <div class="stat-label">Office Locations</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $staff->whereNotNull('rank')->where('rank', '!=', '')->count() }}</div>
            <div class="stat-label">With Ranks</div>
        </div>
    </div>

    @if($staff->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 15%">Staff ID</th>
                    <th style="width: 30%">Full Name</th>
                    <th style="width: 20%">Rank</th>
                    <th style="width: 25%">Office</th>
                    <th style="width: 10%">Date Added</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staff as $member)
                    <tr>
                        <td><strong>{{ $member->staff_id }}</strong></td>
                        <td>{{ $member->name }}</td>
                        <td>
                            @if($member->rank)
                                <span class="rank-badge">{{ $member->rank }}</span>
                            @else
                                <span style="color: #9ca3af; font-style: italic;">Not Specified</span>
                            @endif
                        </td>
                        <td>
                            <span class="office-tag">{{ $member->office }}</span>
                        </td>
                        <td>{{ $member->created_at->format('M j, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>No staff members found matching the current filters.</p>
        </div>
    @endif

    <div class="footer">
        <p>Staff Management System - Confidential Document</p>
        <p>Page generated automatically from database records</p>
    </div>
</body>
</html>