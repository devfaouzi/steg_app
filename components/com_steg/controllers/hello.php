<?php

defined('_JEXEC') or die('Restricted access');

class StegControllerHello extends StegController {

    var $_s = 'Hello';

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function display() {
        $view = $this->getView("Hellos", "html", "StegView");
        $view->display();
    }

    function backToHome() {
        $link = "index.php";
        $this->setRedirect($link);
    }

    function cancel() {
        $link = "index.php?option=com_steg&controller=Hello";
        $this->setRedirect($link);
    }

    function edit() {
        $view = $this->getView("Hello", "html", "StegView");
        $view->display();
    }

    function save() {
        $model = new StegModelHello();
        $data = JRequest::get('post');
        if (!$model->store($data)) {
            $msg = JText::_('Error : de sauvegarde');
        } else {
            $msg = JText::_('Element enregisté');
        }
        $this->setRedirect('index.php?option=com_steg&controller=Hello', $msg);
    }

    function remove() {
        $model = $this->getModel('Hello');
        if (!$model->delete()) {
            $msg = JText::_('Error: One or more Hello could not be Deleted');
        } else {
            $msg = JText::_('Hello(s) Deleted');
        }
        $this->display();
    }

    function apply() {
        $model = new StegModelHello();
        $data = JRequest::get('post');
        if (!$model->store($data)) {
            $errors = $model->getErrors();
            $msg = JText::_('Error : it could not be saved');
        } else {
            $id = $model->_id;
            $msg = JText::_('category saved');
        }
        $link = 'index.php?option=com_steg&controller=Hello&task=edit&cid=' . $id;
        $mainframe = &JFactory::getApplication();
        $mainframe->redirect($link, $msg);
    }

}
