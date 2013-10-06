ALTER table navigation ADD COLUMN template varchar(255) NOT NULL DEFAULT '';
ALTER table navigation ADD COLUMN language varchar(8) NOT NULL DEFAULT '';
ALTER table navigation ADD COLUMN alias integer NOT NULL DEFAULT 0;

