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
 * Steg World Component Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class StegController extends JController {

    /**
     * Method to display the view
     *
     * @access    public
     */
    function display() {
        parent::display();
    }

    function save() {
        $model = new StegModelSteg();

        if (!$model->store()) {
            $msg = JText::_('Error : de sauvegarde');
        } else {
            $msg = JText::_('Element enregisté');
        }
        $this->setRedirect('index.php?option=com_steg&controller=steg', $msg);
    }

    function edit() {
        $view = $this->getView("Steg", "html", "StegView");
        $view->display();
    }

}
