// Tree structure definition
var TREE_NODES = [
	["Relative URLS", null, null,
		["In this file", "#sectionName", null],
		["Other file", "other.html", null],
		["Other folder", "../other_folder/file.html", null]
	],
	["GET Queries", null, null,
		["Same file", "?parameter1=value1", null],
		["Other file", "some_script.php?parameter2=value2", null]
	],
	["Absolute URLS", null, null,
		["Same server", "/some_folder/some_file.html", null],
		["Other server", "http://www.other-server.com/some_file.html", null]
	],
	["Other protocols", null, null,
		["HTTPS", "https://www.secure-server.com/", null],
		["FTP", "ftp://sunsite.unc.edu/", null],
		["E-mail", "mailto:somebody@somewhere.com", null]
	],
	["JavaScript", null, null,
		["Function call", "javascript:void(alert('Function has been called.'))", null]
	]
];