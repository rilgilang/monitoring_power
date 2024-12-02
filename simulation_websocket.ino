#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <DHT.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoWebsockets.h>
#include <ArduinoJson.h>

using namespace websockets;

WebsocketsClient wsClient;

// Pin Definitions
#define DHTPIN 4
#define DHTTYPE DHT22
#define VOLTAGE_PLN_PIN 32
#define CURRENT_PLN_PIN 33
#define VOLTAGE_ACCU_PIN 34
#define CURRENT_ACCU_PIN 35
#define VOLTAGE_UPS_PIN 36
#define CURRENT_UPS_PIN 39

// Struct Definitions
struct PowerSource {
    float voltage = 0.0;
    float current = 0.0;
    String activity = "Normal";
    String status = "Aktif";
};

struct SensorData {
    bool status_changed = false;
    float humidity = 0.0;
    float temperature = 0.0;
    PowerSource pln;
    PowerSource accu;
    PowerSource ups;
};

// Global Variables
SensorData data;

DHT dht(DHTPIN, DHTTYPE);
LiquidCrystal_I2C lcd(0x27, 16, 4);

const char* ssid = "Wokwi-GUEST";
const char* password = "";
const char* wsServerURL = "wss://s13783.blr1.piesocket.com/v3/1?api_key=7SEqHklfXLf4YSvF8OmgAd147ewDT0RT2tZrCE3f";

unsigned long lastSendTime = 0;
unsigned long sendInterval = 2000;
unsigned long lastTelegramSendTime = 0;
unsigned long telegramInterval = 3600000;

String convertToJson(const SensorData &data) {
    // Create a JSON document
    StaticJsonDocument<512> doc;

    // Add data to the JSON document
    doc["status_changed"] = data.status_changed;
    doc["humidity"] = data.humidity;
    doc["temperature"] = data.temperature;

    // Add nested objects for PLN
    JsonObject pln = doc.createNestedObject("pln");
    pln["voltage"] = data.pln.voltage;
    pln["current"] = data.pln.current;
    pln["activity"] = data.pln.activity.c_str(); // Convert std::string to C-string
    pln["status"] = data.pln.status.c_str();

    // Add nested objects for ACCU
    JsonObject accu = doc.createNestedObject("accu");
    accu["voltage"] = data.accu.voltage;
    accu["current"] = data.accu.current;
    accu["activity"] = data.accu.activity.c_str();
    accu["status"] = data.accu.status.c_str();

    // Add nested objects for UPS
    JsonObject ups = doc.createNestedObject("ups");
    ups["voltage"] = data.ups.voltage;
    ups["current"] = data.ups.current;
    ups["activity"] = data.ups.activity.c_str();
    ups["status"] = data.ups.status.c_str();

    // Serialize JSON to string
    String jsonString;
    serializeJson(doc, jsonString);

    return jsonString;
}

void connectWebSocket() {
    Serial.println("Attempting to connect to WebSocket server...");
    if (wsClient.connect(wsServerURL)) {
        Serial.println("Connected to WebSocket server");
        wsClient.onMessage([](WebsocketsMessage message) {
            Serial.println("Message received: " + message.data());
        });
        wsClient.onEvent([](WebsocketsEvent event, String data) {
            if (event == WebsocketsEvent::ConnectionClosed) {
                Serial.println("WebSocket connection closed, reconnecting...");
                connectWebSocket();
            }
        });
    } else {
        Serial.println("Failed to connect, retrying in 5 seconds...");
        delay(5000);
        connectWebSocket();
    }
}

void sendWebSocket(String sensorData) {
    if (wsClient.available()) {
        wsClient.send(sensorData);
        Serial.println("Data sent: " + sensorData);
    } else {
        Serial.println("WebSocket not connected, reconnecting...");
        connectWebSocket();
    }
}

void sendTelegramMessage(String message) {
    if (WiFi.status() == WL_CONNECTED) {
        HTTPClient http;
        String url = "https://api.telegram.org/bot<YourBotToken>/sendMessage?chat_id=<YourChatID>&text=" + message;
        http.begin(url);
        int httpResponseCode = http.GET();
        if (httpResponseCode > 0) {
            Serial.printf("Telegram message sent, response code: %d\n", httpResponseCode);
        } else {
            Serial.printf("Failed to send message, error code: %d\n", httpResponseCode);
        }
        http.end();
    } else {
        Serial.println("WiFi disconnected, unable to send message.");
    }
}

