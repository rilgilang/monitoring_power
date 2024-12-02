#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <DHT.h>
#include <WiFi.h>
#include <HTTPClient.h>

// Definisikan pin
#define DHTPIN 4 // Pin data DHT22
#define DHTTYPE DHT22 // Jenis sensor DHT
#define VOLTAGE_PLN_PIN 32
#define CURRENT_PLN_PIN 33
#define VOLTAGE_ACCU_PIN 34
#define CURRENT_ACCU_PIN 35
#define VOLTAGE_UPS_PIN 36
#define CURRENT_UPS_PIN 39

// Inisialisasi objek DHT dan LCD
DHT dht(DHTPIN, DHTTYPE);
LiquidCrystal_I2C lcd(0x27, 20, 4); // Alamat I2C LCD 20x4

// Informasi Telegram
const char* ssid = "Wokwi-GUEST"; // Ganti dengan SSID WiFi Anda
const char* password = ""; // Ganti dengan password WiFi Anda
const char* botToken = "7814812579:AAGnwV0s0xPqCT9p7YlkJ1EGUmtGfFYCb-0"; // Ganti dengan token bot Anda
const char* chatID = "7558516978"; // Ganti dengan chat ID Anda

// Variabel untuk interval pengiriman ke Telegram
unsigned long lastTelegramSendTime = 0;
unsigned long telegramInterval = 3600000; // 1 jam = 3600000 ms

// Variabel untuk status daya
enum StatusDaya { MENURUN, NORMAL, MATI };
StatusDaya statusPLN = NORMAL;
StatusDaya statusACCU = NORMAL;
StatusDaya statusUPS = NORMAL;

// Variabel untuk menandai apakah ada anomali
bool anomaliTerjadi = false;

void setup() {
    Serial.begin(115200);
    dht.begin();
    lcd.init(); // Menentukan jumlah kolom dan baris LCD
    lcd.backlight(); // Nyalakan backlight LCD

    // Koneksi ke WiFi
    WiFi.begin(ssid, password);
    while (WiFi.status() != WL_CONNECTED) {
        delay(1000);
        Serial.println("Menyambung ke WiFi...");
    }
    Serial.println("Terhubung ke WiFi");
}

// Fungsi untuk mengirim pesan Telegram menggunakan HTTPClient
void sendTelegramMessage(String message) {
    if (WiFi.status() == WL_CONNECTED) {
        HTTPClient http;

        // Buat URL untuk mengirim pesan
        String url = "https://api.telegram.org/bot" + String(botToken) + "/sendMessage?chat_id=" + String(chatID) + "&text=" + message;
        
        // Mulai koneksi HTTP
        http.begin(url);
        int httpResponseCode = http.GET(); // Kirim permintaan GET

        if (httpResponseCode > 0) {
            Serial.printf("Pesan berhasil dikirim ke Telegram, kode respons: %d\n", httpResponseCode);
        } else {
            Serial.printf("Gagal mengirim pesan ke Telegram, kode respons: %d\n", httpResponseCode);
        }
        http.end(); // Akhiri koneksi HTTP
    } else {
        Serial.println("Tidak terhubung ke WiFi, pesan tidak dapat dikirim.");
    }
}

