# 🎓 Maharati Platform

**AI-Powered Life Skills Platform for Egyptian Youth**

---

## 📸 Preview

<!-- Add screenshots or GIF here -->

<!-- ![App Screenshot](your_image_link_here) -->

---

## 📑 Table of Contents

* [Features](#-features)
* [Available Skills](#-available-skills)
* [Technologies Used](#-technologies-used)
* [Requirements](#-requirements)
* [Installation & Setup](#-installation--setup)
* [Usage](#-using-the-application)
* [Advanced Settings](#-advanced-settings)
* [Security](#-security)
* [Contribution](#-contribution)
* [License](#-license)

---

## 🌟 Features

* 🇪🇬 Content tailored to Egyptian culture
* 🤖 AI Assistant powered by Google Gemini
* 🎯 Interactive daily challenges
* 📊 Progress tracking system (points & levels)
* 📱 Progressive Web App (PWA) with offline support
* 🌍 Fully responsive Arabic interface

---

## 🎯 Available Skills

### 💰 Financial Management

* Personal budgeting basics
* Smart saving strategies
* Simple investing for beginners

### 💼 Career Skills

* Professional CV writing
* Mastering job interviews
* Salary negotiation

### 🗣️ Communication & Relationships

* Effective communication
* Building professional networks
* Conflict management

### 🧠 Mental Health

* Stress management
* Building self-confidence
* Goal setting

### 📚 Personal Development

* Time management
* Building positive habits
* Achieving goals

### 💻 Technology

* Computer basics
* Internet essentials
* Digital security

---

## 🛠️ Technologies Used

* **Backend:** PHP 8.0+
* **Database:** MySQL 8.0
* **Frontend:** Bootstrap 5.3, HTML5, CSS3, JavaScript (ES6)
* **AI Integration:** Google Gemini API
* **PWA:** Service Worker + Web App Manifest
* **Icons:** FontAwesome
* **Font:** Cairo

---

## 📋 Requirements

* PHP 8.0 or higher
* MySQL 8.0 or higher
* Apache or Nginx
* Google Gemini API Key (optional)

---

## 🚀 Installation & Setup

### 1) Clone the repository

```bash
git clone https://github.com/Abdullah-Ahmad-Abdulhamid/Digitopia.git
cd maharati_platform
```

---

### 2) Database Setup

* Start MySQL server
* Open in browser:

```
http://localhost/setup.php
```

* Follow setup instructions

---

### 3) Configure AI (Optional)

* Get API key from Google AI Studio
* Open:

```
config/config.php
```

* Replace:

```
your_gemini_api_key_here
```

---

### 4) Server Configuration

* Enable Apache mod_rewrite
* Set permissions:

```bash
chmod 755 uploads/
chmod 755 logs/
```

---

## 👤 Demo Account

* **Email:** [demo@maharati.com](mailto:demo@maharati.com)
* **Password:** 123456

---

## 📱 Using the Application

### New Users

* Create an account
* Explore skills
* Use AI assistant

### Existing Users

* Login
* Track progress
* Complete daily challenges

---

## 🔧 Advanced Settings

### Customize UI

Edit:

```
css/style.css
```

### Add Skills

* Insert into `skill_categories`
* Insert into `skills`

---

## 🔒 Security

* ❗ Delete `setup.php` after installation
* 🔐 Use HTTPS in production
* 🔑 Change default credentials

---

## 🤝 Contribution

1. Fork the project
2. Create a branch

```bash
git checkout -b feature/new-feature
```

3. Commit changes

```bash
git commit -m "Add new feature"
```

4. Push

```bash
git push origin feature/new-feature
```

5. Open Pull Request

---

## 📝 License

This project is licensed under the MIT License.

---

## 🙏 Acknowledgments

* Google Gemini AI
* Bootstrap
* FontAwesome
* Cairo Font

---

## 🔄 Version

**v1.0.0**

* Initial release
* AI integration
* PWA support
* Responsive UI
