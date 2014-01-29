Database  tree  tree.txt  __SQLDSN__

Database  tree  DEFAULT_TYPE varchar(255)
Database  tree  AUTO_SEQUENCE tree_seq
Database  tree  AUTO_SEQUENCE_DROP 1
Database  tree  KEY          code
Database  tree  COLUMN_DEF   "parent_fld=varchar(20)"
Database  tree  COLUMN_DEF   "msort=varchar(8)"
Database  tree  COLUMN_DEF   "extended=text"
Database  tree  COLUMN_DEF   "inactive=int not null default 0"
Database  tree  COLUMN_DEF   "member=varchar(1)"
Database  tree  INDEX        parent_fld
Database  tree  INDEX        mgroup
Database  tree  INDEX        msort
Database  tree  NO_ASCII_INDEX   1
Database  tree  HIDE_FIELD	 inactive
