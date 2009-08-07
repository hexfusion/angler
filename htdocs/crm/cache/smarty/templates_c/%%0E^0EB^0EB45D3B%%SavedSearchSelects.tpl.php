<?php /* Smarty version 2.6.11, created on 2007-03-18 19:16:29
         compiled from modules/SavedSearch/SavedSearchSelects.tpl */ ?>


<?php if ($this->_tpl_vars['SAVED_SEARCHES_OPTIONS'] != null): ?>
<select style="width: 110px" name='side_saved_search_select' onChange='SUGAR.savedViews.shortcut_select(this, "<?php echo $this->_tpl_vars['SEARCH_MODULE']; ?>
");'>
	<?php echo $this->_tpl_vars['SAVED_SEARCHES_OPTIONS']; ?>

</select>
<?php endif; ?>