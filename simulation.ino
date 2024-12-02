#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoWebsockets.h>
#include <DHT.h>

using namespace websockets;

WebsocketsClient wsClient;

// Konfigurasi WiFi
const char* ssid = "Wokwi-GUEST";
const char* password = "";

// URL API untuk menyimpan data ke database
const String apiUrl = "http://192.168./api/insert_data.php";
const char* wsServerURL = "wss://s13783.blr1.piesocket.com/v3/1?api_key=7SEqHklfXLf4YSvF8OmgAd147ewDT0RT2tZrCE3f";

// Konfigurasi Telegram
const String botToken = "";
const String chatID = "";

// Keterangan sumber daya
const String keteranganSumberDayaPLN = "Sumber daya dari Listrik PLN.";
const String keteranganSumberDayaACCU = "Sumber daya cadangan dari ACCU.";

// Pin sensor
#define ZMPT101B_PIN 32
#define ACS712_AC_PIN 33
#define VOLTAGE_ACC_PIN 34
#define ACS712_DC_PIN 35
#define DHTPIN 4
#define DHTTYPE DHT22

// Inisialisasi DHT
DHT dht(DHTPIN, DHTTYPE);

// Variabel global
float SoE_kWh = 0;       // State of Energy (kWh)
float SoC = 100;         // State of Charge (%)
float SoCEstimatedTime = 0; // Waktu tersisa dalam jam
float accuCapacityAh = 100; // Kapasitas Accu (Ah)
String aktivitas = "Normal"; // Status aktivitas (Normal/Abnormal)

// Fungsi pembacaan tegangan AC
float readVoltageAC() {
  int rawValue = analogRead(ZMPT101B_PIN);
  return rawValue * (3.3 / 4095.0) / 0.01; // Kalibrasi sesuai sensor
}

// Fungsi pembacaan arus
float readCurrent(int pin) {
  int rawValue = analogRead(pin);
  float voltage = rawValue * (3.3 / 4095.0);
  return (voltage - 2.5) / 0.066; // Kalibrasi sesuai sensor
}

// Fungsi pembacaan tegangan DC
float readVoltageDC() {
  int rawValue = analogRead(VOLTAGE_ACC_PIN);
  return rawValue * (3.3 / 4095.0) * 5.0; // Kalibrasi sesuai sensor
}

