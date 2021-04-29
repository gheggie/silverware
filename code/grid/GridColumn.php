<?php

/**
 * An extension of the grid component class for a grid column.
 */
class GridColumn extends GridComponent
{
    private static $singular_name = "Column";
    private static $plural_name   = "Columns";
    
    private static $description = "A column within a SilverWare template or layout grid row";
    
    private static $icon = "silverware/images/icons/grid/GridColumn.png";
    
    private static $hide_ancestor = "GridComponent";
    
    private static $db = array(
        'IsSidebar' => 'Boolean',
        'ColumnWidth' => 'Varchar(16)',
        'ColumnOffset' => 'Varchar(16)',
        'HiddenWhenEmpty' => 'Boolean'
    );
    
    private static $defaults = array(
        'IsSidebar' => 0,
        'ColumnWidth' => 'auto',
        'ColumnOffset' => 'none',
        'HiddenWhenEmpty' => 0
    );
    
    private static $allowed_children = array(
        'BaseComponent'
    );
    
    /**
     * @config
     * @var array
     */
    private static $numeric_widths = array(
        'one' => 1,
        'two' => 2,
        'three' => 3,
        'four' => 4,
        'five' => 5,
        'six' => 6,
        'seven' => 7,
        'eight' => 8,
        'nine' => 9,
        'ten' => 10,
        'eleven' => 11,
        'twelve' => 12
    );
    
    /**
     * @config
     * @var integer
     */
    private static $default_sidebar_width = 3;
    
    /**
     * Answers an array of column widths mapped to numeric widths.
     *
     * @return array
     */
    public static function get_numeric_widths()
    {
        return Config::inst()->get('GridColumn', 'numeric_widths');
    }
    
    /**
     * Answers the default sidebar width.
     *
     * @return integer
     */
    public static function get_default_sidebar_width()
    {
        return Config::inst()->get('GridColumn', 'default_sidebar_width');
    }
    
    /**
     * Answers a collection of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Create Style Fields:
        
        if ($Row = $this->Row()) {
            
            if ($Row->isManual()) {
                
                $fields->addFieldToTab(
                    'Root.Style',
                    ToggleCompositeField::create(
                        'StyleGridToggle',
                        _t('GridColumn.GRID', 'Grid'),
                        array(
                            DropdownField::create(
                                'ColumnWidth',
                                _t('GridColumn.COLUMNWIDTH', 'Column width'),
                                $this->config()->column_widths
                            ),
                            DropdownField::create(
                                'ColumnOffset',
                                _t('GridColumn.COLUMNOFFSET', 'Column offset'),
                                $this->config()->column_offsets
                            )
                        )
                    )
                );
                
            }
            
        }
        
        // Create Options Fields:
        
        $fields->addFieldToTab(
            'Root.Options',
            ToggleCompositeField::create(
                'GridColumnOptions',
                $this->i18n_singular_name(),
                array(
                    CheckboxField::create(
                        'IsSidebar',
                        _t('GridColumn.COLUMNISASIDEBAR', 'Column is a sidebar')
                    ),
                    CheckboxField::create(
                        'HiddenWhenEmpty',
                        _t('GridColumn.HIDECOLUMNWHENEMPTY', 'Hide column when empty')
                    )
                )
            )
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Event method called before the receiver is written to the database.
     */
    public function onBeforeWrite()
    {
        // Call Parent Event:
        
        parent::onBeforeWrite();
        
        // Auto-Detect Sidebar:
        
        if (!$this->ID && $this->Title == 'Sidebar') {
            $this->IsSidebar = 1;
            $this->HiddenWhenEmpty = 1;
        }
    }
    
    /**
     * Answers the parent row of the receiver.
     *
     * @return GridRow
     */
    public function Row()
    {
        if ($this->Parent() instanceof GridRow) {
            
            return $this->Parent();
            
        }
    }
    
    /**
     * Answers true if the column width of the receiver is auto.
     *
     * @return boolean
     */
    public function isAuto()
    {
        return $this->ColumnWidth == 'auto';
    }
    
