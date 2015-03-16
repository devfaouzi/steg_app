<?php

/**
 * Hello Model for Hello World Component
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
 * Hello Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class HelloModelHello extends JModel {

    /**
     * Gets the greeting
     * @return string The greeting to be displayed to the user
     */
    function getGreeting() {
//        return 'Hello, World!';
        $db = & JFactory::getDBO();

        $query = 'SELECT greeting FROM #__hello';
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
        $query = ' SELECT * FROM #__gestauto_personnel ' . '  WHERE id = ' . $this->_id;
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

    function getPersonnelIdByMail($mail) {

        $query = "SELECT * FROM #__gestauto_personnel  WHERE contactEmail = '" . $mail . "' ";
        $this->_db->setQuery($query);
        $this->_data = $this->_db->loadObject();
        return $this->_data;
    }

    function getPersonnelIdByUserId($userid) {

        $query = "SELECT * FROM #__gestauto_personnel  WHERE personnelUserId = " . $userid;
        $this->_db->setQuery($query);
        $this->_data = $this->_db->loadObject();
        return $this->_data;
    }

    function getPersonnelVoiture($userid) {

        $query = "SELECT * FROM #__gestauto_personnel  WHERE personnelUserId = " . $userid;
        $this->_db->setQuery($query);
        $this->_data = $this->_db->loadObject();
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

    //params $checkAdmin == true check if user is admin
    //return all PartnersData if Admin
    //           partnersData of a personnel
    function getPartners($filter2 = null, $checkAdmin = false) {
        if (!isset($filter2)) {
            $filter2 = new stdClass();
        }
        $partnersModel = new GestautoModelPartners();
        $userModel = new GestautoModelUser();
        if ($checkAdmin && $userModel->is_admin()) {
            return $partnersModel->getData(new stdClass());
        }
        $filter1 = new stdClass();
        if (isset($this->_id) && !empty($this->_id)) {
            $filter1->aid = $this->_id;
        }
        //if personnel not set get the log in personnel
        else {
            $user = &JFactory::getuser();
            $personnelModel = new GestautoModelPersonnel();
            $personnel = $personnelModel->getPersonnelIdByUserId($user->id);
            if (!$userModel->is_admin())
                $filter1->aid = @$personnel->id;
        }
        $personnelpartnersModel = new GestautoModelPersonnelPartners();
        $data1 = $personnelpartnersModel->getData($filter1);

        $partner_ids = array();

        if (count($data1)) {
            foreach ($data1 as $obj) {
                array_push($partner_ids, $obj->pid);
            }
        }

        $filter2->InIds = $partner_ids;
        return $partnersModel->getData($filter2);
    }

    function getCampaigns($filter2 = null, $checkAdmin = false, $filter = null) {
        // get partner personnel
        $partnersData = $this->getPartners($filter2 = null, $checkAdmin = false);

        $campaignsModel = new GestautoModelCampaigns();
        $partner_ids = array();
        if (count($partnersData)) {
            foreach ($partnersData as $obj) {
                array_push($partner_ids, $obj->id);
            }
        }

        //get campaign of already partner
        if (!isset($filter)) {
            $filter = new stdClass();
        }
        $filter->InIds = $partner_ids;
        return $campaignsModel->getData($filter, "", "", 0, 0);
    }

    function getSupports($filter2 = null, $checkAdmin = false) {
        //get partner personnel
        $campaignsData = $this->getCampaigns();
        $suppportsModel = new GestautoModelSupports();
        $campaigns_ids = array();
        if (count($campaignsData)) {
            foreach ($campaignsData as $obj) {
                array_push($campaigns_ids, $obj->id);
            }
        }
        //get supports of already campaigns
        $filter = new stdClass();
        $filter->campaigns = $campaigns_ids;
        return $suppportsModel->getData($filter);
    }

}
