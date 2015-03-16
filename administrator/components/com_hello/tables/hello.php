
<?php

defined('_JEXEC') or die('Restricted access');

class TableHello extends JTable {

    /** @var int Primary key */
    var $id = 0;
    var $greeting = "";

    function __construct(& $db) {
        parent::__construct('#__hello', 'id', $db);
    }

}

?>
