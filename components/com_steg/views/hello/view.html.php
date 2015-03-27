<?php

// No direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
require_once JPATH_COMPONENT . DS . "views" . DS . "abstractstegview.php";

class StegViewHello extends AbstractStegView {

    var $helloModel;
    var $helloUsersModel;
    var $usersModel;
    var $hellosModel;
    var $hello;

    private function getModels() {
        $this->helloModel = new StegModelHello();
    }

    public function display() {
        $this->getModels();
        $this->data = $this->helloModel->getData();
        $filter = new stdClass();
        $this->createToolbar();
        $this->getEnvVars();
        parent::display();
    }

    private function createToolbar() {
        $isNew = ($this->data->id < 1);
        $text = $isNew ? JText::_('Nouveau') : JText::_('Modifier');

        $this->btnToolbar = array();
        $save = new stdClass();
        $save->name = 'save';
        $apply = new stdClass();
        $apply->name = 'apply';

        array_push($this->btnToolbar, $save, $apply);
        if (!$isNew) {
            $d = new stdClass();
            $d->name = 'delete';
            $d->task = 'remove';
            array_push($this->btnToolbar, $d);
        }
    }

    private function getEnvVars() {
        $mainframe = &JFactory::getApplication();
        $this->limit = $mainframe->getUserStateFromRequest('hello.list.limit', 'limit', 10, 'int');
        $this->limitstart = $mainframe->getUserStateFromRequest('hello.limitstart', 'limitstart', 0, 'int');
        $this->limitstart = ($this->limit != 0 ? (floor($this->limitstart / $this->limit) * $this->limit) : 0);
        $this->helloid = JRequest::getVar('helloid', '');
    }

}
