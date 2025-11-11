# TestSprite Helper Script for Windows PowerShell

# Set the API key from .testsprite.json
$config = Get-Content ".testsprite.json" | ConvertFrom-Json
$env:TESTSPRITE_API_KEY = $config.testsprite.apiKey

Write-Host "TestSprite Configuration Loaded" -ForegroundColor Green
Write-Host "Project Type: $($config.testsprite.projectType)" -ForegroundColor Cyan
Write-Host "Test Framework: $($config.testsprite.testFramework)" -ForegroundColor Cyan
Write-Host ""

# Run TestSprite MCP
Write-Host "Starting TestSprite MCP Server..." -ForegroundColor Yellow
npx @testsprite/testsprite-mcp@latest
