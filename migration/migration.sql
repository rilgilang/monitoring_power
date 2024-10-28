-- Creating tables for the ERD schema

-- Lokasi table
CREATE TABLE Lokasi (
    lokasi_id INT AUTO_INCREMENT PRIMARY KEY,
    nama_lokasi VARCHAR(255) NOT NULL
);

-- SumberDaya table
CREATE TABLE SumberDaya (
    sumberdaya_id INT AUTO_INCREMENT PRIMARY KEY,
    lokasi_id INT,
    jenis_sumberdaya VARCHAR(255) NOT NULL,
    FOREIGN KEY (lokasi_id) REFERENCES Lokasi(lokasi_id)
);

-- Monitoring table
CREATE TABLE Monitoring (
    monitoring_id INT AUTO_INCREMENT PRIMARY KEY,
    sumberdaya_id INT,
    tegangan DECIMAL(10, 2),
    arus DECIMAL(10, 2),
    aktivitas VARCHAR(255),
    status VARCHAR(50),
    suhu DECIMAL(5, 2),
    FOREIGN KEY (sumberdaya_id) REFERENCES SumberDaya(sumberdaya_id)
);

-- RealtimeData table
CREATE TABLE RealtimeData (
    data_id INT AUTO_INCREMENT PRIMARY KEY,
    sumberdaya_id INT,
    waktu_pengukuran DATETIME,
    tegangan DECIMAL(10, 2),
    arus DECIMAL(10, 2),
    FOREIGN KEY (sumberdaya_id) REFERENCES SumberDaya(sumberdaya_id)
);

-- LogAktivitas table
CREATE TABLE LogAktivitas (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    monitoring_id INT,
    waktu DATETIME,
    aktivitas VARCHAR(255),
    status VARCHAR(50),
    sumberdaya VARCHAR(255),
    tegangan_lokasi DECIMAL(10, 2),
    FOREIGN KEY (monitoring_id) REFERENCES Monitoring(monitoring_id)
);

-- Notifikasi table
CREATE TABLE Notifikasi (
    notifikasi_id INT AUTO_INCREMENT PRIMARY KEY,
    lokasi_id INT,
    pesan TEXT,
    status VARCHAR(50),
    tanggal DATETIME,
    FOREIGN KEY (lokasi_id) REFERENCES Lokasi(lokasi_id)
);
