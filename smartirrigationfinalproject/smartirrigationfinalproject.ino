#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

const char* ssid = "HUAWEI-B311-9FA3";
const char* password = "sammysayah";

const char* serverUrl = "http://192.168.9.107:8000/api/irrigation/sensor-data";
const char* statusUrl = "http://192.168.9.107:8000/api/irrigation/system-status";
const char* manualUrl = "http://192.168.9.107:8000/api/irrigation/manual-water";

const int moistureSensorPin = 34;
const int relayPin = 4;

float moistureLevel = 0;
bool pumpStatus = false;
unsigned long lastWatering = 0;
int pumpDuration = 10000;
int moistureThreshold = 30;
bool autoMode = true;
bool systemEnabled = true; // This should be controlled by server settings

unsigned long lastWiFiCheck = 0;
const unsigned long wifiCheckInterval = 5000;
unsigned long lastManualCheck = 0;
const unsigned long manualCheckInterval = 3000;
unsigned long lastMoistureCheck = 0;
const unsigned long moistureCheckInterval = 2000;

void setup() {
  Serial.begin(115200);
  pinMode(relayPin, OUTPUT);
  digitalWrite(relayPin, LOW);

  connectToWiFi();
  getSystemSettings();
  
  Serial.println("System initialized. Checking system status...");
}

void loop() {
  // Check WiFi connection
  if (millis() - lastWiFiCheck > wifiCheckInterval) {
    lastWiFiCheck = millis();
    if (WiFi.status() != WL_CONNECTED) {
      Serial.println("WiFi lost. Reconnecting...");
      connectToWiFi();
    }
  }

  // Check for manual watering
  if (millis() - lastManualCheck > manualCheckInterval) {
    lastManualCheck = millis();
    checkManualWatering();
  }

  // Read moisture
  moistureLevel = readMoisture();

  // Send sensor data and check watering logic
  sendSensorData();
  getSystemSettings(); // Important: Get latest settings including system_enabled
  checkWatering();

  // Print system status occasionally
  static unsigned long lastStatusPrint = 0;
  if (millis() - lastStatusPrint > 30000) {
    lastStatusPrint = millis();
    printSystemStatus();
  }

  delay(5000);
}

float readMoisture() {
  int sensorValue = analogRead(moistureSensorPin);
  float moisture = map(sensorValue, 1500, 4095, 100, 0);
  moisture = constrain(moisture, 0, 100);
  return moisture;
}

