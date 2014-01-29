// Tree format definition
var TREE_FORMAT = [
	//  0. left position
	10,
	//  1. top position
	10,
	//  2. show buttons ("+" and "-" images)
	false,
	//  3. button images: collapsed state, expanded state, blank image
	["", "", "images/blank.gif"],
	//  4. size of buttons: width, height, indent amount for childless nodes
	[0, 0, 0],
	//  5. show icons ("folder" and "document")
	false,
	//  6. icon images: closed folder, opened folder, document
	["", "", ""],
	//  7. size of icons: width, height
	[0, 0],
	//  8. indent amount for each level of the tree
	[0, 16, 32, 48, 64, 80, 96, 112, 128],
	//  9. background color for the tree
	"",
	// 10. default CSS class for nodes
	"treeNode",
	// 11. individual CSS classes for levels of the tree
	[],
	// 12. "single branch" mode
	false,
	// 13. padding and spacing values for all nodes
	[0, 0]
];