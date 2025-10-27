Write-Host "╔══════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║   SIMPLE BACKUP SCHEDULER TEST - 30 SECOND INTERVALS   ║" -ForegroundColor Cyan
Write-Host "╚══════════════════════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""

Write-Host "📋 Test Plan:" -ForegroundColor Yellow
Write-Host "   1. Start scheduler:work in a new window"
Write-Host "   2. Monitor backup creation for 2 minutes"
Write-Host "   3. Expect 4 backups to be created (every 30 seconds)"
Write-Host ""

# Get initial count
Write-Host "📦 Checking current backups..." -ForegroundColor Cyan
$countCmd = 'echo App\Models\Backup::count();'
$initialCount = (php artisan tinker --execute=$countCmd).Trim()
Write-Host "   Initial backup count: $initialCount" -ForegroundColor White
Write-Host ""

# Instructions for manual start
Write-Host "▶️  STEP 1: Start the scheduler worker" -ForegroundColor Green
Write-Host "   Run this command in a NEW PowerShell window:" -ForegroundColor Yellow
Write-Host "   php artisan schedule:work" -ForegroundColor White
Write-Host ""
Write-Host "   (Leave that window open and return here)" -ForegroundColor Gray
Write-Host ""

$continue = Read-Host "Press ENTER when scheduler:work is running in another window"

Write-Host ""
Write-Host "⏱️  Starting 2-minute monitoring..." -ForegroundColor Cyan
Write-Host "   Checking every 10 seconds for new backups" -ForegroundColor Gray
Write-Host ""

$startTime = Get-Date
$endTime = $startTime.AddSeconds(120)
$checkInterval = 10

$measurements = @()

while ((Get-Date) -lt $endTime) {
    $elapsed = ((Get-Date) - $startTime).TotalSeconds
    $remaining = ($endTime - (Get-Date)).TotalSeconds
    
    # Get current count
    $currentCount = (php artisan tinker --execute=$countCmd).Trim()
    $created = [int]$currentCount - [int]$initialCount
    
    $measurements += [PSCustomObject]@{
        Time = Get-Date -Format "HH:mm:ss"
        Elapsed = "{0:N1}s" -f $elapsed
        Count = $currentCount
        Created = $created
    }
    
    Write-Host ("🔄 [{0:N1}s] Backups: {1} (Created: {2})" -f $elapsed, $currentCount, $created) -ForegroundColor $(if ($created -gt 0) { "Green" } else { "Yellow" })
    
    if ($remaining -gt $checkInterval) {
        Start-Sleep -Seconds $checkInterval
    } else {
        Start-Sleep -Seconds $remaining
        break
    }
}

Write-Host ""
Write-Host "╔══════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║                   TEST COMPLETE                          ║" -ForegroundColor Cyan
Write-Host "╚══════════════════════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""

# Final count
$finalCount = (php artisan tinker --execute=$countCmd).Trim()
$totalCreated = [int]$finalCount - [int]$initialCount

Write-Host "📊 Results Summary:" -ForegroundColor Yellow
Write-Host "   Initial Backups: $initialCount" -ForegroundColor White
Write-Host "   Final Backups: $finalCount" -ForegroundColor White
Write-Host "   Created: $totalCreated" -ForegroundColor $(if ($totalCreated -ge 3) { "Green" } else { "Red" })
Write-Host "   Expected: 4" -ForegroundColor White
Write-Host ""

Write-Host "📈 Measurement Timeline:" -ForegroundColor Yellow
$measurements | Format-Table -AutoSize

Write-Host ""
if ($totalCreated -ge 3) {
    Write-Host "✅ SUCCESS: Scheduler is working! Created $totalCreated backups" -ForegroundColor Green
} elseif ($totalCreated -gt 0) {
    Write-Host "⚠️  PARTIAL: Created $totalCreated backups (expected 4)" -ForegroundColor Yellow
} else {
    Write-Host "❌ FAILED: No backups created" -ForegroundColor Red
}

Write-Host ""
Write-Host "📋 Current backups:" -ForegroundColor Cyan
php artisan backup:list

Write-Host ""
Write-Host "💡 Don't forget to:" -ForegroundColor Yellow
Write-Host "   1. Stop the scheduler:work in the other window (Ctrl+C)" -ForegroundColor White
Write-Host "   2. Reset to production: Change BACKUP_SCHEDULE=daily in .env" -ForegroundColor White
Write-Host "   3. Run: php artisan config:clear" -ForegroundColor White