void connectToWiFi() {
  Serial.print("Connecting to WiFi: ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);

  unsigned long start = millis();
  while (WiFi.status() != WL_CONNECTED && millis() - start < 15000) {
    delay(500);
    Serial.print(".");
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\nConnected!");
    Serial.print("IP: ");
    Serial.println(WiFi.localIP());
  } else {
    Serial.println("\nFailed to connect.");
  }
}

void sendSensorData() {
  if (WiFi.status() != WL_CONNECTED) return;

  HTTPClient http;
  http.begin(serverUrl);
  http.addHeader("Content-Type", "application/json");

  StaticJsonDocument<200> doc;
  doc["moisture_level"] = moistureLevel;
  doc["pump_status"] = pumpStatus;
  doc["water_used"] = pumpStatus ? 0.1 : 0;

  String payload;
  serializeJson(doc, payload);

  int code = http.POST(payload);

  if (code == 200) {
    String response = http.getString();
    DynamicJsonDocument resDoc(1024);
    DeserializationError error = deserializeJson(resDoc, response);
    
    if (!error) {
      bool shouldWater = resDoc["should_water"] | false;

      // CRITICAL: Check if system is enabled before starting watering
      if (shouldWater && !pumpStatus && autoMode && systemEnabled) {
        moistureThreshold = resDoc["moisture_threshold"] | moistureThreshold;
        startWatering(true);
        Serial.println("Auto watering triggered by server");
      } else if (shouldWater && !systemEnabled) {
        Serial.println("Auto watering requested but SYSTEM IS DISABLED - ignoring");
      }
    }
  }
  http.end();
}

void getSystemSettings() {
  if (WiFi.status() != WL_CONNECTED) return;

  HTTPClient http;
  http.begin(statusUrl);
  int code = http.GET();

  if (code == 200) {
    String response = http.getString();
    DynamicJsonDocument doc(1024);
    if (!deserializeJson(doc, response)) {
      // Update all settings from server
      moistureThreshold = doc["moisture_threshold"] | moistureThreshold;
      pumpDuration = (doc["pump_duration"] | 10) * 1000;
      autoMode = doc["auto_mode"] | autoMode;
      bool newSystemEnabled = doc["system_enabled"] | systemEnabled;
      
      // If system was just disabled, stop any ongoing watering immediately
      if (systemEnabled && !newSystemEnabled && pumpStatus) {
        Serial.println("SYSTEM DISABLED - Stopping ongoing watering");
        stopWatering();
      }
      
      systemEnabled = newSystemEnabled;
      
      Serial.print("Settings updated - System: ");
      Serial.print(systemEnabled ? "ENABLED" : "DISABLED");
      Serial.print(", Auto: ");
      Serial.print(autoMode ? "ON" : "OFF");
      Serial.print(", Threshold: ");
      Serial.print(moistureThreshold);
      Serial.println("%");
    }
  }
  http.end();
}

void checkManualWatering() {
  if (WiFi.status() != WL_CONNECTED) return;

  HTTPClient http;
  http.begin(manualUrl);
  http.addHeader("Content-Type", "application/json");

  int code = http.POST("{}");

  if (code == 200) {
    String response = http.getString();
    DynamicJsonDocument doc(1024);
    if (!deserializeJson(doc, response)) {
      bool manualTrigger = doc["success"] | false;
      String message = doc["message"] | "";
      int manualDuration = (doc["duration"] | 10) * 1000;

      // CRITICAL: Check if system is enabled before manual watering
      if (manualTrigger && !pumpStatus) {
        if (systemEnabled) {
          pumpDuration = manualDuration;
          startWatering(false);
          Serial.println("Manual watering triggered via dashboard!");
        } else {
          Serial.println("Manual watering requested but SYSTEM IS DISABLED - ignoring");
        }
      }
    }
  }
  http.end();
}

void checkWatering() {
  if (!pumpStatus) return;

  // CRITICAL: If system gets disabled during watering, stop immediately
  if (!systemEnabled) {
    Serial.println("System disabled during watering - stopping immediately");
    stopWatering();
    return;
  }

  if (millis() - lastMoistureCheck > moistureCheckInterval) {
    lastMoistureCheck = millis();

    Serial.print("Watering - Moisture: ");
    Serial.print(moistureLevel);
    Serial.print("%, Target: ");
    Serial.print(moistureThreshold);
    Serial.print("%, Mode: ");
    Serial.println(autoMode ? "AUTO" : "MANUAL");

    if (autoMode) {
      if (moistureLevel >= moistureThreshold) {
        Serial.println("Target moisture reached. Stopping watering.");
        stopWatering();
        return;
      }
    } else {
      if (millis() - lastWatering > pumpDuration) {
        Serial.println("Manual watering duration completed.");
        stopWatering();
        return;
      }
    }
  }
}

void startWatering(bool isAutomatic) {
  // CRITICAL: Double-check system is enabled before starting
  if (!systemEnabled) {
    Serial.println("Cannot start watering - SYSTEM IS DISABLED");
    return;
  }

  if (!pumpStatus) {
    pumpStatus = true;
    digitalWrite(relayPin, HIGH);
    lastWatering = millis();
    lastMoistureCheck = millis();

    Serial.println("=== WATERING STARTED ===");
    if (isAutomatic) {
      Serial.println("Mode: AUTOMATIC");
      Serial.print("Target moisture: ");
      Serial.print(moistureThreshold);
      Serial.println("%");
    } else {
      Serial.println("Mode: MANUAL");
      Serial.print("Duration: ");
      Serial.print(pumpDuration / 1000);
      Serial.println(" seconds");
    }
    Serial.println("========================");
  }
}

void stopWatering() {
  if (pumpStatus) {
    pumpStatus = false;
    digitalWrite(relayPin, LOW);
    Serial.println("=== WATERING STOPPED ===");
  }
}

void printSystemStatus() {
  Serial.println("=== SYSTEM STATUS ===");
  Serial.print("System: ");
  Serial.println(systemEnabled ? "ENABLED 🟢" : "DISABLED 🔴");
  Serial.print("Auto Mode: ");
  Serial.println(autoMode ? "ON" : "OFF");
  Serial.print("Moisture: ");
  Serial.print(moistureLevel);
  Serial.print("%, Threshold: ");
  Serial.print(moistureThreshold);
  Serial.println("%");
  Serial.print("Pump: ");
  Serial.println(pumpStatus ? "RUNNING" : "STOPPED");
  Serial.println("====================");
}
