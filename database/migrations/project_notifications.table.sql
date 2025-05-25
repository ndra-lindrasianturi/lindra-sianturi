CREATE TABLE project_notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL, -- penerima notifikasi ( mandor )
  project_id INT NOT NULL,
  is_read INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (project_id) REFERENCES projects(id)
);
