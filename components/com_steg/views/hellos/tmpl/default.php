<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.calendar');
//require_once JPATH_ADMINISTRATOR . '/components/com_steg/views/bootstrap.php';
//require_once JPATH_ADMINISTRATOR . '/components/com_steg/views/menu.php';
?>
<script language="javascript" type="text/javascript">
    function resetFilter(f) {
        $('matricule').value = '';
        $('filterId').value = '';
        $('date_circulation').value = '';
        f.submit();
        return false;
    }
</script>

<script language="javascript" type="text/javascript">

    window.addEvent('domready', function() {
        //caledndar
        if ($("date_circulation")) {
            Calendar.setup(
                    {
                        inputField: "date_circulation", // ID of the input field
                        ifFormat: "%Y-%m-%d", // the date format
                        button: "date_circulation_click", // ID of the button
                        date: new Date()
                    }
            );
        }
    });
</script>
<form action="index.php" method="post" name="adminForm">
    <?php echo $this->displayToolbar($this->btnToolbar); ?>
    <fieldset class="adminform " >
        <legend><?php echo JText::_('Filter'); ?></legend>
        <table class="">
            <tr>
                <td width="20" align="right" class="key">
                    <label for="matricule">
                        <?php echo JText::_('Nom'); ?>:
                    </label>
                </td>
                <td>
                    <input type="text" name="name" id="name" value="<?php echo JRequest::getVar("name", ""); ?>" class="text_area" />
                </td>

                <td><input type="button" onclick="this.form.submit();" value="<?php echo JText::_('Filtrer'); ?>"/></td>
                <td><input type="button" onclick=" resetFilter(this.form);
                        return false" value="<?php echo JText::_('Inisialiser'); ?>" /></td>
            </tr>
        </table>

    </fieldset>

    <div id="editcell">
        <table class="adminlist">
            <thead>
                <tr>
                    <th width="5">
                        <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
                    </th>
                    <th width="5">
                        <?php echo JText::_('ID'); ?>
                    </th>
                    <th width="200">
                        <?php echo JText::_('Name'); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $k = 0;
                for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                    $row = & $this->items[$i];
                    $checked = JHTML::_('grid.id', $i, $row->id);
                    $link = JRoute::_('index.php?option=com_steg&controller=hello&task=edit&cid[]=' . $row->id);
                    ?>
                    <tr class="<?php echo "row$k"; ?>">
                        <td>
                            <?php echo $checked; ?>
                        </td>
                        <td>
                            <a href="<?php echo $link; ?>"><?php echo $row->id; ?></a>
                        </td>
                        <td>
                            <a href="<?php echo $link; ?>"><?php echo $row->name; ?></a>
                        </td>
                    </tr>
                    <?php
                    $k = 1 - $k;
                }
                ?>
            </tbody>
            <tfoot>
            <td colspan="9"><?php echo $this->pagination->getListFooter(); ?></td>
            </tfoot>
        </table>
    </div>
    <input type="hidden" name="option" value="com_steg" />
    <input type="hidden" name="task"  id="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="controller" value="hello" />
    <input type="hidden" name="limistart" value="<?php echo $this->limitstart; ?>" />
</form>