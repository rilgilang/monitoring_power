-- monitoring_power_2.log definition

CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `temperature` float NOT NULL,
  `pln_volt` float NOT NULL,
  `pln_current` float NOT NULL,
  `pln_activity` varchar(255) DEFAULT NULL,
  `pln_status` varchar(255) DEFAULT NULL,
  `accu_volt` float NOT NULL,
  `accu_current` float NOT NULL,
  `accu_activity` varchar(255) DEFAULT NULL,
  `accu_status` varchar(255) DEFAULT NULL,
  `ups_volt` float DEFAULT NULL,
  `ups_current` float DEFAULT NULL,
  `ups_activity` varchar(255) DEFAULT NULL,
  `ups_status` varchar(255) DEFAULT NULL,
  `device_id` int(11) NOT NULL,
  `soc` float DEFAULT NULL,
  `accu_estimate_time` int(11) DEFAULT NULL,
  `accu_info` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `device_id` (`device_id`),
  CONSTRAINT `log_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `device` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

-- monitoring_power_2.device definition

CREATE TABLE `device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_name` varchar(255) NOT NULL,
  `device_type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;