void processSensorData(float humidity, float temperature, float voltagePLN, float currentPLN, float voltageACCU, float currentACCU, float voltageUPS, float currentUPS) {
    data.humidity = humidity;
    data.temperature = temperature;

    data.pln.voltage = voltagePLN;
    data.pln.current = currentPLN;

    data.accu.voltage = voltageACCU;
    data.accu.current = currentACCU;

    data.ups.voltage = voltageUPS;
    data.ups.current = currentUPS;

    if (voltagePLN <= 0.0 && data.pln.activity != "Daya PLN Mati!") {
        sendTelegramMessage("Peringatan: Daya PLN Mati!");
        data.pln.activity = "Daya PLN Mati!";
        data.status_changed = true;
    } else if (voltagePLN < 180.0 && data.pln.activity != "Daya PLN Menurun!") {
        sendTelegramMessage("Peringatan: Daya PLN Menurun!");
        data.pln.activity = "Daya PLN Menurun!";
        data.status_changed = true;
    } else if (data.pln.activity != "Daya PLN Normal") {
        sendTelegramMessage("Daya PLN Normal.");
        data.pln.activity = "Daya PLN Normal";
        data.status_changed = true;
    }

    if (voltageACCU <= 0.0 && data.accu.activity != "Daya ACCU Mati!") {
        sendTelegramMessage("Peringatan: Daya ACCU Mati!");
        data.accu.activity = "Daya ACCU Mati!";
        data.status_changed = true;
    } else if (voltageACCU < 10.0 && data.accu.activity != "Daya ACCU Menurun!") {
        sendTelegramMessage("Peringatan: Daya ACCU Menurun!");
        data.accu.activity = "Daya ACCU Menurun!";
        data.status_changed = true;
    } else if (data.accu.activity != "Daya ACCU Normal") {
        sendTelegramMessage("Daya ACCU Normal.");
        data.accu.activity = "Daya ACCU Normal";
        data.status_changed = true;
    }

    if (voltageUPS <= 0.0 && data.ups.activity != "Daya UPS Mati!") {
        sendTelegramMessage("Peringatan: Daya UPS Mati!");
        data.ups.activity = "Daya UPS Mati!";
        data.status_changed = true;
    } else if (voltageUPS < 10.0 && data.ups.activity != "Daya UPS Menurun!") {
        sendTelegramMessage("Peringatan: Daya UPS Menurun!");
        data.ups.activity = "Daya UPS Menurun!";
        data.status_changed = true;
    } else if (data.ups.activity != "Daya UPS Normal") {
        sendTelegramMessage("Daya UPS Normal.");
        data.ups.activity = "Daya UPS Normal";
        data.status_changed = true;
    }

    if (millis() - lastTelegramSendTime >= telegramInterval) {
        if (data.status_changed) {
            sendTelegramMessage("Anomaly detected in the last hour.");
        } else {
            sendTelegramMessage("System is running normally.");
        }
        lastTelegramSendTime = millis();
    }

    String jsonData = convertToJson(data);

    Serial.println(jsonData);

    // sendWebSocket(jsonData);
}

void setup() {
    Serial.begin(115200);
    dht.begin();
    lcd.init();
    lcd.backlight();

    WiFi.begin(ssid, password);
    while (WiFi.status() != WL_CONNECTED) {
        delay(1000);
        Serial.println("Connecting to WiFi...");
    }
    Serial.println("WiFi connected");

    // connectWebSocket();
}

void loop() {
    wsClient.poll();

    float humidity = dht.readHumidity();
    float temperature = dht.readTemperature();

    if (isnan(humidity) || isnan(temperature)) {
        Serial.println("Failed to read from DHT sensor!");
        return;
    }

    float voltagePLN = analogRead(VOLTAGE_PLN_PIN) * (220.0 / 4095.0);
    float currentPLN = analogRead(CURRENT_PLN_PIN) * (10.0 / 4095.0);
    float voltageACCU = analogRead(VOLTAGE_ACCU_PIN) * (12.0 / 4095.0);
    float currentACCU = analogRead(CURRENT_ACCU_PIN) * (36.0 / 4095.0);
    float voltageUPS = analogRead(VOLTAGE_UPS_PIN) * (12.0 / 4095.0);
    float currentUPS = analogRead(CURRENT_UPS_PIN) * (2.0 / 4095.0);

    processSensorData(humidity, temperature, voltagePLN, currentPLN, voltageACCU, currentACCU, voltageUPS, currentUPS);

    // Display on LCD
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Temp: "); lcd.print(temperature); lcd.print("C");
    lcd.setCursor(0, 1);
    lcd.print("PLN: "); lcd.print(voltagePLN); lcd.print("V");
    lcd.setCursor(0, 2);
    lcd.print("ACCU: "); lcd.print(voltageACCU); lcd.print("V");
    lcd.setCursor(0, 3);
    lcd.print("UPS: "); lcd.print(voltageUPS); lcd.print("V");
}