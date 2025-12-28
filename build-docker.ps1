# PowerShell script to build and push Docker image to Docker Hub
# Usage: .\build-docker.ps1 [version] [push] [username]

param(
    [string]$Version = "latest",
    [switch]$Push = $false,
    [string]$DockerUsername = ""
)

# Docker Hub username (default: abusaker)
if ([string]::IsNullOrWhiteSpace($DockerUsername)) {
    # Try to get from environment variable
    $DockerUsername = $env:DOCKER_USERNAME
    
    # If still empty, use default
    if ([string]::IsNullOrWhiteSpace($DockerUsername)) {
        $DockerUsername = "abusaker"
        Write-Host "`nUsing default Docker Hub username: abusaker" -ForegroundColor Cyan
        Write-Host "To use a different username, run: .\build-docker.ps1 -DockerUsername 'your-username'" -ForegroundColor Gray
    }
}

$DOCKER_USERNAME = $DockerUsername
$IMAGE_NAME = "itcenter-ecommerce"

# Full image name
$FULL_IMAGE_NAME = "$DOCKER_USERNAME/$IMAGE_NAME`:$Version"

Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "Building Docker Image" -ForegroundColor Cyan
Write-Host "Image: $FULL_IMAGE_NAME" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan

# Build the Docker image
Write-Host "`nBuilding Docker image..." -ForegroundColor Yellow
docker build -t $FULL_IMAGE_NAME .

if ($LASTEXITCODE -ne 0) {
    Write-Host "`nBuild failed!" -ForegroundColor Red
    exit 1
}

Write-Host "`nBuild completed successfully!" -ForegroundColor Green

# Also tag as latest if version is not latest
if ($Version -ne "latest") {
    Write-Host "`nTagging as latest..." -ForegroundColor Yellow
    docker tag $FULL_IMAGE_NAME "$DOCKER_USERNAME/$IMAGE_NAME`:latest"
}

# Push to Docker Hub if requested
if ($Push) {
    Write-Host "`n=========================================" -ForegroundColor Cyan
    Write-Host "Pushing to Docker Hub" -ForegroundColor Cyan
    Write-Host "=========================================" -ForegroundColor Cyan
    
    Write-Host "`nPushing image..." -ForegroundColor Yellow
    docker push $FULL_IMAGE_NAME
    
    if ($LASTEXITCODE -ne 0) {
        Write-Host "`nPush failed!" -ForegroundColor Red
        exit 1
    }
    
    if ($Version -ne "latest") {
        Write-Host "`nPushing latest tag..." -ForegroundColor Yellow
        docker push "$DOCKER_USERNAME/$IMAGE_NAME`:latest"
    }
    
    Write-Host "`nPush completed successfully!" -ForegroundColor Green
    Write-Host "`nImage available at: https://hub.docker.com/r/$DOCKER_USERNAME/$IMAGE_NAME" -ForegroundColor Cyan
} else {
    Write-Host "`nTo push to Docker Hub, run:" -ForegroundColor Yellow
    Write-Host "  .\build-docker.ps1 -Version $Version -Push" -ForegroundColor White
}

Write-Host "`nDone!" -ForegroundColor Green

