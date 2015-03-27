<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.calendar');
JHTML::_('behavior.calendar');
JHTML::_('behavior.tooltip');
jimport('joomla.html.pane');
JHTML::_('behavior.modal');
?>
<?php
echo "<script>var REQUEST_PATH = '" . JUri::root() . "administrator/'</script>";
?>
<script language="javascript" type="text/javascript">

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
        if ($("date_affectation")) {
            Calendar.setup(
                    {
                        inputField: "date_affectation", // ID of the input field
                        ifFormat: "%Y-%m-%d", // the date format
                        button: "date_affectation_click", // ID of the button
                        date: new Date()
                    }
            );
        }
    });

    function onChangeType(p_o) {
        var type = p_o[p_o.selectedIndex].value;
        var url = REQUEST_PATH + "index.php?option=com_steg&controller=hello&format=raw&task=gethellos";
        var jsonRequest = new Request.JSON({
            url: url,
            data: {
                type: type
            },
            onSuccess: function(data) {

                /* Remove all options from the select list */
                $('hello_id').empty();
                /* Insert the new ones from the array above */
                new Element('option')
                        .set('text', "Choisir un hello")
                        .set('value', 0)
                        .inject($('hello_id'));
                $each(data, function(value) {
                    if (value.id) {
                        new Element('option')
                                .set('text', value.nom)
                                .set('value', value.id)
                                .inject($('hello_id'));
                    }
                });
            }
        }).send();
        return 1;
    }
    function goTo(url) {
        window.location.href = url;
        return false;
    }

    function submitbutton(pressbutton) {
        if (pressbutton == 'save') {
        }
        submitform(pressbutton);
    }
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <?php echo $this->displayToolbar($this->btnToolbar); ?>
    <div class="current">
        <div class="col100" style="display:block">
            <fieldset class="adminform" >
                <legend><?php echo JText::_('Nouveau Hello'); ?></legend>
                <table class="admintable">
                    <tr>
                        <td width="200" align="right" class="key">
                            <label for="name">
                                Nom:
                            </label>
                        </td>
                        <td>
                            <input class="text_area" type="text" name="name" id="name" size="32" maxlength="250" value="<?php echo @$this->data->name; ?>" />
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
    </div>
    <input type="hidden" name="option" value="com_steg" />
    <input type="hidden" name="id" value="<?php echo $this->data->id; ?>" />
    <input type="hidden" name="task"  id="task" value="edit" />
    <input type="hidden" name="controller" value="hello" />
</form>