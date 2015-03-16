<?php

/**
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_1#Creating_the_Entry_Point
 * @license    GNU/GPL
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

require_once( JPATH_COMPONENT . DS . 'includemodels.php' );

/**
 * Hello World Component Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class HelloController extends JController {

    /**
     * Method to display the view
     *
     * @access    public
     */
    function display() {
        parent::display();
    }

    function save() {
        $model = new HelloModelHello();

        if (!$model->store()) {
            $msg = JText::_('Error : de sauvegarde');
        } else {
            $msg = JText::_('Element enregisté');
        }
        $this->setRedirect('index.php?option=com_hello&controller=hello', $msg);
    }

    function edit() {
        $view = $this->getView("Hello", "html", "HelloView");
        $view->display();
    }

}
