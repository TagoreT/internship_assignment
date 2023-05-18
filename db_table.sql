CREATE TABLE repositories (
  id INT PRIMARY KEY,
  name VARCHAR(255),
  html_url VARCHAR(255),
  description TEXT,
  created_at DATETIME,
  open_issues INT,
  watchers INT,
  owner_id INT,
  owner_avatar_url VARCHAR(255),
  owner_html_url VARCHAR(255),
  owner_type VARCHAR(50),
  owner_site_admin BOOLEAN
);