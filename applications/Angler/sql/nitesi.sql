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

-- carts and cart_products

   CREATE TABLE carts (
      code integer NOT NULL,
      name character varying(255) DEFAULT '' NOT NULL,
      uid integer DEFAULT 0 NOT NULL,
      session_id character varying(255) DEFAULT '' NOT NULL,
      created integer DEFAULT 0 NOT NULL,
      last_modified integer DEFAULT 0 NOT NULL,
      type character varying(32) DEFAULT '' NOT NULL,
      approved boolean,
      status character varying(32) DEFAULT '' NOT NULL
   );

   CREATE TABLE cart_products (
      cart integer NOT NULL,
      sku character varying(32) NOT NULL,
      position integer NOT NULL,
      quantity integer DEFAULT 1 NOT NULL,
      priority integer DEFAULT 0 NOT NULL
    );
