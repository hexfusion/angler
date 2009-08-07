##          Movable Type configuration file                   ##
##                                                            ##
## This file defines system-wide settings for Movable Type    ##
## In total, there are over a hundred options, but only those ##
## critical for everyone are listed below.                    ##
##                                                            ##
## Information on all others can be found at:                 ##
## http://www.movabletype.org/documentation/appendices/config-directives/ ##

################################################################
##################### REQUIRED SETTINGS ########################
################################################################

# The CGIPath is the URL to your Movable Type directory
CGIPath    http://www.westbranchresort.com/apps/blog/

# The StaticWebPath is the URL to your mt-static directory
# Note: Check the installation documentation to find out 
# whether this is required for your environment.  If it is not,
# simply remove it or comment out the line by prepending a "#".
# StaticWebPath    http://www.example.com/mt-static

#================ DATABASE SETTINGS ==================
#   REMOVE all sections below that refer to databases 
#   other than the one you will be using.

##### MYSQL #####
ObjectDriver DBI::mysql
Database test_site2
DBUser root
DBPassword s1a2mayfly
DBHost localhost

##### POSTGRESQL #####
#ObjectDriver DBI::postgres
#Database wba
#DBUser postgres
#DBPassword mayfly6969
#DBHost localhost

##### SQLITE #####
#ObjectDriver DBI::sqlite
#Database /path/to/sqlite/database/file

