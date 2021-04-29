<?php

/**
 * An extension of the grid component class for a grid row.
 */
class GridRow extends GridComponent
{
    private static $singular_name = "Row";
    private static $plural_name   = "Rows";
    
    private static $description = "A row within a SilverWare template or layout grid";
    
    private static $icon = "silverware/images/icons/grid/GridRow.png";
    
    private static $hide_ancestor = "GridComponent";
    
    private static $default_child = "GridColumn";
    
    private static $db = array(
        'ColumnSizing' => 'Varchar(16)'
    );
    
    private static $defaults = array(
        'ColumnSizing' => 'auto'
    );
    
    private static $allowed_children = array(
        'GridColumn'
    );
    
    private static $grid_width = 12;
    
    /**
     * Answers a collection of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Create Field Objects:
        
        $fields->addFieldToTab(
            'Root.Style',
            ToggleCompositeField::create(
                'StyleGridToggle',
                _t('GridRow.GRID', 'Grid'),
                array(
                    DropdownField::create(
                        'ColumnSizing',
                        _t('GridRow.COLUMNSIZING', 'Column sizing'),
                        $this->config()->column_sizing
                    )
                )
            )
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers an array of class names for the receiver.
     *
     * @return array
     */
    public function getClassNames()
    {
        $classes = parent::getClassNames();
        
        $classes[] = "row";
        
        return $classes;
    }
    
    /**
     * Answers the grid columns within the receiver.
     *
     * @return DataList
     */
    public function Columns()
    {
        return GridColumn::get()->filter('ParentID', $this->ID);
    }
    
    /**
     * Answers true if the column sizing of the receiver is auto.
     *
     * @return boolean
     */
    public function isAuto()
    {
        return $this->ColumnSizing == 'auto';
    }
    
    /**
     * Answers true if the column sizing of the receiver is manual.
     *
     * @return boolean
     */
    public function isManual()
    {
        return $this->ColumnSizing == 'manual';
    }
    
    /**
     * Answers the total number of columns within the receiver (all types, enabled or disabled).
     *
     * @return integer
     */
    public function getNumberOfColumns()
    {
        return $this->Columns()->count();
    }
    
    /**
     * Answers a list of all enabled columns within the receiver.
     *
     * @return DataList
     */
    public function getEnabledColumns()
    {
        return $this->Columns()->filterByCallback(function ($Column) {
            return !$Column->Disabled();
        });
    }
    
    /**
     * Answers a list of all enabled sidebar columns within the receiver.
     *
     * @return DataList
     */
    public function getEnabledSidebars()
    {
        return $this->getEnabledColumns()->filterByCallback(function ($Column) {
            return $Column->isSidebar();
        });
    }
    
    /**
     * Answers a list of all enabled non-sidebar columns within the receiver.
     *
     * @return DataList
     */
    public function getEnabledNonSidebars()
    {
        return $this->getEnabledColumns()->filterByCallback(function ($Column) {
            return !$Column->isSidebar();
        });
    }
    
    /**
     * Answers the number of enabled columns within the receiver.
     *
     * @return integer
     */
    public function getNumberOfEnabledColumns()
    {
        return $this->getEnabledColumns()->count();
    }
    
    /**
     * Answers the number of enabled sidebar columns within the receiver.
     *
     * @return integer
     */
    public function getNumberOfEnabledSidebars()
    {
        return $this->getEnabledSidebars()->count();
    }
    
    /**
     * Answers the number of enabled non-sidebar columns within the receiver.
     *
     * @return integer
     */
    public function getNumberOfEnabledNonSidebars()
    {
        return $this->getEnabledNonSidebars()->count();
    }
    
    /**
     * Answers true if the receiver has enabled columns.
     *
     * @return boolean
     */
    public function hasEnabledColumns()
    {
        return ($this->getNumberOfEnabledColumns() > 0);
    }
    
    /**
     * Answers true if the receiver has enabled sidebar columns.
     *
     * @return boolean
     */
    public function hasEnabledSidebars()
    {
        return ($this->getNumberOfEnabledSidebars() > 0);
    }
    
    /**
     * Answers true if the receiver has enabled non-sidebar columns.
     *
     * @return boolean
     */
    public function hasEnabledNonSidebars()
    {
        return ($this->getNumberOfEnabledNonSidebars() > 0);
    }
    
