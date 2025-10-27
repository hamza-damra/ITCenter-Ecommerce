Write-Host "╔══════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║  AUTOMATED BACKUP SCHEDULER TEST - 30 SECOND INTERVALS  ║" -ForegroundColor Cyan
Write-Host "╚══════════════════════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""

# Get initial count
Write-Host "📦 Checking current backups..." -ForegroundColor Cyan
$countCmd = 'App\Models\Backup::count()'
$initialCount = php artisan tinker --execute="echo $countCmd;"
$initialCount = $initialCount.Trim() -replace '\D',''
Write-Host "   Initial backup count: $initialCount" -ForegroundColor White
Write-Host ""

# Start scheduler:work in a new window
Write-Host "🚀 Starting scheduler:work in new window..." -ForegroundColor Yellow
$schedulerProcess = Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd 'c:\Users\Hamza Damra\ITCenter-Ecommerce'; php artisan schedule:work" -PassThru -WindowStyle Normal
Write-Host "   Process ID: $($schedulerProcess.Id)" -ForegroundColor Gray
Write-Host "   Window opened - scheduler running" -ForegroundColor Green
Write-Host ""

Start-Sleep -Seconds 5

Write-Host "⏱️  Monitoring for 120 seconds (expecting 4 backups)..." -ForegroundColor Cyan
Write-Host "   Checking every 15 seconds" -ForegroundColor Gray
Write-Host ""

$startTime = Get-Date
$endTime = $startTime.AddSeconds(120)
$checkInterval = 15
$measurements = @()

$iteration = 0
while ((Get-Date) -lt $endTime) {
    $iteration++
    $elapsed = ((Get-Date) - $startTime).TotalSeconds
    $remaining = ($endTime - (Get-Date)).TotalSeconds
    
    # Get current count
    $currentCount = php artisan tinker --execute="echo $countCmd;"
    $currentCount = $currentCount.Trim() -replace '\D',''
    $created = [int]$currentCount - [int]$initialCount
    
    $measurements += [PSCustomObject]@{
        Check = $iteration
        Time = Get-Date -Format "HH:mm:ss"
        Elapsed = "{0:N0}s" -f $elapsed
        Total = $currentCount
        NewBackups = $created
    }
    
    $color = if ($created -gt 0) { "Green" } else { "Yellow" }
    Write-Host ("   [{0}] {1:N0}s elapsed - Backups: {2} (+{3} new)" -f $iteration, $elapsed, $currentCount, $created) -ForegroundColor $color
    
    if ($remaining -gt $checkInterval) {
        Start-Sleep -Seconds $checkInterval
    } else {
        if ($remaining -gt 0) {
            Start-Sleep -Seconds $remaining
        }
        break
    }
}

Write-Host ""
Write-Host "🛑 Stopping scheduler..." -ForegroundColor Yellow
Stop-Process -Id $schedulerProcess.Id -Force -ErrorAction SilentlyContinue
Write-Host "   Scheduler stopped" -ForegroundColor Green
Write-Host ""

# Final verification
Write-Host "📊 Final verification..." -ForegroundColor Cyan
Start-Sleep -Seconds 2
$finalCount = php artisan tinker --execute="echo $countCmd;"
$finalCount = $finalCount.Trim() -replace '\D',''
$totalCreated = [int]$finalCount - [int]$initialCount

Write-Host ""
Write-Host "╔══════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║                      RESULTS                             ║" -ForegroundColor Cyan
Write-Host "╚══════════════════════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""

Write-Host "📈 Summary:" -ForegroundColor Yellow
Write-Host "   Duration: 120 seconds (2 minutes)" -ForegroundColor White
Write-Host "   Schedule: Every 30 seconds" -ForegroundColor White
Write-Host "   Expected: 4 backups" -ForegroundColor White
Write-Host "   Actual: $totalCreated backups" -ForegroundColor $(if ($totalCreated -ge 3) { "Green" } elseif ($totalCreated -gt 0) { "Yellow" } else { "Red" })
Write-Host ""

Write-Host "📊 Timeline:" -ForegroundColor Yellow
$measurements | Format-Table -AutoSize

Write-Host ""
if ($totalCreated -ge 4) {
    Write-Host "✅ SUCCESS! Scheduler created $totalCreated backups in 2 minutes" -ForegroundColor Green
    Write-Host "   The 30-second schedule is working perfectly!" -ForegroundColor Green
} elseif ($totalCreated -ge 2) {
    Write-Host "⚠️  PARTIAL SUCCESS: Created $totalCreated backups (expected 4)" -ForegroundColor Yellow
    Write-Host "   Scheduler is working but may need timing adjustment" -ForegroundColor Yellow
} elseif ($totalCreated -gt 0) {
    Write-Host "⚠️  LIMITED SUCCESS: Created only $totalCreated backup(s)" -ForegroundColor Yellow
} else {
    Write-Host "❌ FAILED: No backups were created automatically" -ForegroundColor Red
    Write-Host "   Check logs: storage/logs/laravel.log" -ForegroundColor Red
}

Write-Host ""
Write-Host "📋 All backups:" -ForegroundColor Cyan
php artisan backup:list

Write-Host ""
Write-Host "💡 Next steps:" -ForegroundColor Yellow
Write-Host "   1. Change .env: BACKUP_SCHEDULE=daily" -ForegroundColor White
Write-Host "   2. Run: php artisan config:clear" -ForegroundColor White
Write-Host "   3. Set up cron: * * * * * cd /path && php artisan schedule:run" -ForegroundColor White
