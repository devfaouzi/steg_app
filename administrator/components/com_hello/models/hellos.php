<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
jimport('joomlapplication.component.model');

class HelloModelHellos extends JModel {

    var $_data;

    function _buildQuery() {
        $query = ' SELECT * FROM #__hello';
        return $query;
    }

    private function addFilters(&$filter, &$where, &$query) {
        $db = &JFactory::getDBO();
        if (isset($filter->id) && !empty($filter->id)) {
            $where [] = "id = " . $filter->id;
        } 
        if (isset($filter->nom)) {
            $where [] = "nom Like " . $db->Quote($db->getEscaped($filter->nom, true) . '%', false);
        } 
         if (isset($filter->prenom)) {
            $where [] = "prenom Like " . $db->Quote($db->getEscaped($filter->prenom, true) . '%', false);
        } 
        if (isset($filter->fonctions) && is_array($filter->fonctions) && !empty($filter->fonctions)) {
            $where [] = "fonction IN (" . implode(",", $filter->fonctions) . ")";
        }
        if (isset($filter->inIds) && is_array($filter->inIds) && !empty($filter->inIds)) {
            $where [] = "id IN (" . implode(",", $filter->inIds) . ")";
        }
        if (isset($filter->notInIds) && is_array($filter->notInIds) && !empty($filter->notInIds)) {
            $where [] = "id NOT IN (" . implode(",", $filter->notInIds) . ")";
        }
    }

    function getData(&$filter, $order = "", $order_dir = "", $limitstart = 0, $limit = 10) {
        $query = $this->_buildQuery();
        $where = array();
        $this->addFilters($filter, $where, $query);
        $where = count($where) ? ' WHERE ' . implode(' AND ', $where) : '';
         
        $orderby = "";
        if ($order && $order_dir) {
            $orderby = ' ORDER BY ' . $order . ' ' . $order_dir;
        }
        $this->_data = $this->_getList($query . $where . $orderby, $limitstart, $limit);

        return $this->_data;
    }

    /**
     * @return int number of rows
     */
    function getTotal(&$filter) {
        /*
         * On recupere le nombre de resultat
         */
        $query = $this->_buildQuery();
        $db = &JFactory::getDBO();
        $where = array();
        $this->addFilters($filter, $where, $query);
        $where = count($where) ? ' WHERE (' . implode(') AND (', $where) . ')' : '';
        //  $query ='SELECT count(t1.id) '. substr($query,strpos($query,"FROM"));
        //  $query=$query.$where;  
        $query = "SELECT SQL_CALC_FOUND_ROWS " . substr($query, strpos($query, "SELECT") + 7);
        $db->setQuery($query . $where);

        $db->loadResult();
        $db->setQuery("SELECT FOUND_ROWS();");
        return $db->loadResult();
    }

}