    /**
     * Answers the appropriate grid column class for the given column.
     *
     * @param GridColumn $Column
     * @return string
     */
    public function getColumnClass(GridColumn $Column)
    {
        // Determine Row Mode:
        
        if ($this->isAuto() || ($this->isManual() && $this->allColumnsAuto())) {
            
            // Answer Mixed Class:
            
            if ($this->hasMixedColumns()) {
                return $this->getMixedColumnClass($Column);
            }
            
            // Answer Regular Class:
            
            return $this->getRegularColumnClass();
            
        } else {
            
            // Manual Row/Column Mode:
            
            return $Column->getColumnClass(); 
            
        }
    }
    
    /**
     * Answers the regular column class based on the number of enabled columns in the row.
     *
     * @return string
     */
    public function getRegularColumnClass()
    {
        $classes = array(
            1 => 'twelve columns',
            2 => 'one-half column',
            3 => 'one-third column',
            4 => 'three columns'
        );
        
        $columns = $this->getNumberOfEnabledColumns();
        
        return isset($classes[$columns]) ? $classes[$columns] : null;
    }
    
    /**
     * Answers true if all enabled columns are set to 'auto' mode.
     *
     * @return boolean
     */
    public function allColumnsAuto()
    {
        foreach ($this->getEnabledColumns() as $Column) {
            
            if (!$Column->isAuto()) {
                return false;
            }
            
        }
        
        return true;
    }
    
    /**
     * Answers true if the receiver has mixed enabled columns.
     *
     * @return boolean
     */
    public function hasMixedColumns()
    {
        return ($this->hasEnabledSidebars() && $this->hasEnabledNonSidebars());
    }
    
    /**
     * Answers the appropriate grid column class for given column (used with column auto sizing).
     *
     * @param GridColumn $Column
     * @return string
     */
    public function getMixedColumnClass(GridColumn $Column)
    {
        $width = $Column->isSidebar() ? $Column->getDefaultSidebarWidth() : $this->getNonSidebarWidth();
        
        return $this->getIntegerWidthClass($width);
    }
    
    /**
     * Answers the number of auto-sized enabled columns.
     *
     * @return integer
     */
    public function getAutoColumnCount()
    {
        return $this->getEnabledColumns()->filter('ColumnWidth', 'auto')->count();
    }
    
    /**
     * Answers the width for auto columns.
     *
     * @return integer
     */
    public function getAutoColumnWidth()
    {
        if ($count = $this->getAutoColumnCount()) {
            return floor($this->getAutoColumnTotal() / $count);
        }
    }
    
    /**
     * Answers the total width available for auto columns.
     *
     * @return integer
     */
    public function getAutoColumnTotal()
    {
        return (self::$grid_width - $this->getFixedColumnTotal());
    }
    
    /**
     * Answers the total width used by sidebar columns.
     *
     * @return integer
     */
    public function getSidebarTotal()
    {
        return ($this->getNumberOfEnabledSidebars() * GridColumn::get_default_sidebar_width());
    }
    
    /**
     * Answers the width for non-sidebar columns.
     *
     * @return integer
     */
    public function getNonSidebarWidth()
    {
        if ($count = $this->getNumberOfEnabledNonSidebars()) {
            return floor($this->getNonSidebarTotal() / $count);
        }
    }
    
    /**
     * Answers the total width available for non-sidebar columns.
     *
     * @return integer
     */
    public function getNonSidebarTotal()
    {
        return (self::$grid_width - $this->getSidebarTotal());
    }
    
    /**
     * Answers the total width used by fixed columns.
     *
     * @return integer
     */
    public function getFixedColumnTotal()
    {
        $total = 0;
        
        foreach ($this->getEnabledColumns()->exclude('ColumnWidth', 'auto') as $Column) {
            $total += $Column->getWidth();
        }
        
        return $total;
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
        $output = sprintf("<div %s>\n", $this->getAttributesHTML());
        
        foreach ($this->Columns() as $Column) {
            
            if (!$Column->Disabled()) {
                $output .= $Column->Render($layout, $title);
            }
            
        }
        
        $output .= "</div>\n";
        
        return $output;
    }
}

/**
 * An extension of the grid component controller class for a grid row.
 */
class GridRow_Controller extends GridComponent_Controller
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
