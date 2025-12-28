#!/bin/bash
# Bash script to build and push Docker image to Docker Hub
# Usage: ./build-docker.sh [version] [--push]

# Docker Hub username (change this to your Docker Hub username)
DOCKER_USERNAME="your-dockerhub-username"
IMAGE_NAME="itcenter-ecommerce"
VERSION="${1:-latest}"
PUSH="${2:-false}"

# Full image name
FULL_IMAGE_NAME="$DOCKER_USERNAME/$IMAGE_NAME:$VERSION"

echo "========================================="
echo "Building Docker Image"
echo "Image: $FULL_IMAGE_NAME"
echo "========================================="

# Build the Docker image
echo ""
echo "Building Docker image..."
docker build -t "$FULL_IMAGE_NAME" .

if [ $? -ne 0 ]; then
    echo ""
    echo "Build failed!"
    exit 1
fi

echo ""
echo "Build completed successfully!"

# Also tag as latest if version is not latest
if [ "$VERSION" != "latest" ]; then
    echo ""
    echo "Tagging as latest..."
    docker tag "$FULL_IMAGE_NAME" "$DOCKER_USERNAME/$IMAGE_NAME:latest"
fi

# Push to Docker Hub if requested
if [ "$PUSH" == "--push" ]; then
    echo ""
    echo "========================================="
    echo "Pushing to Docker Hub"
    echo "========================================="
    
    echo ""
    echo "Pushing image..."
    docker push "$FULL_IMAGE_NAME"
    
    if [ $? -ne 0 ]; then
        echo ""
        echo "Push failed!"
        exit 1
    fi
    
    if [ "$VERSION" != "latest" ]; then
        echo ""
        echo "Pushing latest tag..."
        docker push "$DOCKER_USERNAME/$IMAGE_NAME:latest"
    fi
    
    echo ""
    echo "Push completed successfully!"
    echo ""
    echo "Image available at: https://hub.docker.com/r/$DOCKER_USERNAME/$IMAGE_NAME"
else
    echo ""
    echo "To push to Docker Hub, run:"
    echo "  ./build-docker.sh $VERSION --push"
fi

echo ""
echo "Done!"

