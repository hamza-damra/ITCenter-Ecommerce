# Script to fix Docker image tags with correct username
# Usage: .\fix-docker-tags.ps1 -DockerUsername "your-username"

param(
    [Parameter(Mandatory=$true)]
    [string]$DockerUsername
)

$IMAGE_NAME = "itcenter-ecommerce"
$OLD_TAG = "your-dockerhub-username/$IMAGE_NAME"
$NEW_TAG = "$DockerUsername/$IMAGE_NAME"

Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "Fixing Docker Image Tags" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan

# Get all images with old tag
$images = docker images "$OLD_TAG*" --format "{{.Repository}}:{{.Tag}}"

if ($images) {
    Write-Host "`nFound images with old tag:" -ForegroundColor Yellow
    $images | ForEach-Object { Write-Host "  $_" -ForegroundColor White }
    
    Write-Host "`nRetagging images..." -ForegroundColor Yellow
    
    # Retag latest
    $latestImage = docker images "$OLD_TAG:latest" --format "{{.ID}}"
    if ($latestImage) {
        Write-Host "  Retagging latest..." -ForegroundColor Gray
        docker tag $latestImage "$NEW_TAG`:latest"
    }
    
    # Retag v1.0.0
    $v1Image = docker images "$OLD_TAG:v1.0.0" --format "{{.ID}}"
    if ($v1Image) {
        Write-Host "  Retagging v1.0.0..." -ForegroundColor Gray
        docker tag $v1Image "$NEW_TAG`:v1.0.0"
    }
    
    Write-Host "`nRetagging completed!" -ForegroundColor Green
    Write-Host "`nNew tags:" -ForegroundColor Cyan
    docker images "$NEW_TAG*" --format "  {{.Repository}}:{{.Tag}}"
    
    Write-Host "`nYou can now push with:" -ForegroundColor Yellow
    Write-Host "  docker push $NEW_TAG`:latest" -ForegroundColor White
    Write-Host "  docker push $NEW_TAG`:v1.0.0" -ForegroundColor White
} else {
    Write-Host "`nNo images found with old tag." -ForegroundColor Yellow
}

Write-Host "`nDone!" -ForegroundColor Green

