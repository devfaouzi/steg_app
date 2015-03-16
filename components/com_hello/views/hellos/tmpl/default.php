<?php
defined('_JEXEC') or die('Restricted access');
?>
<script language="javascript" type="text/javascript">
    function resetFilter(f) {
        $('nom').value = '';
        $('prenom').value = '';
        f.submit();
        return false;
    }
</script>
<form action="index.php" method="post" name="adminForm">
    <?php
    echo $this->displayToolbar($this->btnToolbar);
    ?>

    <fieldset class="adminform " >
        <legend><?php echo JText::_('Filter'); ?></legend>
        <table class="">

            <tr>
                <td width="20" align="right" class="key">
                    <label for="Nom">
                        <?php echo JText::_('Nom'); ?>:
                    </label>
                </td>
                <td>
                    <input type="text" name="nom" id="nom" value="<?php echo JRequest::getVar("nom", ""); ?>" class="text_area" />
                </td>
            </tr>
            <tr>
                <td width="20" align="right" class="key">
                    <label for="prenom">
                        <?php echo JText::_('prenom'); ?>:
                    </label>
                </td>
                <td>
                    <input type="text" name="prenom" id="prenom" value="<?php echo JRequest::getVar("prenom", ""); ?>" class="text_area" />
                </td>
            </tr>
        </table>
        <br />
        <input type="button" onclick="this.form.submit();" value="<?php echo JText::_('Filtrer'); ?>"/>
        <input type="button" onclick=" resetFilter(this.form);
                return false" value="<?php echo JText::_('Inisialiser'); ?>" />
        <br/>
    </fieldset>

    <div id="editcell">
        <table class="adminlist">
            <thead>
                <tr>
                    <th width="5">
                        <?php echo JText::_('ID'); ?>
                    </th>

                    <th width="5">
                        <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
                    </th>

                    <th width="200">
                        <?php echo JText::_('Greeting'); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $k = 0;
                for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                    $row = & $this->items[$i];
                    $checked = JHTML::_('grid.id', $i, $row->id);
                    $link = JRoute::_('index.php?option=com_gestauto&controller=personnel&task=edit&cid[]=' . $row->id);
                    ?>
                    <tr class="<?php echo "row$k"; ?>">
                        <td>
                            <a href="<?php echo $link; ?>"><?php echo $row->id; ?></a>
                        </td>
                        <td>
                            <?php echo $checked; ?>
                        </td>
                        <td> 
                            <a href="<?php echo $link; ?>"><?php echo $row->greeting; ?></a>
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
    <input type="hidden" name="option" value="com_gestauto" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="controller" value="personnel" />
    <input type="hidden" name="limistart" value="<?php echo $this->limitstart; ?>" />
</form>