    /**
     * Answers an array of class names for the receiver.
     *
     * @return array
     */
    public function getClassNames()
    {
        $classes = parent::getClassNames();
        
        if ($width = $this->getWidthClass()) {
            $classes[] = $width;
        }
        
        if ($offset = $this->getOffsetClass()) {
            $classes[] = $offset;
        }
        
        return $classes;
    }
    
    /**
     * Answers the width of the receiver as an integer (including any offset).
     *
     * @return integer
     */
    public function getWidth()
    {
        $column_width = SilverWareTools::nice_column_to_int($this->ColumnWidth);
        $offset_width = SilverWareTools::nice_column_to_int($this->ColumnOffset);
        
        return ($column_width + $offset_width);
    }
    
    /**
     * Answers the appropriate column width class for the receiver.
     *
     * @return string
     */
    public function getWidthClass()
    {
        if ($Row = $this->Row()) {
            return $Row->getColumnClass($this);
        }
    }
    
    /**
     * Answers the appropriate column offset class for the receiver.
     *
     * @return string
     */
    public function getOffsetClass()
    {
        if ($this->ColumnOffset && $this->ColumnOffset != 'none') {
            
            return $this->getStringOffsetClass($this->ColumnOffset);
            
        }
    }
    
    /**
     * Answers the column width class for the receiver.
     *
     * @return string
     */
    public function getColumnClass()
    {
        if ($this->isAuto()) {
            return $this->getAutoColumnClass();
        }
        
        return $this->getManualColumnClass();
    }
    
    /**
     * Answers the appropriate auto column class for the receiver.
     *
     * @return string
     */
    public function getAutoColumnClass()
    {
        return $this->getIntegerWidthClass($this->getAutoColumnWidth());
    }
    
    /**
     * Answers the width for auto columns.
     *
     * @return integer
     */
    public function getAutoColumnWidth()
    {
        if ($Row = $this->Row()) {
            return $Row->getAutoColumnWidth();
        }
    }
    
    /**
     * Answers the manual column width class for the receiver.
     *
     * @return string
     */
    public function getManualColumnClass()
    {
        return $this->getStringWidthClass($this->ColumnWidth);
    }
    
    /**
     * Answers true if the receiver is a sidebar column.
     *
     * @return boolean
     */
    public function isSidebar()
    {
        return $this->IsSidebar;
    }
    
    /**
     * Answers the default sidebar width.
     *
     * @return integer
     */
    public function getDefaultSidebarWidth()
    {
        return $this->config()->default_sidebar_width;
    }
    
    /**
     * Renders the receiver for the HTML template (overrides parent method for performance reasons).
     *
     * @param string $layout
     * @param string $title
     * @return string
     */
    public function Render($layout = null, $title = null)
    {
        $tag = $this->IsSidebar ? 'aside' : 'div';
        
        $output = sprintf("<%s %s>\n", $tag, $this->getAttributesHTML());
        
        foreach ($this->getEnabledChildren() as $Child) {
            $output .= $Child->Render($layout, $title);
        }
        
        $output .= sprintf("</%s>\n", $tag);
        
        return $output;
    }
    
    /**
     * Disables the receiver if it contains no children and is configured to hide when empty.
     *
     * @return boolean
     */
    public function Disabled()
    {
        if ($this->HiddenWhenEmpty && $this->getEnabledChildren()->count() == 0) {
            return true;
        }
        
        return parent::Disabled();
    }
}

/**
 * An extension of the grid component controller class for a grid column.
 */
class GridColumn_Controller extends GridComponent_Controller
{
    /**
     * Defines the URLs handled by this controller.
     */
    private static $url_handlers = array(
        
    );
    
    /**
     * Defines the allowed actions for this controller.
     */
    private static $allowed_actions = array(
        
    );
    
    /**
     * Performs initialisation before any action is called on the receiver.
     */
    public function init()
    {
        // Initialise Parent:
        
        parent::init();
    }
}
