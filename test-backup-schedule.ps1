# Backup Schedule Test - 30 Second Interval
# This script tests the automated backup system

Write-Host "`n╔══════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║    BACKUP SCHEDULE TEST - 30 SECOND AUTOMATION TEST     ║" -ForegroundColor Cyan
Write-Host "╚══════════════════════════════════════════════════════════╝`n" -ForegroundColor Cyan

Write-Host "📋 Test Setup:" -ForegroundColor Yellow
Write-Host "   1. Schedule: Every 30 seconds" -ForegroundColor White
Write-Host "   2. Duration: 2 minutes (120 seconds)" -ForegroundColor White
Write-Host "   3. Expected backups: 4 backups" -ForegroundColor White
Write-Host ""

# Check current backup count
$countCmd = 'echo App\Models\Backup::count();'
$initialCount = php artisan tinker --execute=$countCmd
Write-Host "📦 Current backups in database: $initialCount" -ForegroundColor Green
Write-Host ""

Write-Host "⚙️  Starting test..." -ForegroundColor Yellow
Write-Host ""

# Clean up old test backups (optional)
$cleanup = Read-Host "Do you want to clean up existing backups first? (y/n)"
if ($cleanup -eq 'y') {
    Write-Host "🧹 Cleaning up old backups..." -ForegroundColor Yellow
    php artisan backup:cleanup --force
    Write-Host ""
}

Write-Host "🚀 Starting Laravel scheduler worker in background..." -ForegroundColor Cyan
Write-Host "   The scheduler worker will run tasks as they become due (including every 30 seconds)" -ForegroundColor White
Write-Host ""

# Start the scheduler worker in a background job
$schedulerJob = Start-Job -ScriptBlock {
    Set-Location "c:\Users\Hamza Damra\ITCenter-Ecommerce"
    php artisan schedule:work
}

Start-Sleep -Seconds 2
Write-Host "✅ Scheduler worker started (Job ID: $($schedulerJob.Id))" -ForegroundColor Green
Write-Host "   Status: $($schedulerJob.State)" -ForegroundColor Gray
Write-Host ""

# Wait a moment for scheduler to initialize
Start-Sleep -Seconds 2

Write-Host "🔍 Starting backup monitor for 120 seconds..." -ForegroundColor Cyan
Write-Host "   (Monitoring will track all backups created during this time)" -ForegroundColor White
Write-Host ""

# Run the monitoring command
php artisan backup:monitor-schedule --duration=120

# Stop the scheduler
Write-Host "`n🛑 Stopping scheduler..." -ForegroundColor Yellow
Stop-Job -Id $schedulerJob.Id
Remove-Job -Id $schedulerJob.Id
Write-Host "✅ Scheduler stopped" -ForegroundColor Green

Write-Host "`n📊 Final Verification:" -ForegroundColor Cyan
$finalCountCmd = 'echo App\Models\Backup::count();'
$finalCount = php artisan tinker --execute=$finalCountCmd
Write-Host "   Total backups now: $finalCount" -ForegroundColor White

Write-Host "`n📁 List all backups:" -ForegroundColor Cyan
php artisan backup:list

Write-Host "`n✅ Test Complete!" -ForegroundColor Green
Write-Host ""
Write-Host "💡 To reset schedule to production mode:" -ForegroundColor Yellow
Write-Host "   1. Edit .env and change BACKUP_SCHEDULE=testing to BACKUP_SCHEDULE=daily" -ForegroundColor White
Write-Host "   2. Run: php artisan config:clear" -ForegroundColor White
Write-Host ""
