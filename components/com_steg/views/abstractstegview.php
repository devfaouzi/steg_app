<?php
jimport('joomla.application.component.view');
jimport('joomla.html.toolbar');

abstract class AbstractStegView extends JView {

    var $user;
    var $limit = 10;
    var $limitstart = 0;
    var $page = 1;
    var $total = 0;
    var $bar;
    var $list_status;

    function __construct() {
        parent::__construct();
        $this->user = &JFactory::getUser();
        $this->editor = &JFactory::getEditor();
        $this->config = &JComponentHelper::getParams('com_steg');

        $this->initLimits();
    }

    private function createModels() {
    }

    function display($tpl = null) {

        parent::display($tpl);
    }

    function displayRaw($tpl = null) {
        parent::display($tpl);
    }

    protected function setPageMetasValues($title, $description = "", $keywords = "", $robots = null) {
        $document = &JFactory::getDocument();
        $document->setTitle($title);
        $document->setMetaData('keywords', $keywords);
        $document->setDescription($description);
        if ($robots) {
            $document->setMetaData('robots', $robots);
        }
    }

    protected function setPageMetas(&$metas) {
        $registry = &JRegistry::getInstance('nysac');
        $registry->setValue("currentPageMetas", $metas);
        $this->setPageMetasValues(@$metas->title, @$metas->description, @$metas->keywords, @$metas->robots);
    }

    protected function fillSearchTree(&$searchTree, &$parents, $parent_id, &$model) {
        $filter = new stdClass();
        $filter->parent_id = $parent_id;
        $children = $model->getData($filter, "name", "asc");
        if (count($children)) {
            foreach ($children as $child) {
                $filter->parent_id = $child->id;
                $child->childrenNb = $model->getTotal($filter);
                if ($this->isInParents($child, $parents)) {
                    $child->selected = true;
                    $this->fillSearchTree($searchTree, $parents, $child->id, $model);
                } else {
                    $child->selected = false;
                }
            }
            $searchTree[] = $children;
        }
    }

    protected function isInParents(&$child, &$parents) {
        foreach ($parents as $parent) {
            if ($parent->id == $child->id) {
                return true;
            }
        }
        return false;
    }

    /**
     * Permet de definir le fil d'arianne de la page (pathWay)
     * format des arguments var args :
     * array("nom du lien" => "lien"), array("nom du lien2" => "lien2"), ...
     */
    protected function setPathWay() {
        $argc = func_num_args();
        if ($argc > 0) {
            global $mainframe;
            $pathway = &$mainframe->getPathway();
            $i = 0;
            while ($i < $argc) {
                $arg = func_get_arg($i);
                list($name, $link) = each($arg);
                $pathway->addItem($name, $link);
                ++$i;
            }
        }
    }

    /**
     * Render les modules en position $position
     */
    protected function loadModules($position, $style = -2) {
        $document = &JFactory::getDocument();
        $renderer = $document->loadRenderer('module');
        $params = array('style' => $style);
        foreach (JModuleHelper::getModules($position) as $mod) {
            echo $renderer->render($mod, $params);
        }
    }

    /**
     * Retourne l'html des modules en position $position
     */
    protected function getModulesHTML($position, $style = -2) {
        $document = &JFactory::getDocument();
        $renderer = $document->loadRenderer('module');
        $params = array('style' => $style);
        $html = "";
        foreach (JModuleHelper::getModules($position) as $mod) {
            $html .= $renderer->render($mod, $params);
        }
        return $html;
    }

    protected function countModules($position) {
        $document = &JFactory::getDocument();
        $modules = JModuleHelper::getModules($position);
        if (!$modules || ($c = count($modules)) <= 0) {
            return 0;
        }
        return $c;
    }

    private function agent($browser) {
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        return strstr($useragent, $browser);
    }

    protected function isIe() {
        return $this->agent("MSIE");
    }

    protected function isOldIe() {
        return $this->agent("MSIE") && !$this->agent("MSIE 8") && !$this->agent("MSIE 9");
    }

    protected function initLimits() {
        $this->page = 1;
        if (($page = JRequest::getVar("page")) != null && is_numeric($page)) {
            $this->page = intval($page);
        }
        $this->limitstart = ($this->page - 1) * $this->limit;
    }

    protected function createPagePagination($url, $layout, $visiblePagesNumber = 0) {
        if ($this->total > 0) {
            require_once (JPATH_BASE . DS . "components" . DS . "com_calyos" . DS . "classes" . DS . "pagepagination.php");
            $this->pagePagination = new PagePagination($this->total, $this->limit, $this->page, $url, $layout, $visiblePagesNumber);
        }
    }

    protected function displayPagination() {
        if ($this->pagePagination) {
            $this->pagePagination->display($this->_path["template"]);
        }
    }

    protected function getLangPrefix() {
        $lang = JRequest::getVar("lang", "fr");
        return $lang == "fr" ? "fr" : "en";
    }

    protected function toolbar_title($title, $icon = 'generic.png') {

//   global $mainframe;
//strip the extension
        $icon = preg_replace('#\.[^.]*$#', '', $icon);
        $html = "<div class=\"header icon-48-$icon\">\n";
        $html .= "$title\n";
        $html .= "</div>\n";

        return $html;
    }

    function addIcon($image, $view, $text) {
        $lang = & JFactory::getLanguage();
        $link = 'index.php?option=com_steg&controller=' . $view . "&task=display";
        ?>
        <div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
            <div class="icon"><a href="<?php echo $link; ?>">
                    <img src="<?php echo JUri::root(); ?>administrator/components/com_steg/images/<?php echo $image; ?>" alt="<?php echo $text; ?>"/>
                    <span><?php echo $text; ?></span></a></div>
        </div>
        <?php
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
?>