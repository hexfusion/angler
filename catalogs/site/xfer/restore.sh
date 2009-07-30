#!/bin/sh

DIR=$1

USAGE="ic_restore.sh [directory]";
if test -n "$DIR"
then
	cd $DIR || (echo "$DIR: does not exist"; echo $USAGE; exit 1);
fi

if test ! -d xfer/exports
then
	echo "Not a restore directory for Interchange."
	echo $USAGE
	exit 1;
fi

cp -r xfer/exports/* products

mkdir -p /home/virtual/site12/fst/var/www/html/site2/images
cp -r images/* /home/virtual/site12/fst/var/www/html/site2/images
rm -rf images
ln -s /home/virtual/site12/fst/var/www/html/site2/images images

mysql -u root -ps1a2mayfly test_site2 < xfer/dumps/test_site2.mysql.maindump
CURDIR=`pwd`
cat <<EOF
Finished the restore script.

You should inspect the output above (if any) in case of errors.
You will need to add the following line to interchange.cfg if
you haven't done so already:

	Catalog  site2 $CURDIR /cgi-bin/site2
You will also need to copy an appropriate link program if it is
not already in place. 

And of course you need to restart Interchange.
EOF

