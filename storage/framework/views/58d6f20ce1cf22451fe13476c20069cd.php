<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>GAFCSC Staff Directory Export</title>
    <style>
        body {
            font-family: serif;
            font-size: 10pt;
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
        
        .rank-badge {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 500;
        }
        
        .grade-badge {
            background-color: #ddd6fe;
            color: #5b21b6;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
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
            Generated on <?php echo e(now()->format('F j, Y \a\t g:i A')); ?>

            <?php if(request('search') || request('type') || request('department')): ?>
                <br>
                <?php if(request('search')): ?>
                    Search: "<?php echo e(request('search')); ?>"
                <?php endif; ?>
                <?php if(request('type')): ?>
                    | Type: <?php echo e(ucfirst(request('type'))); ?>

                <?php endif; ?>
                <?php if(request('department')): ?>
                    | Department: <?php echo e(request('department')); ?>

                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="stats">
        <div class="stat-item">
            <div class="stat-number"><?php echo e($stats['total_staff']); ?></div>
            <div class="stat-label">Total Staff</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?php echo e($stats['military_count']); ?></div>
            <div class="stat-label">Military Personnel</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?php echo e($stats['civilian_count']); ?></div>
            <div class="stat-label">Civilian Personnel</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?php echo e($stats['total_departments']); ?></div>
            <div class="stat-label">Departments</div>
        </div>
    </div>

    <?php if($militaryStaff->count() > 0): ?>
        <div class="section-title">🎖️ MILITARY PERSONNEL (<?php echo e($militaryStaff->count()); ?>)</div>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 5%">No.</th>
                    <th style="width: 12%">Service No.</th>
                    <th style="width: 12%">Rank</th>
                    <th style="width: 20%">Name</th>
                    <th style="width: 6%">Sex</th>
                    <th style="width: 10%">Trade</th>
                    <th style="width: 10%">Arm of Svc</th>
                    <th style="width: 10%">Deployment</th>
                    <th style="width: 15%">Department</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $militaryStaff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td><strong><?php echo e($member->service_number); ?></strong></td>
                        <td>
                            <?php if($member->rank): ?>
                                <span class="rank-badge"><?php echo e($member->rank); ?></span>
                            <?php else: ?>
                                <span style="color: #999; font-style: italic;">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($member->name); ?></td>
                        <td style="text-align: center;"><?php echo e($member->sex ?: '-'); ?></td>
                        <td><?php echo e($member->trade ?: '-'); ?></td>
                        <td><?php echo e($member->arm_of_service ?: '-'); ?></td>
                        <td><?php echo e($member->deployment ?: '-'); ?></td>
                        <td><?php echo e($member->department ?: '-'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if($civilianStaff->count() > 0): ?>
        <?php if($militaryStaff->count() > 0): ?>
            <div class="page-break"></div>
        <?php endif; ?>
        
        <div class="section-title">👔 CIVILIAN PERSONNEL (<?php echo e($civilianStaff->count()); ?>)</div>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 5%">No.</th>
                    <th style="width: 15%">Service No.</th>
                    <th style="width: 25%">Name</th>
                    <th style="width: 15%">Present Grade</th>
                    <th style="width: 15%">Job Description</th>
                    <th style="width: 12%">Location</th>
                    <th style="width: 13%">Department</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $civilianStaff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td><strong><?php echo e($member->service_number); ?></strong></td>
                        <td><?php echo e($member->name); ?></td>
                        <td>
                            <?php if($member->present_grade): ?>
                                <span class="grade-badge"><?php echo e($member->present_grade); ?></span>
                            <?php else: ?>
                                <span style="color: #999; font-style: italic;">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($member->job_description ?: '-'); ?></td>
                        <td><?php echo e($member->location ?: '-'); ?></td>
                        <td><?php echo e($member->department ?: '-'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if($staff->count() == 0): ?>
        <div class="no-data">
            <p>No staff members found matching the current filters.</p>
        </div>
    <?php endif; ?>

    <div class="footer">
        <p><strong>CONFIDENTIAL DOCUMENT</strong></p>
        <p>Ghana Armed Forces Command & Staff College - Staff Management System</p>
        <p>Total Records: <?php echo e($staff->count()); ?> (Military: <?php echo e($militaryStaff->count()); ?>, Civilian: <?php echo e($civilianStaff->count()); ?>) | Generated: <?php echo e(now()->format('Y-m-d H:i:s')); ?></p>
    </div>
</body>
</html><?php /**PATH /var/www/gafcsc-records/resources/views/staff/export-pdf.blade.php ENDPATH**/ ?>