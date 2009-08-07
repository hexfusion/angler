<?php /* Smarty version 2.6.10, created on 2008-01-18 09:28:04
         compiled from gallery:themes/PGtheme/templates/progressbar.tpl */ ?>
<div id="ProgressBar" class="gbBlock">
  <h3 id="progressTitle">
    &nbsp;
  </h3>

  <p id="progressDescription">
    &nbsp;
  </p>

  <table width="100%" cellspacing="0" cellpadding="0">
    <tr>
      <td id="progressDone" style="display: inline-block; width:0%">&nbsp;</td>
      <td id="progressToGo" style="display: inline-block; width:100%; border-left: none">&nbsp;</td>
    </tr>
  </table>

  <p id="progressTimeRemaining">
    &nbsp;
  </p>

  <p id="progressMemoryInfo" style="position: absolute; top: 0px; right: 15px">
    &nbsp;
  </p>

  <p id="progressErrorInfo" style="display: none">

  </p>

  <a id="progressContinueLink" style="display: none">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Continue..."), $this);?>

  </a>
</div>

<?php $this->_tag_stack[] = array('addToTrailer', array(), $this); $this->_reg_objects['g'][0]->addToTrailer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true); while ($_block_repeat) { ob_start();?>
<?php echo '
<script type="text/javascript">
  // <![CDATA[
  var saveToGoDisplay = document.getElementById(\'progressToGo\').style.display;
  function updateProgressBar(title, description, percentComplete, timeRemaining, memoryInfo) {
    document.getElementById(\'progressTitle\').innerHTML = title;
    document.getElementById(\'progressDescription\').innerHTML = description;

    var progressMade = Math.round(percentComplete * 100);
    var progressToGo = document.getElementById(\'progressToGo\');

    if (progressMade == 100) {
      progressToGo.style.display = \'none\'; 
    } else {
      progressToGo.style.display = saveToGoDisplay;
      progressToGo.style.width = (100 - progressMade) + "%";
    }

    document.getElementById(\'progressDone\').style.width = progressMade + "%";
    document.getElementById(\'progressTimeRemaining\').innerHTML = timeRemaining;
    document.getElementById(\'progressMemoryInfo\').innerHTML = memoryInfo;
  }

  function completeProgressBar(url) {
    var link = document.getElementById(\'progressContinueLink\');
    link.href = url;
    link.style.display = \'block\';
  }

  function errorProgressBar(html) {
    var errorInfo = document.getElementById(\'progressErrorInfo\');
    errorInfo.innerHTML = html;
    errorInfo.style.display = \'block\';
  }
  // ]]>
</script>
'; ?>

<?php $_obj_block_content = ob_get_contents(); ob_end_clean(); echo $this->_reg_objects['g'][0]->addToTrailer($this->_tag_stack[count($this->_tag_stack)-1][1], $_obj_block_content, $this, $_block_repeat=false);} array_pop($this->_tag_stack);?>
