#!/bin/bash

# Symfony CLI Installation Script for Bash
# Supports Linux and macOS

echo "Checking for Symfony CLI..."

# Check if symfony command exists
if command -v symfony &> /dev/null; then
    echo "Symfony CLI is already installed."
    symfony version
    exit 0
fi

echo "Symfony CLI not found. Installing..."

# Detect OS
OS="$(uname -s)"
case "${OS}" in
    Linux*)
        echo "Detected Linux system"
        
        # Check if curl is available
        if ! command -v curl &> /dev/null; then
            echo "Error: curl is required but not installed."
            echo "Please install curl first: sudo apt-get install curl (Ubuntu/Debian) or sudo yum install curl (RHEL/CentOS)"
            exit 1
        fi
        
        # Download and install Symfony CLI
        echo "Downloading Symfony CLI..."
        curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | sudo -E bash
        sudo apt install symfony-cli
        ;;
        
    Darwin*)
        echo "Detected macOS system"
        
        # Check if Homebrew is installed
        if command -v brew &> /dev/null; then
            echo "Installing via Homebrew..."
            brew install symfony-cli/tap/symfony-cli
        else
            echo "Homebrew not found. Installing via curl..."
            if ! command -v curl &> /dev/null; then
                echo "Error: curl is required but not installed."
                exit 1
            fi
            curl -sS https://get.symfony.com/cli/installer | bash
            # Add to PATH
            export PATH="$HOME/.symfony5/bin:$PATH"
            echo 'export PATH="$HOME/.symfony5/bin:$PATH"' >> ~/.bashrc
            echo 'export PATH="$HOME/.symfony5/bin:$PATH"' >> ~/.zshrc
        fi
        ;;
        
    *)
        echo "Unsupported operating system: ${OS}"
        echo "Please visit https://symfony.com/download for manual installation instructions."
        exit 1
        ;;
esac

# Verify installation
echo "Verifying installation..."
if command -v symfony &> /dev/null; then
    echo "✅ Symfony CLI installed successfully!"
    symfony version
else
    echo "❌ Installation failed. Please check the output above for errors."
    exit 1
fi