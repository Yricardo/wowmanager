# Implementation in progress
# 🏰 WoW Manager - Guild Management Platform

[![Symfony](https://img.shields.io/badge/Symfony-6.4-blue.svg)](https://symfony.com/)
[![PHP](https://img.shields.io/badge/PHP-8.2+-purple.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Build Status](https://img.shields.io/badge/Build-Passing-brightgreen.svg)](https://github.com/your-repo/wowmanager)

> **A comprehensive World of Warcraft guild management platform built with Symfony**

Transform your guild administration with an intuitive, feature-rich platform designed for WoW communities. From recruitment to auction management, WoW Manager handles it all !
---

## 🎮 **Features Overview**

### 🛡️ **Core Systems**
- **Multi-Role User Management** - Super Admin, Admin, Member hierarchy
- **Basic security to be improved** - Protected super admin creation, CSRF protection (messages encryption incoming)
- **Real-time Messaging** - Live chat system with friend networks
- **Smart Settings** - Auto-populated user preferences with validation
- **Invitation System** - Secure guild recruitment with email invitations

### ⚔️ **Guild Features**
- **📜 Invitation Management** - Create, track, and manage member invitations
- **👥 Friend Networks** - Connect guild members with friendship systems 
- **💬 Messaging System** - Real-time conversations with polling updates (to be replaced by mercure)
- **⚖️ Auction House** - Guild auction management (coming soon)
- **📊 Activity Feed** - Track guild events and member activities (coming soon)

### 🏰 **Technical approach**
- **Auto-Settings Generation** - Event listeners populate user settings automatically
- **Doctrine Fixtures** - Development data generation for testing
- **Command-Line Tools**
- **Stimulus Controllers** - Enhanced frontend interactions
- **Event driven programmation** -
- **Clean controllers policy**
- **CI/CD incoming**
- **Critical path testing incoming**
---

## 🚀 **Quick Start**

### Prerequisites
- PHP 8.2+
- Composer
- MySQL 8.0+
- Redis (coming soon)

### Installation

```bash
# Clone the repository
git clone https://github.com/your-username/wowmanager.git
cd wowmanager

# Install dependencies
composer install

# Configure environment
cp .env .env.local
# Edit .env.local with your database credentials

# Setup database
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
# Initialize super admin
php bin/console powtatow:base:launch superadmin your_password
#compile assets
sudo -u www-data php bin/console asset-map:compile

# Start development server
symfony server:start
```

### 🎯 **First Login**
1. Navigate to `http://localhost:8000`
2. Login with your super admin credentials
3. Start inviting guild members!

---

## 🛠️ **Architecture & Design**

### 🏗️ **Core Components**

#### **User Management System**
```php
// Hierarchical role system
ROLE_USER          // Base user access
ROLE_MEMBER        // Guild member privileges  
ROLE_ADMIN         // Administrative access
ROLE_SUPER_ADMIN   // Full system control (only one allowed)
```

#### **Event-Driven Settings**
- **Automatic Population** - New users get default settings via Doctrine listeners
- **Role-Based Access** - Settings visibility based on user permissions
- **Type Safety** - Strongly typed setting values with validation

#### **Invitation Workflow**
```
Create Invitation → Email Link → Registration → Auto-Friend → Guild Member
```

### 🎨 **Frontend Architecture**
- **Stimulus Controllers** - Modern JavaScript interactions
- **Functionnal design** - Improvements are still needed 
- **Responsive Grid** - Adaptive layouts for all screen sizes (still is to be perfected)
- **Real-time Updates** - Polling-based live messaging (will be replaced by pub/sub)

---

## 📚 **Key Features Deep Dive**

### 🔐 **Security & Protection**

#### **Super Admin Protection**
```php
// Only one super admin allowed - enforced at entity level
#[AsEntityListener(event: Events::prePersist, entity: User::class)]
class SuperAdminProtectionListener
```

#### **Setting Duplication Prevention**
- Automatic validation prevents duplicate user settings
- Role-based setting access control
- Type-safe value handling

### 📨 **Invitation System**

#### **Create Invitations**
- Email validation and duplicate prevention
- Personal message support
- Automatic expiration handling
- Secure token generation

#### **Registration Flow**
```php
// Secure invitation-based registration
/register/member/{invitationCode}  // For guild members
/register/admin/{invitationCode}   // For administrators
```

### 💬 **Messaging System**

#### **Real-time Features**
- Friend-based messaging restrictions
- Live message polling (3-second intervals)
- Message read status tracking
- Conversation history persistence

#### **Technical Implementation**
```javascript
// Stimulus controller with polling
pollForNewMessages() {
    // Fetch new messages since last timestamp
    // Update DOM with new content
    // Maintain scroll position
}
```

---

## 🔧 **Development Commands**

### **Essential Commands**
```bash
# Initialize system with super admin
php bin/console powtatow:base:launch <username> <password>

# Update user settings for all users (deployment)
php bin/console powtatow:update-settings-for-users

# Test security protections
php bin/console powtatow:test:setting-protection
php bin/console powtatow:test:super-admin-protection

# Load development data
php bin/console doctrine:fixtures:load
```

### **Database Operations**
```bash
# Reset database with fresh fixtures
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create  
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

---

## 📁 **Project Structure**

```
src/
├── Command/              # CLI commands for administration
├── Controller/           # HTTP request handlers
│   ├── InvitationController.php
│   ├── MessageController.php
│   └── RegistrationController.php
├── Entity/               # Doctrine entities
│   ├── User.php
│   ├── Invitation.php
│   ├── Message.php
│   └── Setting.php
├── EventListener/        # Doctrine event listeners
│   ├── UserSettingsPopulatorListener.php
│   └── SuperAdminProtectionListener.php
├── Form/                 # Symfony forms
├── Managers/             # Business logic services
│   ├── UserManager.php
│   ├── InvitationManager.php
│   ├── MessageManager.php
│   └── SettingManager.php
├── Repository/           # Database queries
└── Helper/               # Utility classes
    └── SettingHelper.php

templates/
├── member/               # Member dashboard views
│   ├── views/
│   │   ├── invitation_create.html.twig
│   │   ├── invitation_list.html.twig
│   │   ├── message.html.twig
│   │   └── settings.html.twig
│   └── member_base.html.twig
└── security/
    └── login.html.twig

assets/
├── controllers/          # Stimulus controllers
│   └── conversation_controller.js
└── styles/
    └── memberdashboard.css
```

---

## 🎨 **UI/UX Features**

### **WoW-Themed Design**
- **Fantasy Color Palette** - Gold borders, dark backgrounds
- **Immersive Icons** - Castle, scroll, sword emojis
- **Glowing Effects** - CSS animations and gradients
- **Responsive Layouts** - Mobile-friendly grid systems

### **Interactive Elements**
- **Real-time Message Updates** - Live conversation polling
- **Dynamic Form Validation** - Instant email verification
- **Hover Animations** - Smooth UI transitions
- **Loading States** - User feedback during operations

---

## 🧪 **Testing & Quality**

### **Built-in Tests**
```bash
# Test super admin protection
php bin/console powtatow:test:super-admin-protection

# Test setting duplication prevention  
php bin/console powtatow:test:setting-protection
```

### **Data Fixtures**
- **20 Test Users** - Complete with friendships and messages
- **Guild Structure** - Characters, guilds, and relationships
- **Invitation Samples** - Pre-loaded invitation examples
- **Settings Population** - Automatic user preference generation

---

## 🔄 **Deployment & Updates**

### **Initial Deployment**
1. Run database migrations
2. Execute `powtatow:base:launch` command
3. Configure web server
4. Set up environment variables

### **Updates & Maintenance**
```bash
# After adding new settings, update all users
php bin/console powtatow:update-settings-for-users

# Clear caches
php bin/console cache:clear --env=prod
```

---

## 🤝 **Contributing**

We welcome contributions! Here's how to get started:

1. **Fork** the repository
2. **Create** a feature branch (`git checkout -b feature/amazing-feature`)
3. **Commit** your changes (`git commit -m 'Add amazing feature'`)
4. **Push** to the branch (`git push origin feature/amazing-feature`)
5. **Open** a Pull Request

### **Development Guidelines**
- Follow PSR-12 coding standards
- Write meaningful commit messages
- Add tests for new features
- Update documentation as needed

---

## 📄 **License**

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🙏 **Acknowledgments**

- **Symfony Community** - For the amazing framework
- **Doctrine Team** - For the robust ORM
- **WoW Community** - For inspiration and feedback
- **Contributors** - For making this project better

---

## 📞 **Support & Contact**

- **Documentation**: [Wiki](https://github.com/your-username/wowmanager/wiki)
- **Issues**: [GitHub Issues](https://github.com/your-username/wowmanager/issues)
- **Discussions**: [GitHub Discussions](https://github.com/your-username/wowmanager/discussions)

---

<div align="center">

**Built with ❤️ for the WoW community**

*May your code be bug-free and your raids successful!* ⚔️

[![Made with Symfony](https://img.shields.io/badge/Made%20with-Symfony-brightgreen.svg)](https://symfony.com/)
[![Powered by PHP](https://img.shields.io/badge/Powered%20by-PHP-blue.svg)](https://php.net/)

</div>