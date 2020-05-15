<?php

namespace App\Libraries\MenuBuilder;

class MenuBuilder
{

    /**
     * Records
     * 
     * @var array
     */
    protected $records = array();

    /**
     * Menu data group by specific parent identifier or children records
     *
     * @var array 
     */
    protected $items = array();

    /**
     * Current url
     *
     * @var string
     */
    protected $currentUrl = null;

    /**
     * Selected menu
     *
     * @var string
     */
    protected $selectedMenu = array();

    /**
     * Menu Attributes
     *
     * @var array
     */
    protected $menuAttributes = array("class" => "nav navbar-nav");

    /**
     * Dropdown List Attributes
     *
     * @var array
     */
    protected $dropdownListAttributes = array("class" => "dropdown");

    /**
     * Dropdown Link Attributes
     *
     * @var array
     */
    protected $dropdownLinkAttributes = array("class" => "dropdown-toggle", "data-toggle" => "dropdown", "role" => "button", "aria-expanded" => "false");

    /**
     * Dropdown List Span Attributes
     *
     * @var array
     */
    protected $dropdownListElement = ' <span %1$s></span>';

    /**
     * Dropdown List Span Attributes
     *
     * @var array
     */
    protected $dropdownListElementAttributes = array("class" => "caret");

    /**
     * Dropdown Menu Attributes
     * 
     * @var array
     */
    protected $dropdownMenuAttributes = array("class" => "dropdown-menu", "role" => "menu");

//    /**
//     * List attributes
//     * 
//     * @var type 
//     */
//    protected $listAttributes = "nav-item";

    /**
     * Constructor
     * Set data for menu
     * Group data using parent ID
     * Set children records of specific parent
     * 
     * @param array $records
     * @return \App\Libraries\Menu\Menu
     */
    public function __construct(array $records)
    {
        foreach ($records as $record) {
            $this->records[$record['id']] = $record;
            $this->items[$record['parent_id']][] = $record;
        }
//        dd($this->items);
        return $this;
    }

    /**
     * Set current url
     * 
     * @param array $attributes
     * 
     * @return $this
     */
    public function setCurrentUrl($attributes)
    {
        $this->currentUrl = $attributes;
        return $this;
    }

    /**
     * Get selected menu
     * 
     * @param array $attributes
     * 
     * @return $this
     */
    public function getSelectedMenu()
    {
        krsort($this->selectedMenu);
        return $this->selectedMenu;
    }

    /**
     * Set menu attributes
     * 
     * @param array $attributes
     * 
     * @return $this
     */
    public function setMenuAttributes($attributes)
    {
        $this->menuAttributes = $attributes;
        return $this;
    }

    /**
     * Set dropdown list attributes
     * 
     * @param array $attributes
     * 
     * @return $this
     */
    public function setDropdownListAttributes($attributes)
    {
        $this->dropdownListAttributes = $attributes;
        return $this;
    }

    /**
     * Set dropdown link attributes
     * 
     * @param array $attributes
     * 
     * @return $this
     */
    public function setDropdownLinkAttributes($attributes)
    {
        $this->dropdownLinkAttributes = $attributes;
        return $this;
    }

    /**
     * Set dropdown list element
     * 
     * @param array $attributes
     * 
     * @return $this
     */
    public function setDropdownListElement($attributes)
    {
        $this->dropdownListElement = $attributes;
        return $this;
    }

    /**
     * Set dropdown list element attributes
     * 
     * @param array $attributes
     * 
     * @return $this
     */
    public function setDropdownListElementAttributes($attributes)
    {
        $this->dropdownListElementAttributes = $attributes;
        return $this;
    }

    /**
     * Set dropdown menu attributes
     * 
     * @param array $attributes
     * 
     * @return $this
     */
    public function setDropdownMenuAttributes($attributes)
    {
        $this->dropdownMenuAttributes = $attributes;
        return $this;
    }

