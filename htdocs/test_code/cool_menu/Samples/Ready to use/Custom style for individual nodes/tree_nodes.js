// Tree structure definition
var TREE_NODES = [
	["Plain nodes", null, null,
		["Node 1", null, null],
		["Node 2", null, null],
		["Node 3", null, null]
	],
	["Nodes with formatting tags", null, null,
		["&lt;u&gt;: <u>Node 1</u>", null, null],
		["&lt;b&gt;: <b>Node 2</b>", null, null],
		["&lt;i&gt;: <i>Node 3</i>", null, null]
	],
	["Nodes with inline styles", null, null,
		["<span style=\"color: red;\">Node 1</span> (custom color)", null, null],
		["<span style=\"background-color: silver;\">Node 2</span> (custom background color)", null, null],
		["<span style=\"font: bold 9pt times;\">Node 3</span> (custom font)", null, null]
	],
	["Nodes with CSS class references", null, null,
		["<span class=\"class1\">Node 1</span>", null, null],
		["<span class=\"class2\">Node 2</span>", null, null],
		["<span class=\"class3\">Node 3</span>", null, null]
	]
];