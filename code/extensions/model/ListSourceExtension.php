<?php

/**
 * An extension of the data extension class to add list source functionality to the extended object.
 */
class ListSourceExtension extends DataExtension
{
    private static $db = array(
        'ItemsPerPage' => 'Varchar(16)',
        'NumberOfItems' => 'Varchar(16)',
        'PaginateItems' => 'Boolean',
        'PaginationVar' => 'Varchar(32)',
        'SortItemsBy' => "Enum('Default, Random, Custom', 'Default')",
        'CustomSort' => 'Varchar(255)',
        'ReverseItems' => 'Boolean',
        'ImageItems' => 'Boolean'
    );
    
    private static $defaults = array(
        'SortItemsBy' => 'Default',
        'ItemsPerPage' => 10,
        'PaginateItems' => 0,
        'ReverseItems' => 0,
        'ImageItems' => 0
    );
    
    private static $has_one = array(
        'ListSource' => 'SiteTree'
    );
    
    /**
     * Updates the CMS fields of the extended object.
     *
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        // Create Main Fields:
        
        $fields->addFieldsToTab(
            'Root.Main',
            array(
                DropdownField::create(
                    'ListSourceID',
                    _t('ListSourceExtension.LISTSOURCE', 'List Source'),
                    $this->getListSourceOptions()
                )->setEmptyString(' ')
            )
        );
        
         // Create Options Fields:
        
        $fields->addFieldToTab(
            'Root.Options',
            ToggleCompositeField::create(
                'ListSourceOptions',
                _t('ListSourceExtension.LISTSOURCE', 'List Source'),
                array(
                    TextField::create(
                        'NumberOfItems',
                        _t('ListSourceExtension.NUMBEROFITEMS', 'Number of items')
                    ),
                    CheckboxField::create(
                        'PaginateItems',
                        _t('ListSourceExtension.PAGINATEITEMS', 'Paginate items')
                    ),
                    DisplayLogicWrapper::create(
                        TextField::create(
                            'ItemsPerPage',
                            _t('ListSourceExtension.ITEMSPERPAGE', 'Items per page')
                        ),
                        TextField::create(
                            'PaginationVar',
                            _t('ListSourceExtension.PAGINATIONVARIABLE', 'Pagination variable')
                        )->setRightTitle(
                            _t(
                                "ListSourceExtension.PAGINATIONVARIABLERIGHTTITLE",
                                "Name of the GET variable to use for pagination (if blank, uses the variable 'start')."
                            )
                        )
                    )->displayIf('PaginateItems')->isChecked()->end(),
                    DropdownField::create(
                        'SortItemsBy',
                        _t('ListSourceExtension.SORTITEMSBY', 'Sort items by'),
                        $this->owner->dbObject('SortItemsBy')->enumValues()
                    )->setRightTitle(
                        _t(
                            'ListSourceExtension.SORTITEMSBYRIGHTTITLE',
                            'Note: random sorting disables pagination for the list items.'
                        )
                    ),
                    TextField::create(
                        'CustomSort',
                        _t('ListSourceExtension.CUSTOMSORT', 'Custom sort')
                    )->displayIf('SortItemsBy')->isEqualTo('Custom')->end(),
                    CheckboxField::create(
                        'ReverseItems',
                        _t('ListSourceExtension.REVERSEORDEROFITEMS', 'Reverse order of items')
                    ),
                    CheckboxField::create(
                        'ImageItems',
                        _t('ListSourceExtension.SHOWITEMSWITHIMAGESONLY', 'Show items with images only')
                    )
                )
            )
        );
    }
    
    /**
     * Event method called before the receiver is written to the database.
     */
    public function onBeforeWrite()
    {
        $this->owner->NumberOfItems = SilverWareTools::integer_or_null($this->owner->NumberOfItems);
        $this->owner->ItemsPerPage  = SilverWareTools::integer_or_null($this->owner->ItemsPerPage);
    }
    
    /**
     * Answers the list of items for the template.
     *
     * @return SS_List
     */
    public function getListItems()
    {
        // Create Item List:
        
        $items = ArrayList::create();
        
        // Obtain List Items:
        
        if ($source = $this->owner->getSource()) {
            
            $items = $source->getListItems();
            
        }
        
        // Sort Items (if applicable):
        
        if ($this->owner->SortItemsBy != 'Default') {
            
            $items = $this->getSortedListItems($items, $this->owner->SortItemBy);
            
        }
        
        // Remove Items without Images (if applicable):
        
        if ($this->owner->ImageItems) {
            
            $items = $items->filterByCallback(function ($item, $list) {
                return $item->HasMetaImage();
            });
            
        }
        
        // Reverse Items (if applicable):
        
        if ($this->owner->ReverseItems) {
            
            $items = $items->reverse();
            
        }
        
        // Limit Items (if applicable):
        
        if ($this->owner->NumberOfItems) {
            
            $items = $items->limit($this->owner->NumberOfItems);
            
        }
        
        // Paginate Items (if applicable):
        
        if ($this->owner->PaginateItems && $this->owner->SortItemsBy != 'Random') {
            
            $items = PaginatedList::create($items, $_GET);
            
            if ($this->owner->ItemsPerPage) {
                $items->setPageLength($this->owner->ItemsPerPage);
            }
            
            if ($this->owner->PaginationVar) {
                $items->setPaginationGetVar($this->owner->PaginationVar);
            }
            
        }
        
        // Answer Item List:
        
        return $items;
    }
    
    /**
     * Defines the list source object for the extended object.
     *
     * @param ListSource|SS_List $source
     */
    public function setSource($source)
    {
        if ($source instanceof ListSource) {
            $this->owner->ListSource = $source;
        }
        
        if ($source instanceof SS_List) {
            $this->owner->ListSource = ListSourceWrapper::create($source);
        }
    }
    
    /**
     * Answers the list source object for the extended object.
     *
     * @return ListSource
     */
    public function getSource()
    {
        if (!$this->owner->ListSourceID) {
            
            // Answer Object from Record:
            
            $source = $this->owner->getField('ListSource');
            
            if ($source instanceof ListSource) {
                return $source;
            }
            
        } else {
            
            // Answer Object from Database:
            
            return $this->owner->ListSource();
            
        }
    }
    
    /**
     * Answers an array of list sources for a dropdown field.
     *
     * @return array
     */
    private function getListSourceOptions()
    {
        // Create Options Array:
        
        $options = array();
        
        // Find List Source Implementors:
        
        $sources = SiteTree::get()->filter(
            'ClassName',
            ClassInfo::implementorsOf('ListSource')
        )->sort('ClassName');
        
        // Define Options Array:
        
        foreach ($sources as $source) {
            $options[$source->ID] = $source->Title . " (" . $source->i18n_singular_name() . ")";
        }
        
        // Answer Options Array:
        
        return $options;
    }
    
    /**
     * Sorts the given list of items.
     *
     * @param SS_List $list
     * @return SS_List
     */
    private function getSortedListItems(SS_List $list)
    {
        if ($this->owner->SortItemsBy == 'Random') {
            
            if ($list instanceof DataList) {
                
                return $list->sort('RAND()');
                
            } elseif ($list instanceof ArrayList) {
                
                $elements = $list->toArray();
                
                shuffle($elements);
                
                return ArrayList::create($elements);
                
            }
            
        } elseif ($this->owner->SortItemsBy == 'Custom' && $this->owner->CustomSort) {
            
            return $list->sort(Convert::raw2sql($this->owner->CustomSort));
            
        }
        
        return $list;
    }
}
