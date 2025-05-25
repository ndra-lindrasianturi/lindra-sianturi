CREATE TABLE projects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL, -- mandor yang membuat proyek
  project_name VARCHAR(255) NOT NULL,
  customer_name VARCHAR(255),
  start_date DATE,
  end_date DATE,
  status ENUM('install', 'non-install') DEFAULT 'install',
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  FOREIGN KEY (user_id) REFERENCES users(id)
);
