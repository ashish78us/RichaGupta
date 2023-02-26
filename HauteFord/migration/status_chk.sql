ALTER TABLE course ADD COLUMN status varchar(255) DEFAULT 'active';
ALTER TABLE course ADD CONSTRAINT status_chk CHECK (status IN ('active','inactive'));