// Fungsi untuk menentukan aktivitas
void determineActivity(float voltageAC, float voltageDC, float temperature) {
  if (voltageAC < 180 || voltageAC > 240 || voltageDC < 11 || temperature > 50) {
    aktivitas = "Abnormal";
  } else {
    aktivitas = "Normal";
  }
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

// Fungsi untuk mengirim data ke database
void sendDataToDatabase(float voltageAC, float currentAC, float powerAC, float voltageDC, float SoE, float SoC, float temperature, float humidity) {
  HTTPClient http;
  http.begin(apiUrl);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  // Format data untuk dikirim
  String postData = "sumber_daya_pln=" + keteranganSumberDayaPLN +
                    "&sumber_daya_accu=" + keteranganSumberDayaACCU +
                    "&voltage_ac=" + String(voltageAC, 2) +
                    "&current_ac=" + String(currentAC, 2) +
                    "&power_ac=" + String(powerAC, 2) +
                    "&voltage_dc=" + String(voltageDC, 2) +
                    "&soe=" + String(SoE, 2) +
                    "&soc=" + String(SoC, 2) +
                    "&temperature=" + String(temperature, 2) +
                    "&humidity=" + String(humidity, 2) +
                    "&aktivitas=" + aktivitas;

  int httpResponseCode = http.POST(postData);

  if (httpResponseCode > 0) {
    Serial.println("Data berhasil dikirim ke database.");
    Serial.println("Response: " + http.getString());
  } else {
    Serial.println("Gagal mengirim data ke database.");
  }

  http.end();
}

// Fungsi untuk mengirim data ke Telegram
void sendToTelegram(float voltageAC, float currentAC, float powerAC, float voltageDC, float SoE, float SoC, float estimatedTime, float temperature, float humidity) {
  HTTPClient http;
  String message = "Monitoring Data:\n"
                   "Sumber Daya PLN: " + keteranganSumberDayaPLN + "\n"
                   "Sumber Daya ACCU: " + keteranganSumberDayaACCU + "\n"
                   "Voltage AC: " + String(voltageAC, 2) + " V\n"
                   "Current AC: " + String(currentAC, 2) + " A\n"
                   "Power AC: " + String(powerAC, 2) + " W\n"
                   "SoE (kWh): " + String(SoE, 2) + " kWh\n"
                   "Voltage DC: " + String(voltageDC, 2) + " V\n"
                   "SoC: " + String(SoC, 2) + " %\n"
                   "Estimated Time: " + (estimatedTime > 0 ? String(estimatedTime, 2) + " hours" : "Unlimited") + "\n"
                   "Temperature: " + String(temperature, 2) + " C\n"
                   "Humidity: " + String(humidity, 2) + " %\n"
                   "Aktivitas: " + aktivitas;

  String url = "https://api.telegram.org/bot" + botToken + "/sendMessage";
  String payload = "chat_id=" + chatID + "&text=" + message;

  http.begin(url);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  int httpResponseCode = http.POST(payload);
  if (httpResponseCode > 0) {
    Serial.println("Pesan berhasil dikirim ke Telegram.");
  } else {
    Serial.println("Gagal mengirim pesan ke Telegram.");
  }
  http.end();
}

void setup() {
  Serial.begin(115200);

  // Menghubungkan ke WiFi
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("WiFi connected");

  dht.begin(); // Inisialisasi DHT
}

void loop() {

  wsClient.poll();

  // Membaca data sensor
  float voltageAC = readVoltageAC();
  float currentAC = readCurrent(ACS712_AC_PIN);
  float powerAC = voltageAC * currentAC;

  float voltageDC = readVoltageDC();
  float temperature = dht.readTemperature();
  float humidity = dht.readHumidity();

  // Tentukan status aktivitas
  determineActivity(voltageAC, voltageDC, temperature);

  // Menampilkan data ke Serial Monitor
//   Serial.printf("Voltage AC: %.2f V\n", voltageAC);
//   Serial.printf("Current AC: %.2f A\n", currentAC);
//   Serial.printf("Power AC: %.2f W\n", powerAC);
//   Serial.printf("Voltage DC: %.2f V\n", voltageDC);
//   Serial.printf("Temperature: %.2f C\n", temperature);
//   Serial.printf("Humidity: %.2f %%\n", humidity);
//   Serial.printf("Aktivitas: %s\n", aktivitas.c_str());

  // Mengirim data ke database
  sendDataToDatabase(voltageAC, currentAC, powerAC, voltageDC, SoE_kWh, SoC, temperature, humidity);

  // Mengirim data ke Telegram
  sendToTelegram(voltageAC, currentAC, powerAC, voltageDC, SoE_kWh, SoC, SoCEstimatedTime, temperature, humidity);


 String payload = "{"
                   "\"sumber_daya_pln\":\"" + keteranganSumberDayaPLN + "\","
                   "\"sumber_daya_accu\":\"" + keteranganSumberDayaACCU + "\","
                   "\"voltage_ac\":" + voltageAC + ","
                   "\"current_ac\":"+ currentAC + ","
                   "\"power_ac\":+ powerAC + ","
                   "\"soe_kwh\":"+ SoE_kWh + ","
                   "\"voltage_dc\":"+ voltageDC + ","
                   "\"soc\":"+ SoC + ","
                   "\"estimated_time\":\"" + (SoCEstimatedTime > 0 ? String(SoCEstimatedTime, 2) + " hours" : "Unlimited") + "\","
                   "\"temperature\":"+ temperature + ","
                   "\"humidity\":\"" + humidity + "\","
                   "\"aktivitas\":\"" + aktivitas + "\""
                   "}";

  sendWebSocket(payload);

  Serial.println(payload);

  delay(5000); // Kirim data setiap 5 detik
}
