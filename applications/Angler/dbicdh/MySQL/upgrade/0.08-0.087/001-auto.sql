-- Convert schema '../dbicdh/_source/deploy/0.08/001-auto.yml' to '../dbicdh/_source/deploy/0.087/001-auto.yml':;

;
BEGIN;

;
SET foreign_key_checks=0;

;
CREATE TABLE uri_redirects (
  uri_source varchar(255) NOT NULL,
  uri_target varchar(255) NOT NULL,
  status_code integer NOT NULL DEFAULT 301,
  created datetime NOT NULL,
  last_used datetime NOT NULL,
  PRIMARY KEY (uri_source)
);

;
SET foreign_key_checks=1;

;
ALTER TABLE addresses ADD COLUMN priority integer NOT NULL DEFAULT 0;

;
ALTER TABLE messages ADD UNIQUE messages_uri (uri);

;
ALTER TABLE navigations CHANGE COLUMN uri uri varchar(255) NULL;

;
ALTER TABLE products CHANGE COLUMN weight weight numeric(10, 2) NOT NULL DEFAULT 0;

;

COMMIT;

