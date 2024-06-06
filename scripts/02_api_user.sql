-- ----------------------------------------------------------------------------
-- Insert `app_users`
-- ----------------------------------------------------------------------------

-- User: terst@test.com, password: Test123!
INSERT INTO app_users (id, email, password, create_date, update_date, status) 
VALUES (NULL, 'test@test.com', '$2y$10$F5E5n6bJrEiLxCLa36OwFee6NtGDgX1miJcJtw.C1Chde.AM4PQ3K', NOW(), NOW(), true);

COMMIT;
