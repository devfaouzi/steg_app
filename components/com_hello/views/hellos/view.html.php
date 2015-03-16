<?php

/**
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_2
 * @license    GNU/GPL
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

/**
 * HTML View class        $this->pagination = new JPagination($this->count, for the HelloWorld Component
 *
 * @package    HelloWorld
 */
class HelloViewHellos extends JView {

    private function getModels() {
        $this->hellosModel = new HelloModelHellos();
    }

    function display($tpl = null) {
        $this->getModels();
        $this->createToolbar();
        $this->getEnvVars();
        $this->items = $this->hellosModel->getData($this->addFilter(), "id", "desc", $this->limitstart, $this->limit);
        $this->count = $this->hellosModel->getTotal($this->addFilter());
        $this->pagination = new JPagination($this->count, $this->limitstart, $this->limit);
        parent::display($tpl);
    }

    private function createToolbar() {
        $this->btnToolbar = array();
        $new = new stdClass();
        $new->name = 'new';
        $new->href = JRoute::_('index.php?option=com_hello&controller=hello&task=edit&layout=new');
        array_push($this->btnToolbar, $new);
    }

    private function getEnvVars() {
        $mainframe = &JFactory::getApplication();
        $this->limit = $mainframe->getUserStateFromRequest('personnels.list.limit', 'limit', 5, 'int');
        $this->limitstart = $mainframe->getUserStateFromRequest('personnels.limitstart', 'limitstart', 0, 'int');
        $this->limitstart = ($this->limit != 0 ? (floor($this->limitstart / $this->limit) * $this->limit) : 0);
    }

    private function addFilter() {
        $afilter = new stdClass();
//        $afilter->nom = JRequest::getVar("nom", "");
//        $afilter->prenom = JRequest::getVar("prenom", "");
        return $afilter;
    }

    protected function displayToolbar(array $tasks) {
        $html = '';
        if (count($tasks)) {
            $html .= '<div id="toolbar-box">';
            $html .= '<span class="left"></span>';
            $html .= '<span class="btn">';
            for ($i = 0; $i < count($tasks); $i++) {
                $btn = $tasks[$i];
                $name = $btn->name;
//                $event= @($btn->event) ? $btn->event : '';
                $task = @($btn->task) ? $btn->task : $btn->name;
                $text = @($btn->text) ? $btn->text : $btn->name;
                $last = ($i == count($tasks) - 1) ? 'last' : '';
                $href = @($btn->href) ? $btn->href : 'javascript:onBtnClick(\'' . $task . '\');';
                $alt = @($btn->alt) ? $btn->alt : '';
                $rel = @($btn->rel) ? $btn->rel : '';
                $target = @($btn->target) ? $btn->target : '';
                $class = @($btn->class) ? $btn->class : '';
                $html .= '<a id="btn_' . $name . '" class="' . $last . ' ' . $class . '" href="' . $href . '" rel="' . $rel . '" onmouseover="onBtnOver(this)"  alt="' . $alt . '" onmouseout="onBtnOut(this)">';
                $html .= '<span class="toopltipstext">' . JText::_($text) . '</span>';
                $html .= '</a>';
            }
            $html .= '</span>';
            $html .= '<span class="right"></span>';
            $html .= '</div>';
        }
        echo $html;
    }

}
