#include <ESP8266WiFi.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <SPI.h>
#include <MFRC522.h>
#include <ESP8266HTTPClient.h>
#include <WiFiUdp.h>
#include <NTPClient.h>
#include <time.h>

// RFID
#define SS_PIN D4
#define RST_PIN D3
MFRC522 rfid(SS_PIN, RST_PIN);

// LCD
LiquidCrystal_I2C lcd(0x27, 16, 2);

// WiFi
const char* ssid = "Xiaomi 14T";
const char* password = "12345678";

// Laravel Server
const String server = "http://192.168.129.144/arduino_sensor/public/api/v1";

// NTP Client
WiFiUDP ntpUDP;
NTPClient timeClient(ntpUDP, "id.pool.ntp.org", 25200, 60000);  // GMT+7
String TAG = "";
WiFiClient wifiClient;

// Ambil tanggal: YYYY-MM-DD
String getTanggal() {
  time_t rawTime = timeClient.getEpochTime();
  struct tm* timeinfo = localtime(&rawTime);
  char buffer[11];
  sprintf(buffer, "%04d-%02d-%02d", timeinfo->tm_year + 1900, timeinfo->tm_mon + 1, timeinfo->tm_mday);
  return String(buffer);
}

// Ambil waktu: HH:MM:SS
String getWaktu() {
  time_t rawTime = timeClient.getEpochTime();
  struct tm* timeinfo = localtime(&rawTime);
  char buffer[9];
  sprintf(buffer, "%02d:%02d:%02d", timeinfo->tm_hour, timeinfo->tm_min, timeinfo->tm_sec);
  return String(buffer);
}

void setup() {
  Serial.begin(115200);
  SPI.begin();
  rfid.PCD_Init();
  Wire.begin(D2, D1);
  lcd.begin(16, 2);
  lcd.backlight();

  lcd.setCursor(0, 0);
  lcd.print("Menghubungkan...");
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);

  int tries = 0;
  while (WiFi.status() != WL_CONNECTED && tries < 20) {
    delay(500);
    Serial.print(".");
    tries++;
  }

  lcd.clear();
  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\nWiFi Terhubung:");
    Serial.println(WiFi.localIP());
    lcd.print("WiFi Terhubung");

    timeClient.begin();
    if (!timeClient.forceUpdate()) {
      Serial.println("Gagal sync waktu NTP");
    } else {
      Serial.println("Waktu sinkron NTP OK");
    }

  } else {
    Serial.println("\nGagal konek WiFi");
    lcd.print("WiFi Gagal!");
    delay(3000);
  }

  delay(2000);
  lcd.clear();
  lcd.print("Tempel Kartu");
}

void loop() {
  timeClient.update();

  if (!rfid.PICC_IsNewCardPresent() || !rfid.PICC_ReadCardSerial()) return;

  TAG = "";
  for (byte i = 0; i < rfid.uid.size; i++) {
    TAG += String(rfid.uid.uidByte[i], HEX);
  }
  TAG.toUpperCase();

  Serial.print("UID: ");
  Serial.println(TAG);

  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Mengecek UID...");

  if (WiFi.status() == WL_CONNECTED) {
    String waktu = getWaktu();     // dari NTP
    String tanggal = getTanggal(); // dari NTP

    // Kirim ke /tambah
    HTTPClient httpTambah;
    String urlTambah = server + "/tambah?tag=" + TAG;
    Serial.println("Kirim ke: " + urlTambah);
    httpTambah.begin(wifiClient, urlTambah);
    int codeTambah = httpTambah.GET();
    if (codeTambah > 0) {
      String resTambah = httpTambah.getString();
      Serial.print("Respon Tambah: ");
      Serial.println(resTambah);
    } else {
      Serial.println("Gagal kirim ke /tambah");
    }
    httpTambah.end();

    // Kirim ke /absen
    HTTPClient httpAbsen;
    String urlAbsen = server + "/absen?tag=" + TAG + "&tanggal=" + tanggal + "&waktu=" + waktu;
    Serial.println("Kirim ke: " + urlAbsen);
    httpAbsen.begin(wifiClient, urlAbsen);
    int codeAbsen = httpAbsen.GET();

    if (codeAbsen > 0) {
      String resAbsen = httpAbsen.getString();
      Serial.print("Respon: ");
      Serial.println(resAbsen);
      resAbsen.trim();

      lcd.clear();
      if (resAbsen.indexOf("OK") >= 0) {
        lcd.setCursor(0, 0);
        lcd.print("Akses Diterima");
        lcd.setCursor(0, 1);
        lcd.print("Selamat Datang");
      } else if (resAbsen.indexOf("SUDAH ABSEN") >= 0) {
        lcd.setCursor(0, 0);
        lcd.print("Sudah Absen");
        lcd.setCursor(0, 1);
        lcd.print("Hari Ini");
      } else if (resAbsen.indexOf("BELUM TERDAFTAR") >= 0) {
        lcd.setCursor(0, 0);
        lcd.print("Kartu Baru");
        lcd.setCursor(0, 1);
        lcd.print("Daftarkan Dulu");
      } else {
        lcd.setCursor(0, 0);
        lcd.print("Respon Tidak");
        lcd.setCursor(0, 1);
        lcd.print("Dikenali");
      }
    } else {
      Serial.print("HTTP Error Code: ");
      Serial.println(codeAbsen);
      lcd.clear();
      lcd.setCursor(0, 0);
      lcd.print("HTTP Gagal");
    }

    httpAbsen.end();
  } else {
    lcd.setCursor(0, 0);
    lcd.print("WiFi Putus!");
    Serial.println("WiFi Putus!");
  }

  delay(3000);
  lcd.clear();
  lcd.print("Tempel Kartu");
  TAG = "";

  rfid.PICC_HaltA();
  rfid.PCD_StopCrypto1();
}
