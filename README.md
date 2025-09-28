# 🌱 IoT Smart Irrigation System

A full-stack IoT solution for automated plant watering with real-time monitoring and control via Laravel dashboard.

![Project Banner](public/welcome.png)

## 🚀 Project Overview

**IoT-Based Smart Irrigation System** that autonomously monitors soil conditions and manages water distribution through an ESP32 microcontroller. Features a real-time Laravel dashboard with user authentication, data visualization, and remote control capabilities.

## 🛠 Tech Stack

### Hardware & IoT
- **Microcontroller**: ESP32-WROOM-32
- **Sensors**: Capacitive Soil Moisture Sensor
- **Actuators**: 5V Relay Module, DC Water Pump
- **Communication**: WiFi 802.11 b/g/n

### Backend
- **Framework**: Laravel 10.x (PHP 8.2)
- **Database**: MySQL 8.0
- **Authentication**: Laravel Breeze
- **API**: RESTful JSON API

### Frontend
- **Styling**: Tailwind CSS 3.x
- **Charts**: Chart.js 4.x
- **JavaScript**: Vanilla ES6+ with Axios

## ✨ Key Features

### IoT Device
- ✅ Real-time soil moisture monitoring (0-100% range)
- ✅ Threshold-based watering automation
- ✅ Manual override controls
- ✅ Offline operation capability

### Web Dashboard
- ✅ Real-time moisture level monitoring
- ✅ Historical data charts and analytics
- ✅ User authentication system
- ✅ Mobile-responsive interface
- ✅ Water usage statistics

## 📋 Installation & Setup

### Hardware Requirements
- ESP32 Development Board
- Capacitive Soil Moisture Sensor
- 5V Relay Module
- DC Water Pump
- Jumper Wires & Breadboard

### Software Setup

1. **Clone the repository**
```bash
git clone https://github.com/your-username/iot-smart-irrigation-system.git
cd iot-smart-irrigation-system
