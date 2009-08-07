<?
include ("../body.php");
include ("../config.php");
?>
<TABLE cellSpacing=1 cellPadding=3 width="100%" align=center border=0>
  <TBODY>
    <TR>
      <TH class=thHead>BBCode Guide</TH>
    </TR>
    <TR>
      <TD><SPAN><B>Introduction</B></SPAN><BR>
          <SPAN><A 
href="#0">What is BBCode?</A></SPAN><BR>
          <BR>
          <SPAN 
class=gen><B>Text Formatting</B></SPAN><BR>
          <SPAN><A 
href="#1">How to create bold, italic and underlined text</A></SPAN><BR>
          <SPAN 
class=gen><A href="#2">How to change the text colour or size</A></SPAN><BR>
        <SPAN><A href="#3">Can I combine formatting tags?</A></SPAN><BR>
        <BR>
        <SPAN><B>Quoting and outputting fixed-width text</B></SPAN><BR>
        <SPAN><A 
href="#4">Quoting text in replies</A></SPAN><BR>
        <SPAN><A 
href="#5">Outputting code or fixed width data</A></SPAN><BR>
        <BR>
        <SPAN><B>Generating lists</B></SPAN><BR>
        <SPAN><A href="#6">Creating an Un-ordered list</A></SPAN><BR>        
        <BR>
        <SPAN><B>Creating Links</B></SPAN><BR>
        <SPAN><A href="#8">Linking to another site</A></SPAN><BR>
        <BR>
        <SPAN><B>Showing images in reviews </B></SPAN><BR>
        <SPAN><A href="#9">Adding an image to a review </A></SPAN></TD>
    </TR>
    <TR>
      <TD height=28>&nbsp;</TD>
    </TR>
  </TBODY>
</TABLE>
<BR clear=all>
<TABLE cellSpacing=1 cellPadding=3 width="100%" align=center 
border=0>
  <TBODY>
    <TR>
      <TD align=middle height=28><SPAN 
class=cattitle>Introduction</SPAN></TD>
    </TR>
    <TR>
      <TD vAlign=top align=left><SPAN><A name=0></A><B>What is BBCode?</B></SPAN><BR>
        <SPAN>BBCode is a special implementation of HTML. Whether you can actually use BBCode in your reviews is determined by the administrator. BBCode itself is similar in style to HTML: tags are enclosed in square braces [ and ] rather than &lt; and &gt; and it offers greater control over what and how something is displayed.  Even with this you may find the following guide useful.<BR>
        <A 
href="#Top">Back to top</A></SPAN></TD>
    </TR>

  </TBODY>
</TABLE>
<BR clear=all>
<TABLE cellSpacing=1 cellPadding=3 width="100%" align=center 
border=0>
  <TBODY>
    <TR>
      <TD align=middle height=28><SPAN class=cattitle>Text Formatting</SPAN></TD>
    </TR>
    <TR>
      <TD vAlign=top align=left><SPAN><A name=1></A><B>How to create bold, italic and underlined text</B></SPAN><BR>
        <SPAN 
class=postbody>BBCode includes tags to allow you to quickly change the basic style of your text. This is achieved in the following ways:
        <UL>
          <LI>To make a piece of text bold enclose it in <B>[b][/b]</B>, eg. <BR>
              <BR>
              <B>[b]</B>Hello<B>[/b]</B><BR>
              <BR>
              will become <B>Hello</B>
          <LI>For underlining use <B>[u][/u]</B>, for example:<BR>
              <BR>
              <B>[u]</B>Good Morning<B>[/u]</B><BR>
            <BR>
            becomes <U>Good Morning</U>
          <LI>To italicise text use <B>[i][/i]</B>, eg.<BR>
              <BR>
              This is <B>[i]</B>Great!<B>[/i]</B><BR>
              <BR>
              would give This is <I>Great!</I></LI>
        </UL>
        <BR>
        <A href="#Top">Back to top</A></SPAN></TD>
    </TR>
    <TR>
      <TD height=1><IMG height=1 alt="" 
