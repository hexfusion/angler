<script>

// Tree structure definition s

var TREE_NODES = [
	["Accessories", "[area href=scan search=| fi=products/st=db/sf=prod_group/se=Accessories/op=rm/nu=0/ml=10/|]&cat=accessories", null,[if cgi cat eq "accessories"][then]{format:{expanded:true}},[/then][/if]
		["Floatants", "[area href=scan search=| fi=products/st=db/sf=category/se=Floatants/op=rm/nu=0/ml=10/|]&cat=accessories", null,[if cgi cat eq "accessories"][then]{format:{expanded:true}},[/then][/if]],
		["Fly Boxes", "[area href=scan search=| fi=products/st=db/sf=category/se=Boxes/op=rm/nu=0/ml=10/ |]&cat=accessories", null,[if cgi cat eq "accessories"][then]{format:{expanded:true}},[/then][/if]],
		["Indicators", "[area href=scan search=| fi=products/st=db se=Indicators/sf=category/op=rm/nu=0/ml=10/ |]&cat=accessories", null,],
		["Landing Nets", "[area href=scan search=| fi=products/st=db/sf=category/se=Nets/op=rm/nu=0/ml=10/ |]&cat=accessories", null,[if cgi cat eq "accessories"][then]{format:{expanded:true}},[/then][/if]],
		["Line", "[area href=scan search=| fi=products/st=db/sf=category/se=Lines/op=rm/nu=0/ml=10/ |]&cat=accessories", null,[if cgi cat eq "accessories"][then]{format:{expanded:true}},[/then][/if]],
		["Reel", "[area href=scan search=| fi=products/st=db/sf=category/se=Reels/op=rm/nu=0/ml=10/ |]&cat=accessories", null,[if cgi cat eq "accessories"][then]{format:{expanded:true}},[/then][/if]],
		["Split Shot", "[area href=scan search=| fi=products/st=db/se=Shot/sf=category/op=rm/nu=0/ml=10/|]&cat=accessories", null,[if cgi cat eq "accessories"][then]{format:{expanded:true}},[/then][/if]],
		["Sunglasses", "[area href=scan search=| fi=products/st=db/se=Sunglasses/sf=category/op=rm/nu=0/ml=10/ |]&cat=accessories", null,[if cgi cat eq "accessories"][then]{format:{expanded:true}},[/then][/if]],
		["Wading", "[area href=scan search=| fi=products/st=db/sf=category/se=Wading/op=rm/nu=0/ml=10/ |]&cat=accessories", null,[if cgi cat eq "accessories"][then]{format:{expanded:true}},[/then][/if]]
			],


	["Books & DVDs", "[area href=scan search=| fi=products/st=db/sf=prod_group/se=Books/op=rm/nu=0/ml=10/|]&cat=books_dvd", null,[if cgi cat eq "books_dvd"][then]{format:{expanded:true}},[/then][/if]
		["Books", "[area href=scan search=| fi=products/st=db/se=Books/op=rm/nu=0/ml=10/ |]&cat=books_dvd", null,[if cgi cat eq "books_dvd"][then]{format:{expanded:true}},[/then][/if]],
		["DVDs", "[area href=scan search=| fi=products/st=db/se=DVD/op=rm/nu=0/ml=10/ |]&cat=books_dvd", null,[if cgi cat eq "books_dvd"][then]{format:{expanded:true}},[/then][/if]]
			],

	["Boots", "[area href=scan search=| fi=products/st=db/sf=category/se=Boots/op=rm/nu=0/ml=10/|]&cat=boots", null,[if cgi cat eq "boots"][then]{format:{expanded:true}},[/then][/if]
		["Simms", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Simms/op=rm/nu=/sf=category/se=Boots/op=rm/nu=0/ml=10/ |]&cat=boots", null,[if cgi cat eq "boots"][then]{format:{expanded:true}},[/then][/if]],
		["Orvis", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Orvis/op=rm/nu=/sf=category/se=Boots/op=rm/nu=0/ml=10/ |]&cat=boots", null,[if cgi cat eq "boots"][then]{format:{expanded:true}},[/then][/if]]
			 ],
	
	["Clothing", "[area href=scan search=| fi=products/st=db/sf=prod_group/se=Clothing/op=rm/nu=0/ml=10/|]&cat=clothing", null,[if cgi cat eq "clothing"][then]{format:{expanded:true}},[/then][/if]
		["Outerwear", "[area href=scan search= | fi=products/st=db/sf=category/se=Outerwear/op=rm/nu=0/ml=10/ |]&cat=clothing", null,[if cgi cat eq "clothing"][then]{format:{expanded:true}},[/then][/if]],
		["Layering", "[area href=scan search= | fi=products/st=db/sf=category/se=Layering/op=rm/nu=0/ml=10/ |]&cat=clothing", null,[if cgi cat eq "clothing"][then]{format:{expanded:true}},[/then][/if]],
		["Sportswear", "[area href=scan search= | fi=products/st=db/sf=category/se=Sportswear/op=rm/nu=0/ml=10/ |]&cat=clothing", null,[if cgi cat eq "clothing"][then]{format:{expanded:true}},[/then][/if]],
		["Waders", "[area href=scan search= | fi=products/st=db/sf=category/se=Waders/op=rm/nu=0/ml=10/ |]&cat=clothing", null,[if cgi cat eq "clothing"][then]{format:{expanded:true}},[/then][/if]],
		["Boots", "[area href=scan search= | fi=products/st=db/sf=category/se=Boots/op=rm/nu=0/ml=10/ |]&cat=clothing", null,[if cgi cat eq "clothing"][then]{format:{expanded:true}},[/then][/if]],
		["Vests", "[area href=scan search= | fi=products/st=db/sf=category/se=Vests/op=rm/nu=0/ml=10/ |]&cat=clothing", null,[if cgi cat eq "clothing"][then]{format:{expanded:true}},[/then][/if]],
        ["Packs", "[area href=scan search= | fi=products/st=db/sf=category/se=Packs/op=rm/nu=0/ml=10/ |]&cat=clothing", null,[if cgi cat eq "clothing"][then]{format:{expanded:true}},[/then][/if]],
		["Gloves", "[area href=scan search= | fi=products/st=db/sf=category/se=Gloves/op=rm/nu=0/ml=10/ |]&cat=clothing", null,[if cgi cat eq "clothing"][then]{format:{expanded:true}},[/then][/if]],
		["Hats", "[area href=scan search= | fi=products/st=db/sf=category/se=Hats/op=rm/nu=0/ml=10/ |]&cat=clothing", null,[if cgi cat eq "clothing"][then]{format:{expanded:true}},[/then][/if]]
			],

	["Fly Lines", "[area href=scan search=| fi=products/st=db/sf=category/se=Lines/op=rm/nu=0/ml=10/|]&cat=lines", null,[if cgi cat eq "lines"][then]{format:{expanded:true}},[/then][/if]
		["Sci Angler", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Sci Angler/op=rm/nu=/sf=category/se=Lines/op=rm/nu=0/ml=10/ |]&cat=lines", null,[if cgi cat eq "lines"][then]{format:{expanded:true}},[/then][/if]],
		["Rio", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Rio/op=rm/nu=/sf=category/se=Lines/op=rm/nu=0/ml=10/ |]&cat=lines", null,[if cgi cat eq "lines"][then]{format:{expanded:true}},[/then][/if]],
		["Cortland", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Cortland/op=rm/nu=/sf=category/se=Lines/op=rm/nu=0/ml=10/ |]&cat=lines", null,[if cgi cat eq "lines"][then]{format:{expanded:true}},[/then][/if]],
		["Sage", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Sage/op=rm/nu=/sf=category/se=Lines/op=rm/nu=0/ml=10/ |]&cat=lines", null,[if cgi cat eq "lines"][then]{format:{expanded:true}},[/then][/if]]
			],
	
	["Luggage", "[area href=scan search=| fi=products/st=db/sf=category/se=Luggage/op=rm/nu=0/ml=10/|]&cat=luggage", null,[if cgi cat eq "luggage"][then]{format:{expanded:true}},[/then][/if]
		["Simms", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Simms/op=rm/nu=/sf=category/se=Luggage/op=rm/nu=0/ml=10/ |]&cat=luggage", null,[if cgi cat eq "luggage"][then]{format:{expanded:true}},[/then][/if]],
		["Orvis", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Orvis/op=rm/nu=/sf=category/se=Luggage/op=rm/nu=0/ml=10/ |]&cat=luggage", null,[if cgi cat eq "luggage"][then]{format:{expanded:true}},[/then][/if]],
		["Fish Pond", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Fish Pond/op=rm/nu=/sf=category/se=Luggage/op=rm/nu=0/ml=10/ |]&cat=luggage", null,[if cgi cat eq "luggage"][then]{format:{expanded:true}},[/then][/if]],
		["Sage", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Sage/op=rm/nu=/sf=category/se=Luggage/op=rm/nu=0/ml=10/ |]&cat=luggage", null,[if cgi cat eq "luggage"][then]{format:{expanded:true}},[/then][/if]]
			],
	
	["Packs", "[area href=scan search=| fi=products/st=db/sf=category/se=Packs/op=rm/nu=0/ml=10/|]&cat=packs", null,[if cgi cat eq "packs"][then]{format:{expanded:true}},[/then][/if]
		["Simms", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Simms/op=rm/nu=/sf=category/se=Packs/op=rm/nu=0/ml=10/ |]&cat=packs", null,[if cgi cat eq "packs"][then]{format:{expanded:true}},[/then][/if]],
		["Orvis", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Orvis/op=rm/nu=/sf=category/se=Packs/op=rm/nu=0/ml=10/ |]&cat=packs", null,[if cgi cat eq "packs"][then]{format:{expanded:true}},[/then][/if]],
		["Fish Pond", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Fish Pond/op=rm/nu=/sf=category/se=Packs/op=rm/nu=0/ml=10/ |]&cat=packs", null,[if cgi cat eq "packs"][then]{format:{expanded:true}},[/then][/if]],
		["Sage", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Sage/op=rm/nu=/sf=category/se=Packs/op=rm/nu=0/ml=10/ |]&cat=packs", null,[if cgi cat eq "packs"][then]{format:{expanded:true}},[/then][/if]]
			],
	
	["Reels", "[area href=scan search=| fi=products/st=db/sf=category/se=Reels/op=rm/nu=0/ml=10/|]&cat=reels", null,[if cgi cat eq "reels"][then]{format:{expanded:true}},[/then][/if]
		["Hardy", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Hardy/op=rm/nu=/sf=category/se=Reels/op=rm/nu=0/ml=10/ |]&cat=reels", null,[if cgi cat eq "reels"][then]{format:{expanded:true}},[/then][/if]],
		["Bauer", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Bauer/op=rm/nu=/sf=category/se=Reels/op=rm/nu=0/ml=10/ |]&cat=reels", null,[if cgi cat eq "reels"][then]{format:{expanded:true}},[/then][/if]],
		["Orvis", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Orvis/op=rm/nu=/sf=category/se=Reels/op=rm/nu=0/ml=10/ |]&cat=reels", null,[if cgi cat eq "reels"][then]{format:{expanded:true}},[/then][/if]],
		["Ross", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Ross/op=rm/nu=/sf=category/se=Reels/op=rm/nu=0/ml=10/ |]&cat=reels", null,[if cgi cat eq "reels"][then]{format:{expanded:true}},[/then][/if]],
		["Sage", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Sage/op=rm/nu=/sf=category/se=Reels/op=rm/nu=0/ml=10/ |]&cat=reels", null,[if cgi cat eq "reels"][then]{format:{expanded:true}},[/then][/if]],
		["Tibor", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Tibor/op=rm/nu=/sf=category/se=Reels/op=rm/nu=0/ml=10/ |]&cat=reels", null,[if cgi cat eq "reels"][then]{format:{expanded:true}},[/then][/if]]
			],
	
	["Rods", "[area href=scan search=| fi=products/st=db/sf=category/se=Rods/op=rm/nu=0/ml=10/|]&cat=rods", null,[if cgi cat eq "rods"][then]{format:{expanded:true}},[/then][/if]
		["Sage", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Sage/op=rm/nu=/sf=category/se=Rods/op=rm/nu=0/ml=10/ |]&cat=rods", null,[if cgi cat eq "rods"][then]{format:{expanded:true}},[/then][/if]],
		["Winston", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Winston/op=rm/nu=/sf=category/se=Rods/op=rm/nu=0/ml=10/ |]&cat=rods", null,[if cgi cat eq "rods"][then]{format:{expanded:true}},[/then][/if]],
		["Orvis", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Orvis/op=rm/nu=/sf=category/se=Rods/op=rm/nu=0/ml=10/ |]&cat=rods", null,[if cgi cat eq "rods"][then]{format:{expanded:true}},[/then][/if]],
		["Hardy", "[area href=scan search=|fi=products/st=db/co=1/sf=manufacturer/se=Hardy/op=rm/nu=/sf=category/se=Rods/op=rm/nu=0/ml=10/ |]&cat=rods", null,[if cgi cat eq "rods"][then]{format:{expanded:true}},[/then][/if]]
		
	]
];

</script>
