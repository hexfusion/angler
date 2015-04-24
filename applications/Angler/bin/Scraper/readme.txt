Run in folowing order

Set DB setting in config.yml

perl deploy.pl


# Simms
perl simmsFetchIds.pl 
perl simmsFetchHTML.pl 
perl simmsParseHTML.pl 

# Patagonia
perl simmsFetchIds.pl 
perl simmsFetchHTML.pl 
perl simmsParseHTML.pl 

# Merge to common table
perl merge.pl