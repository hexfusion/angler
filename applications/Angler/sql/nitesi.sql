ALTER table navigation ADD COLUMN template varchar(255) NOT NULL DEFAULT '';
ALTER table navigation ADD COLUMN language varchar(8) NOT NULL DEFAULT '';
ALTER table navigation ADD COLUMN alias integer NOT NULL DEFAULT 0;


   CREATE TABLE users (
      uid serial primary key,
      username varchar(255) NOT NULL,
      email varchar(255) NOT NULL DEFAULT '',
      password varchar(255) NOT NULL DEFAULT '',
      first_name varchar(255) NOT NULL DEFAULT '',
      last_name varchar(255) NOT NULL DEFAULT '',
      last_login integer NOT NULL DEFAULT 0,
      created datetime NOT NULL,
      modified datetime NOT NULL,
      inactive boolean NOT NULL DEFAULT FALSE
    );

  CREATE TABLE roles (
      rid serial primary key,
      name varchar(32) NOT NULL,
      label varchar(255) NOT NULL
    );

    INSERT INTO roles (rid,name,label) VALUES (1, 'anonymous', 'Anonymous Users');
    INSERT INTO roles (rid,name,label) VALUES (2, 'authenticated', 'Authenticated Users');

    CREATE TABLE user_roles (
      uid integer DEFAULT 0 NOT NULL,
      rid integer DEFAULT 0 NOT NULL,
      PRIMARY KEY (uid, rid)
    );

    CREATE INDEX idx_user_roles_rid ON user_roles (rid);


    CREATE TABLE permissions (
      rid integer not null default 0,
      uid integer not null default 0,
      perm varchar(255) not null default ''
    );

    INSERT INTO permissions (rid,perm) VALUES (1,'anonymous');
    INSERT INTO permissions (rid,perm) VALUES (2,'authenticated');
