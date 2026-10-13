CREATE DATABASE IF NOT EXISTS travel CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE travel;

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS places (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NOT NULL,
  name VARCHAR(150) NOT NULL,
  province VARCHAR(100) NOT NULL DEFAULT '',
  description TEXT,
  location VARCHAR(255) DEFAULT NULL,
  image VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_cat (category_id),
  CONSTRAINT fk_places_cat FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS contact (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  subject VARCHAR(200) DEFAULT NULL,
  message TEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admin login: username = admin / password = admin123  (change it after login)
INSERT IGNORE INTO admins (username, password) VALUES ('admin', '$2b$12$y3njYSiCa.q08p/ArMnl1eoJ4Vj7bji50fVWQazssCpbcsPbp.IUa');

INSERT IGNORE INTO categories (id, name) VALUES
 (1,'ทะเล / เกาะ'),(2,'ภูเขา / ธรรมชาติ'),(3,'วัด / ประวัติศาสตร์'),(4,'เมือง / ตลาด');

INSERT INTO places (category_id,name,province,description,location,image) VALUES
 (2,'ภูทับเบิก','เพชรบูรณ์','ทะเลหมอกและวิวภูเขาสวยงาม','เพชรบูรณ์','063d85e9c13e4923.jpg'),
 (1,'เกาะสมุย หาดเฉวง','สุราษฎร์ธานี','ชายหาดน้ำใสทรายขาว','สุราษฎร์ธานี','0e50d86a2f9949b5.jpg'),
 (4,'ย่านเมืองเก่าภูเก็ต','ภูเก็ต','ตึกชิโนโปรตุกีสสีสันสดใส','ภูเก็ต','16b66a0d56814056.jpg'),
 (3,'วัดร่องขุ่น','เชียงราย','วัดสีขาวงดงามด้วยศิลปะไทยร่วมสมัย','เชียงราย','178bc0237925df5f.jpg'),
 (1,'เกาะขาม','ชลบุรี','เกาะเล็กน้ำทะเลใสสีฟ้าเทอร์ควอยซ์','ชลบุรี','20b8b05885325d17.jpg'),
 (2,'ป่าสนและทะเลสาบ','เชียงใหม่','ธรรมชาติเงียบสงบกลางป่าสน','เชียงใหม่','239037f035ee832d.jpg'),
 (4,'สวนลุมพินี','กรุงเทพมหานคร','สวนสาธารณะใจกลางเมืองกับวิวตึกสูง','กรุงเทพมหานคร','36d0f23abbeb164a.jpg'),
 (2,'วิวพระอาทิตย์ตกบนยอดเขา','เชียงใหม่','จุดชมวิวพระอาทิตย์ตกสุดอลังการ','เชียงใหม่','380070d858fbfb9b.jpg'),
 (3,'วัดชัยวัฒนาราม','พระนครศรีอยุธยา','โบราณสถานอันยิ่งใหญ่ริมแม่น้ำเจ้าพระยา','พระนครศรีอยุธยา','4127ffa235e5513c.jpg'),
 (4,'ตลาดร่มหุบ','สมุทรสงคราม','ตลาดชื่อดังที่รถไฟวิ่งผ่านกลางตลาด','สมุทรสงคราม','bfb435dbae586071.jpg'),
 (3,'วัดอรุณราชวราราม','กรุงเทพมหานคร','วัดแจ้ง ริมแม่น้ำเจ้าพระยา','กรุงเทพมหานคร','c7a446f2d2124464.jpg'),
 (2,'ดอยหลวงเชียงดาว','เชียงใหม่','ภูเขาสูงสวยงามท่ามกลางสายหมอก','เชียงใหม่','cc6f49ba6695b56f.jpg'),
 (1,'อ่าวมาหยา เกาะพีพี','กระบี่','อ่าวสวยล้อมรอบด้วยหน้าผาหินปูน','กระบี่','d08702e1aa87ae41.jpg'),
 (2,'ยอดเขายามเย็น','ภาคเหนือ','ยอดเขาท่ามกลางแสงสุดท้ายของวัน','ภาคเหนือ','d8fff52e536f0d12.jpg'),
 (3,'วัดมหาธาตุ','พระนครศรีอยุธยา','โบราณสถานเก่าแก่ในอุทยานประวัติศาสตร์','พระนครศรีอยุธยา','e07899e5bfe446d9.jpg');
