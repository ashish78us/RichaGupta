ALTER TABLE formation ADD CONSTRAINT status_chk CHECK (status IN ('active','inactive'));

