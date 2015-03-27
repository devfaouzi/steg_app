<?php

/**
 * Steg Model for Steg World Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:modules/
 * @license    GNU/GPL
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * Steg Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class StegModelHello extends JModel {

    /**
     * Gets the greeting
     * @return string The greeting to be displayed to the user
     */
    function getGreeting() {
//        return 'Steg, World!';
        $db = & JFactory::getDBO();

        $query = 'SELECT greeting FROM #__steg_hello';
        $db->setQuery($query);
        $greeting = $db->loadResult();

        return $greeting;
    }

    function __construct() {
        parent::__construct();
        $array = JRequest::getVar('cid', 0, '', 'array');
        $this->setId((int) $array[0]);
    }

    function setId($id) {
        $this->_id = $id;
        $this->_data = null;
    }

    function _buildQuery() {
        $query = ' SELECT * FROM #__steg_hello ' . '  WHERE id = ' . $this->_id;
        return $query;
    }

    function &getData() {
        // Load the data
        if (empty($this->_data)) {
            $query = $this->_buildQuery();
            $this->_db->setQuery($query);
            $this->_data = $this->_db->loadObject();
        }
        if (!$this->_data) {
            $data = new stdClass();
            $data->id = 0;
            $this->_data = $data;
        }
        return $this->_data;
    }

    function store($data = null) {
        $row = &$this->getTable();
        if ($data == null) {
            $data = JRequest::get('post');
        }
        // Bind the form fields to the publisher table
        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        // Make sure the publisher record is valid
        if (!$row->check($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        // Store the web link table to the database
        if (!$row->store($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        $this->_id = $row->id;
        return true;
    }

    function delete() {
        $cids = JRequest::getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable();
        if (count($cids)) {
            foreach ($cids as $cid) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            }
        }
        return true;
    }

    function getId() {
        return $this->_id;
    }

}
