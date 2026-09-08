-- Da eseguire una sola volta sul database MySQL del sito (es. via phpMyAdmin
-- del pannello Aruba), prima di mettere online il form contatti.

CREATE TABLE IF NOT EXISTS contatti_lead (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  email VARCHAR(190) NOT NULL,
  messaggio TEXT NOT NULL,
  consenso_privacy TINYINT(1) NOT NULL DEFAULT 0,
  consenso_ip VARCHAR(45) NULL,
  privacy_versione VARCHAR(20) NULL,
  creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