    /**
     * Build an HTML attribute string from an array.
     *
     * @param array $attributes
     *
     * @return string
     */
    public function attributes($attributes)
    {
        $html = [];

        foreach ((array) $attributes as $key => $value) {
            $element = $this->attributeElement($key, $value);

            if (!is_null($element)) {
                $html[] = $element;
            }
        }

        return count($html) > 0 ? ' ' . implode(' ', $html) : '';
    }

    /**
     * Build a single attribute element.
     *
     * @param string $key
     * @param string $value
     *
     * @return string
     */
    protected function attributeElement($key, $value)
    {
        // For numeric keys we will assume that the key and the value are the same
        // as this will convert HTML attributes such as "required" to a correct
        // form like required="required" instead of using incorrect numerics.
        if (is_numeric($key)) {
            $key = $value;
        }

        if (!is_null($value)) {
            return $key . '="' . htmlentities($value, ENT_QUOTES, 'UTF-8', false) . '"';
        }
    }

    /**
     * Create menu
     *
     * @param array $items
     * @return string
     */
    protected function buildSelectedMenu($item)
    {
        $this->selectedMenu[] = $item;
        if ($item['parent_id']) {
            $item = $this->records[$item['parent_id']];
            $this->buildSelectedMenu($item);
        }
    }

    /**
     * Create menu
     *
     * @param array $items
     * @return string
     */
    protected function createMenu(array $items, $level = 0)
    {
        $menuAttributes = array();
        if (array_key_exists($items[0]['parent_id'], $this->items)) {
            $menuAttributes = (!$level) ? $this->menuAttributes : $this->dropdownMenuAttributes;
        }
        $html = '<ul' . $this->attributes($menuAttributes) . ' level-attr=' . $level . '>';
        foreach ($items as $key => $item) {
            $id = $item['id'];
            $dropdownListAttributes = $dropdownLinkAttributes = $dropdownListElementAttributes = array();
            $listElement = "";
            if (array_key_exists($id, $this->items)) {
                $dropdownListAttributes = $this->dropdownListAttributes;
                $dropdownLinkAttributes = $this->dropdownLinkAttributes;
                $dropdownListElementAttributes = $this->dropdownListElementAttributes;
                $listElement = sprintf($this->dropdownListElement, $this->attributes($dropdownListElementAttributes));
            }
            // Set active class to the selected menu
            if (!array_key_exists($id, $this->items) && $this->currentUrl == $item['url']) {
                $dropdownListAttributes['class'] = isset($dropdownListAttributes['class']) ? $dropdownListAttributes['class'] . ' active' : 'active';
                $this->buildSelectedMenu($item);
            }
            $html .= '<li' . $this->attributes($dropdownListAttributes) . '><a href="' . $item['url'] . '"' . $this->attributes($dropdownLinkAttributes) . '>' . $item['name'] . $listElement . '</a>';
            $levelID = $level + 1;
            $html .= (array_key_exists($id, $this->items)) ? $this->createMenu($this->items[$id], $levelID) : null;
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    /**
     * Get menu
     *
     * @return string
     */
    public function build()
    {
        return $this->createMenu($this->items[0]);
    }

    /**
     * Get menu
     *
     * @return string
     */
    public function get()
    {
        return $this->build();
    }

    /**
     * Render Menu
     */
    public function render()
    {
        echo $this->build();
    }

    /**
     * Get menu
     *
     * @return string
     */
    public function getList()
    {
        $items = isset($this->items[0]) ? $this->items[0] : array();
        return $this->createList($items);
    }

    /**
     * Create menu
     *
     * @param array $items
     * @return string
     */
    protected function createList(array $items, $level = 0)
    {
        $html = '<ol class="dd-list">';
        foreach ($items as $key => $item) {
            $id = $item['id'];
            $html .= '<li class="dd-item" data-id="' . $item['id'] . '">';
            $html .= '<div class="dd-handle dd3-handle"></div>';
            $html .= '<div class="dd3-content">' . $item['name'] . '<button class="btn-menu-remove">' . trans('main.remove') . '</button></div>';
            $levelID = $level + 1;
            $html .= (array_key_exists($id, $this->items)) ? $this->createList($this->items[$id], $levelID) : null;
            $html .= '</li>';
        }
        $html .= '</ol>';
        return $html;
    }

}
