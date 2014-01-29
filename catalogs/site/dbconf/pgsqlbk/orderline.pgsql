Database  orderline  orderline.txt __SQLDSN__
Database  orderline  DEFAULT_TYPE varchar(128)
Database  orderline  COLUMN_DEF   "code=varchar(14) NOT NULL PRIMARY KEY"
Database  orderline  COLUMN_DEF   "store_id=varchar(9)"
Database  orderline  COLUMN_DEF   "order_number=varchar(14) NOT NULL"
Database  orderline  COLUMN_DEF   "session=varchar(32) NOT NULL"
Database  orderline  COLUMN_DEF   "username=varchar(32)"
Database  orderline  COLUMN_DEF   "shipmode=varchar(255)"
Database  orderline  COLUMN_DEF   "sku=varchar(64) NOT NULL"
Database  orderline  COLUMN_DEF   "quantity=int NOT NULL"
Database  orderline  COLUMN_DEF   "price=decimal(12,2) NOT NULL"
Database  orderline  COLUMN_DEF   "subtotal=decimal(12,2) NOT NULL"
Database  orderline  COLUMN_DEF   "shipping=decimal(12,2)"
Database  orderline  COLUMN_DEF   "taxable=varchar(3)"
Database  orderline  COLUMN_DEF   "size=varchar(255)"
Database  orderline  COLUMN_DEF   "color=varchar(255)"
Database  orderline  COLUMN_DEF   "options=text"
Database  orderline  COLUMN_DEF   "order_date=varchar(32) NOT NULL"
Database  orderline  COLUMN_DEF   "update_date=timestamp"
Database  orderline  COLUMN_DEF   "status=varchar(64)"
Database  orderline  COLUMN_DEF   "parent=varchar(9)"
Database  orderline  INDEX         store_id order_number