void loop() {
    // Baca data DHT22
    float humidity = dht.readHumidity();
    float temperature = dht.readTemperature();

    // Baca sensor tegangan dan arus
    float voltagePLN = analogRead(VOLTAGE_PLN_PIN) * (220.0 / 4095.0); // Ganti 5.0 sesuai dengan Vcc yang digunakan
    float currentPLN = analogRead(CURRENT_PLN_PIN) * (10.0 / 4095.0);
    float voltageACCU = analogRead(VOLTAGE_ACCU_PIN) * (12.0 / 4095.0);
    float currentACCU = analogRead(CURRENT_ACCU_PIN) * (36.0 / 4095.0);
    float voltageUPS = analogRead(VOLTAGE_UPS_PIN) * (12.0 / 4095.0);
    float currentUPS = analogRead(CURRENT_UPS_PIN) * (2.00 / 4095.0);

    // Tampilkan data ke Serial Monitor
    Serial.print("Humidity: ");
    Serial.print(humidity);
    Serial.print("%, Temperature: ");
    Serial.print(temperature);
    Serial.println("°C");
    Serial.print("Voltage PLN: ");
    Serial.print(voltagePLN);
    Serial.print(" V, Current PLN: ");
    Serial.print(currentPLN);
    Serial.println(" A");
    Serial.print("Voltage ACCU: ");
    Serial.print(voltageACCU);
    Serial.print(" V, Current ACCU: ");
    Serial.print(currentACCU);
    Serial.println(" A");
    Serial.print("Voltage UPS: ");
    Serial.print(voltageUPS);
    Serial.print(" V, Current UPS: ");
    Serial.print(currentUPS);
    Serial.println(" A");

    // Mengatur status PLN
    if (voltagePLN <= 0.0) {
        if (statusPLN != MATI) {
            sendTelegramMessage("Peringatan: Daya PLN Mati!");
            statusPLN = MATI;
            anomaliTerjadi = true; // Tandai ada anomali
        }
    } else if (voltagePLN < 180.0) { // Misalnya nilai ambang untuk menurun
        if (statusPLN != MENURUN) {
            sendTelegramMessage("Peringatan: Daya PLN Menurun!");
            statusPLN = MENURUN;
            anomaliTerjadi = true; // Tandai ada anomali
        }
    } else {
        if (statusPLN != NORMAL) {
            sendTelegramMessage("Daya PLN Normal.");
            statusPLN = NORMAL;
            anomaliTerjadi = false; // Reset anomali jika kembali normal
        }
    }

    // Mengatur status ACCU
    if (voltageACCU <= 0.0) {
        if (statusACCU != MATI) {
            sendTelegramMessage("Peringatan: Daya ACCU Mati!");
            statusACCU = MATI;
            anomaliTerjadi = true; // Tandai ada anomali
        }
    } else if (voltageACCU < 10.0) { // Misalnya nilai ambang untuk menurun
        if (statusACCU != MENURUN) {
            sendTelegramMessage("Peringatan: Daya ACCU Menurun!");
            statusACCU = MENURUN;
            anomaliTerjadi = true; // Tandai ada anomali
        }
    } else {
        if (statusACCU != NORMAL) {
            sendTelegramMessage("Daya ACCU Normal.");
            statusACCU = NORMAL;
            anomaliTerjadi = false; // Reset anomali jika kembali normal
        }
    }

    // Mengatur status UPS
    if (voltageUPS <= 0.0) {
        if (statusUPS != MATI) {
            sendTelegramMessage("Peringatan: Daya UPS Mati!");
            statusUPS = MATI;
            anomaliTerjadi = true; // Tandai ada anomali
        }
    } else if (voltageUPS < 10.0) { // Misalnya nilai ambang untuk menurun
        if (statusUPS != MENURUN) {
            sendTelegramMessage("Peringatan: Daya UPS Menurun!");
            statusUPS = MENURUN;
            anomaliTerjadi = true; // Tandai ada anomali
        }
    } else {
        if (statusUPS != NORMAL) {
            sendTelegramMessage("Daya UPS Normal.");
            statusUPS = NORMAL;
            anomaliTerjadi = false; // Reset anomali jika kembali normal
        }
    }

    // Kirim pesan setiap satu jam jika ada anomali
    unsigned long currentMillis = millis();
    if (currentMillis - lastTelegramSendTime >= telegramInterval) {
        if (anomaliTerjadi) {
            sendTelegramMessage("Peringatan: Terjadi anomali pada salah satu sumber daya dalam 1 jam terakhir.");
        } else {
            sendTelegramMessage("Status sistem masih berjalan. Daya PLN, ACCU, dan UPS sedang dipantau.");
        }
        lastTelegramSendTime = currentMillis; // Reset timer
    }

   // Tampilkan data ke LCD
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("T: "); lcd.print(temperature); lcd.print("C H: "); lcd.print(humidity); lcd.print("%");
    lcd.setCursor(0, 1);
    lcd.print("PLN: "); lcd.print(voltagePLN); lcd.print("V "); lcd.print(currentPLN); lcd.print("A");
    lcd.setCursor(0, 2);
    lcd.print("ACCU: "); lcd.print(voltageACCU); lcd.print("V "); lcd.print(currentACCU); lcd.print("A");
    lcd.setCursor(0, 3);
    lcd.print("UPS: "); lcd.print(voltageUPS); lcd.print("V "); lcd.print(currentUPS); lcd.print("A");

    delay(2000); // Delay 2 detik sebelum pembacaan ulang
}