src="templates/subSilver/images/spacer.gif" width=1></TD>
    </TR>
    <TR>
      <TD class=row2 vAlign=top align=left><SPAN><A name=2></A><B>How to change the text colour or size</B></SPAN><BR>
        <SPAN>To alter the color or size of your text the following tags can be used. Keep in mind that how the output appears will depend on the viewers browser and system:
        <UL>
          <LI>Changing the colour of text is achieved by wrapping it in <B>[color=][/color]</B>. You can specify either a recognized colour name (eg. red, blue, yellow, etc.) or the hexadecimal triplet alternative, eg. #FFFFFF, #000000. For example, to create red text you could use:<BR>
            <BR>
            <B>[color=&quot;red&quot;]</B>Hello!<B>[/color]</B><BR>
            <BR>
            or<BR>
            <BR>
            <B>[color=&quot;#FF0000&quot;]</B>Hello!<B>[/color]</B><BR>
            <BR>
            will both output <SPAN style="COLOR: red">Hello!</SPAN>
          <LI>Changing the text size is achieved in a similar way using <B>[size=][/size]</B>. This tag is dependent on the template you are using but the recommended format is a numerical value representing the text size in pixels, starting at 1 (so tiny you will not see it) through to 29 (very large). For example:<BR>
            <BR>
            <B>[size=&quot;9&quot;]</B>SMALL<B>[/size]</B><BR>
            <BR>
            will generally be <SPAN 
style="FONT-SIZE: 9px">SMALL</SPAN><BR>
            <BR>
            whereas:<BR>
            <BR>
            <B>[size=&quot;24&quot;]</B>HUGE!<B>[/size]</B><BR>
            <BR>
            will be <SPAN style="FONT-SIZE: 24px">HUGE!</SPAN></LI>
        </UL>
        <BR>
        <A 
href="#Top">Back to top</A></SPAN></TD>
    </TR>
    <TR>
      <TD height=1><IMG height=1 alt="" 
src="templates/subSilver/images/spacer.gif" width=1></TD>
    </TR>
    <TR>
      <TD vAlign=top align=left><SPAN><A name=3></A><B>Can I combine formatting tags?</B></SPAN><BR>
        <SPAN>Yes, of course you can; for example to get someones attention you may write:<BR>
        <BR>
        <B>[size=&quot;18&quot;][color=&quot;red&quot;][b]</B>BIG RED LETTERS!<B>[/b][/color][/size]</B><BR>
        <BR>
        this would output <SPAN 
style="FONT-SIZE: 18px; COLOR: red"><B>BIG RED LETTERS !</B></SPAN><BR>
        <BR>
        We don't recommend you output lots of text that looks like this, though! Remember that it is up to you, the reviewer, to ensure that tags are closed correctly. For example, the following is incorrect:<BR>
        <BR>
        <B>[b][u]</B>This is wrong<B>[/b][/u]</B><BR>
        <A href="#Top">Back to top</A></SPAN></TD>
    </TR>

  </TBODY>
</TABLE>
<BR clear=all>
<TABLE cellSpacing=1 cellPadding=3 width="100%" align=center 
border=0>
  <TBODY>
    <TR>
      <TD align=middle height=28><SPAN class=cattitle>Quoting and outputting fixed-width text</SPAN></TD>
    </TR>
    <TR>
      <TD vAlign=top align=left><SPAN><A 
name=4></A><B>Quoting text in reviews </B></SPAN><BR>
          <SPAN>
          <UL>
            <LI>Use [quote][/quote] to specify a quote without a reference. <BR>
            <BR>
            [quote]This is the text I am quoting.[/quote]<BR>
            <br />
            will output the following: <br />
              <table width=100% bgcolor=lightgray>
                <tr>
                     <td bgcolor=white>Quote:  This is the text I am quoting.</td>
                </tr>
              </table>
          </UL>
          <BR>
        <A 
href="#Top">Back to top</A></SPAN></TD>
    </TR>
    <TR>
      <TD height=1><IMG height=1 alt="" 
src="templates/subSilver/images/spacer.gif" width=1></TD>
    </TR>
    <TR>
      <TD class=row2 vAlign=top align=left><SPAN><A 
name=5></A><B>Outputting code or fixed width data</B></SPAN><BR>
          <SPAN 
class=postbody>If you want to output a piece of code, you should enclose the text in <B>[code][/code]</B> tags, eg.<BR>
        <BR>
        <B>[code]</B>echo "This is some code";<B>[/code]</B><BR>
        <BR>
        <BR>
        <A href="#Top">Back to top</A></SPAN></TD>
    </TR>

  </TBODY>
</TABLE>
<BR clear=all>
<TABLE cellSpacing=1 cellPadding=3 width="100%" align=center 
border=0>
  <TBODY>
    <TR>
      <TD align=middle height=28><SPAN class=cattitle>Generating lists</SPAN></TD>
    </TR>
    <TR>
      <TD vAlign=top align=left><SPAN><A 
name=6></A><B>Creating an Un-ordered list</B></SPAN><BR>
          <SPAN 
class=postbody>An unordered list ouputs each item in your list sequentially one after the other indenting each with a bullet character. To create an unordered list you use <B>[list][/list]</B> and define each item within the list using <B>[*]</B>. For example, to list your favorite colors you could use:<BR>
        <BR>
        <B>[list]</B><BR>
        <B>[*]</B>Red<BR>
        <B>[*]</B>Blue<BR>
        <B>[*]</B>Yellow<BR>
        <B>[/list]</B><BR>
        <BR>
        This would generate the following list:
        <UL>
          <LI>Red
          <LI>Blue
          <LI>Yellow</LI>
        </UL>
        <BR>
        <A href="#Top">Back to top</A></SPAN></TD>
    </TR>
  </TBODY>
</TABLE>
<BR clear=all>
<TABLE cellSpacing=1 cellPadding=3 width="100%" align=center 
border=0>
  <TBODY>
    <TR>
      <TD align=middle height=28><SPAN class=cattitle>Creating Links</SPAN></TD>
    </TR>
    <TR>
      <TD vAlign=top align=left><SPAN><A 
name=8></A><B>Linking to another site</B></SPAN><BR>
          <SPAN>BBCode supports a creating URIs, Uniform Resource Indicators better known as URLs.
          <UL>
            <LI>Use the <B>[url=&quot;&quot;][/url]</B> tag; whatever you type after the = sign will cause the contents of that tag to act as a URL. For example, to link to Review-Script.com you could use:<BR>
            <BR>
            <B>[url=&quot;http://www.review-script.com/&quot;]</B>Get Five Star Review!<B>[/url]</B><BR>
            <BR>
            This would generate the following link, <A 
href="http://www.review-script.com/" target=_blank>Get Five Star Review!</A> <br />
            <br />            
            <LI>The same thing applies equally to email addresses; you can specify an address explicitly, like:<BR>
            <BR>
            <B>[email]</B>&quot;no.one@domain.com<B>&quot;[/email]</B><BR>
            <BR>
            which will output <A href="emailto:no.one@domain.com">no.one@domain.com</A> </LI>
          </UL>
          As with all the BBCode tags you can wrap URLs around any of the other tags such as <B>[img][/img]</B> (see next entry), <B>[b][/b]</B>, etc. As with the formatting tags it is up to you to ensure the correct open and close order is following.        <BR>
        <BR>
        <A 
href="#Top">Back to top</A></SPAN></TD>
    </TR>

  </TBODY>
</TABLE>
<BR clear=all>
<TABLE cellSpacing=1 cellPadding=3 width="100%" align=center 
border=0>
  <TBODY>
    <TR>
      <TD align=middle height=28><SPAN class=cattitle>Showing images in posts</SPAN></TD>
    </TR>
    <TR>
      <TD vAlign=top align=left><SPAN><A 
name=9></A><B>Adding an image to a review </B></SPAN><BR>
          <SPAN>BBCode incorporates a tag for including images in your reviews. Two very important things to remember when using this tag are: many users do not appreciate lots of images being shown in reviews and second, the image you display must already be available on the Internet (it cannot exist only on your computer, for example, unless you run a webserver!). There is currently no way of storing images locally with Five Star Review Script. To display an image, you must surround the URL pointing to the image with <B>[img][/img]</B> tags. For example:<BR>
        <BR>
        <B>[img]</B>http://www.review-script.com/images/80x60.gif<B>[/img]</B><BR>
        <BR>
        As noted in the URL section above you can wrap an image in a <B>[url][/url]</B> tag if you wish, eg.<BR>
        <BR>
        <B>[url=<B>&quot;http://www.review-script.com/&quot;</B>][img]</B><B>http://www.review-script.com/images/80x60.gif[/img][/url]</B><BR>
        <BR>
        would generate:<BR>
        <BR>
        <A href="http://www.review-script.com/" target=_blank><IMG alt="" 
src="http://www.review-script.com/images/80x60.gif" border=0></A><BR>
        <BR>
        <A 
href="#Top">Back to top</A></SPAN></TD>
    </TR>

  </TBODY>
</TABLE>
<BR clear=all>
<?
BodyFooter();
exit;
?>
