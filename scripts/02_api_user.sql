-- ----------------------------------------------------------------------------
-- Insert `app_users`
-- ----------------------------------------------------------------------------

-- User: terst@test.com, password: test
INSERT INTO app_users (id, email, password, create_date, update_date, status) 
VALUES (NULL, 'test@test.com', '$2y$10$1OoDF//gr0a7TQfUTU3fiuKxj9ZH5CJd8OHQrM0jy5dd7s9kgnYVW', NOW(), NOW(), true);

COMMIT;
