<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.calendar');
JHTML::_('behavior.tooltip');
jimport('joomla.html.pane');
JHTML::_('behavior.modal');
JHTMLBehavior::formvalidation();
?>

<script language="javascript" type="text/javascript">
    function onBtnClick(presbutton) {
        $('task').value = presbutton;
        submitform(presbutton);
    }
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <?php
    echo $this->displayToolbar($this->btnToolbar);
    ?>
    <div class="current">
        <div class="col100" style="display:block">
            <fieldset class="adminform" >
                <legend><?php echo JText::_('Fiche'); ?></legend>
                <table class="admintable">
                    <tr>
                        <td width="100" align="right" class="key">
                            <label for="greeting">
                                greeting:
                            </label>
                        </td>
                        <td>
                            <input class="text_area" type="text" name="greeting" id="greeting" size="32" maxlength="250" value="<?php echo @$this->data->greeting; ?>" />
                        </td>
                    </tr>
                </table>
            </fieldset>

        </div>
    </div>
    <input type="hidden" name="option" value="com_hello" />
    <input type="hidden" name="id" value="<?php echo $this->data->id; ?>" />
    <input type="hidden" name="task" id="task" value="edit" />
    <input type="hidden" name="controller" value="hello" />
</form>