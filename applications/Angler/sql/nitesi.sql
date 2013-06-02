 ALTER table products ADD COLUMN   priority integer NOT NULL DEFAULT 0;
 ALTER table products ADD COLUMN   uri varchar(255) NOT NULL DEFAULT '';

   CREATE TABLE navigation (
      code serial NOT NULL,
      uri varchar(255) NOT NULL DEFAULT '',
      type varchar(32) NOT NULL DEFAULT '',
      scope varchar(32) NOT NULL DEFAULT '',
      name varchar(255) NOT NULL DEFAULT '',
      description text NOT NULL DEFAULT '',
      parent integer NOT NULL DEFAULT 0,
      priority integer NOT NULL DEFAULT 0,
      count integer NOT NULL DEFAULT 0,
      inactive boolean NOT NULL default FALSE,
      entered timestamp DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY(code),
      UNIQUE(uri)
    );

    CREATE TABLE navigation_products (
      sku varchar(32) NOT NULL,
      navigation integer NOT NULL,
      type varchar(16) NOT NULL DEFAULT '',
      key(sku,navigation)
    );

