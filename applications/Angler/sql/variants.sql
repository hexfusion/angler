DROP TABLE IF EXISTS product_attributes;

CREATE TABLE product_attributes (
  code serial NOT NULL PRIMARY KEY,
  sku varchar(32) NOT NULL,
  name varchar(32) NOT NULL,
  value text NOT NULL default '',
  original_sku varchar(32) NOT NULL default ''
);

CREATE INDEX product_attributes_sku ON product_attributes (sku);

ALTER TABLE products ADD COLUMN canonical_sku varchar(32) NOT NULL DEFAULT '';