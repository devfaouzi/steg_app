
<?php

defined('_JEXEC') or die();
jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

require_once JPATH_COMPONENT . DS . "views" . DS . "abstractstegview.php";

class StegViewHellos extends AbstractStegView {

    var $hellosModel;

    private function getModels() {
        $this->hellosModel = new StegModelHellos();
        $this->helloModel = new StegModelHello();
    }

    function display($tpl = null) {
        $this->getModels();
        $this->createToolbar();
        $this->getEnvVars();
        $hello_id = JRequest::getVar("hello_id", 0);
        $filter = new stdClass();
        $filter->name = JRequest::getVar("name", "");
        $this->items = $this->hellosModel->getData($filter, "id", "desc", $this->limitstart, $this->limit);
        
        $this->count = $this->hellosModel->getTotal($hellosFilter);
        $this->pagination = new JPagination($this->count, $this->limitstart, $this->limit);
        parent::display($tpl);
    }

    private function createToolbar() {
        $this->btnToolbar = array();
        $c = new stdClass();
        $c->name = 'Close';
        $c->task = 'backToHome';
        $n = new stdClass();
        $n->name = 'new';
        $n->task = 'edit';
        array_push($this->btnToolbar, $n, $c);
    }

    private function getEnvVars() {
        $mainframe = &JFactory::getApplication();
        $this->limit = $mainframe->getUserStateFromRequest('hellos.list.limit', 'limit', 5, 'int');
        $this->limitstart = $mainframe->getUserStateFromRequest('hellos.limitstart', 'limitstart', 0, 'int');
        $this->limitstart = ($this->limit != 0 ? (floor($this->limitstart / $this->limit) * $this->limit) : 0);
    }

    private function addFilter() {
        $afilter = new stdClass();
        return $afilter;
    }

}
