//------- Common -------
var texpanded = 0;
var tlevelDX = 20;
var texpandItemClick = 0;
var ttoggleMode = 0;
var tcloseExpanded = 0;
var tcloseExpandedXP = 0;
var titemTarget = "_self";
var titemCursor = "pointer";
var tblankImage = "images/blank.gif";
var key = "";

//------- Menu -------
var tmenuWidth = "280px";
var tmenuHeight = "auto";
var tmenuBorderWidth = 0;
var tmenuBorderStyle = "solid";
var tmenuBackImage = "";

//------- Menu Positioning -------
var tabsolute = 1;
var tleft = 10;
var ttop = 10;
var tfloatable = 0;
var tfloatIterations = 6;
var tmoveable = 0;
var tmoveImage = "images/movepic.gif";
var tmoveImageHeight = 12;

//------- Font -------
var tfontStyle = "normal 8pt Tahoma";
var tfontColor = ["#215DC6","#428EFF"];
var tfontDecoration = ["none","underline"];
var tpressedFontColor = "#AA0000";
var tfontColorDisabled = "#AAAAAA";

//------- Items -------
var titemAlign = "left";
var titemHeight = 22;
var titemBackImage = ["",""];

//------- Colors -------
var tmenuBackColor = "";
var tmenuBorderColor = "#FFFFFF";
var titemBackColor = ["#D6DFF7","#D6DFF7"];

//------- Icons -------
var ticonWidth = 16;
var ticonHeight = 16;
var ticonAlign = "left";

//------- Buttons -------
var texpandBtn = ["images/expandbtn2.gif","images/expandbtn2.gif","images/collapsebtn2.gif"];
var texpandBtnW = 9;
var texpandBtnH = 9;
var texpandBtnAlign = "left";

//------- Lines -------
var tpoints = 0;
var tpointsImage = "";
var tpointsVImage = "";
var tpointsCImage = "";

//------- State saving -------
var tsaveState = 0;

//------- XP-Style -------
var tXPStyle = 1;
var tXPIterations = 8;
var tXPFilter = 1;
var tXPTitleLeft = "images/xptitleleft.gif";
var tXPTitleLeftWidth = 4;
var tXPExpandBtn = ["images/xpexpand1.gif","images/xpexpand2.gif","images/xpcollapse1.gif","images/xpcollapse2.gif"];
var tXPBtnWidth = 25;
var tXPBtnHeight = 25;
var tXPIconWidth = 30;
var tXPIconHeight = 32;
var tXPTitleBackColor = "#265BCC";
var tXPTitleTopBackColor = "";
var tXPTitleBackImg = "images/xptitle.gif";


var tmenuItems = [

    ["+DHTML Menus","", "images/xpicon1.gif", , , "DHTML Menus", , , , , , ],
        ["|DHTML Menu","http://dhtml-menu.com", "images/icon1.gif", "images/icon1o.gif", "images/icon1o.gif", "Information", "_self", , , , , ],
        ["|+DHTML Tree Menu","", "images/icon1.gif", "images/icon1o.gif", "images/icon1o.gif", "Support", "_self", , , , , ],
            ["||2 styles: standard & XP","", , , , , , , , , , ],
            ["||Individual Item & Submenu Styles","", , , , , , , , , , ],
            ["||Floatable & Movable Menu","", , , , , , , , , , ],
            ["||JavaScript API","", , , , , , , , , , ],
            ["||And even more!","", , , , , , , , , , ],
        ["|DHTML Tabs","http://dhtml-menu.com", "images/icon1.gif", "images/icon1o.gif", "images/icon1o.gif", "Help", "_self", , , , , ],
        ["|DHTML Tab Menu","http://dhtml-menu.com", "images/icon1.gif", "images/icon1o.gif", "images/icon1o.gif", "Support", "_self", , , , , ],
    ["Apycom Products","", , , , "Download software", , , , , , ],
        ["|Java menus","", "images/icon1.gif", "images/icon1o.gif", "images/icon1o.gif", , "_self", , , , , ],
            ["||Drop Down Menus","http://www.apycom.com/xp-drop-down-menu/", , , , , "_self", , , , , ],
            ["||Animated Menus","http://www.apycom.com/animated-buttons/", , , , , "_self", , , , , ],
            ["||Website Buttons","http://www.apycom.com/website-buttons/", , , , , "_self", , , , , ],
            ["||Navigation Tabs","http://www.apycom.com/navigation-bar-tabs/", , , , , "_self", , , , , ],
            ["||Live Examples","http://www.apycom.com/indexex.html", , , , , "_self", , , , , ],
            ["||Sreenshots","http://www.apycom.com/shots/xpdd.html", , , , , "_self", , , , , ],
            ["||Purchase","http://www.apycom.com/order.html", , , , , "_self", , , , , ],
            ["||Download","http://www.apycom.com/download.html", , , , , "_self", , , , , ],
        ["|Web buttons","http://www.xp-web-buttons.com", "images/icon1.gif", "images/icon1o.gif", "images/icon1o.gif", "_self", "_self", , , , , ],
        ["|DBF Viewer/Editor","http://www.dbfview.com", "images/icon1.gif", "images/icon1o.gif", "images/icon1o.gif", "_self", "_self", , , , , ],
];

dtree_init();
