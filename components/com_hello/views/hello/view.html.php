<?php

/**
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_2
 * @license    GNU/GPL
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.toolbar');
jimport('joomla.application.component.view');

/**
 * HTML View class for the HelloWorld Component
 *
 * @package    HelloWorld
 */
class HelloViewHello extends JView {

    private function getModels() {
        $this->HelloModel = new HelloModelHello();
    }

    public function display() {
        $this->getModels();
        $this->data = &$this->HelloModel->getData();
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

        array_push($this->btnToolbar, $save);
    }

    private function getEnvVars() {
        $mainframe = &JFactory::getApplication();
        $this->limit = $mainframe->getUserStateFromRequest('personnel.list.limit', 'limit', 10, 'int');
        $this->limitstart = $mainframe->getUserStateFromRequest('personnel.limitstart', 'limitstart', 0, 'int');
        $this->limitstart = ($this->limit != 0 ? (floor($this->limitstart / $this->limit) * $this->limit) : 0);
        $this->personnelid = JRequest::getVar('personnelid', '');
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
