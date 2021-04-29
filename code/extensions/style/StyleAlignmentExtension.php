<?php

/**
 * An extension of the SilverWare style extension class to apply alignment styles to the extended object.
 */
class StyleAlignmentExtension extends SilverWareStyleExtension
{
    private static $db = array(
        'StyleAlignmentWide' => "Enum('Left, Center, Right, Justify', 'Left')",
        'StyleAlignmentNarrow' => "Enum('Left, Center, Right, Justify', 'Left')",
        'StyleAlignmentVertical' => "Enum('Baseline, Top, Middle, Bottom', 'Baseline')"
    );
    
    private static $defaults = array(
        'StyleAlignmentWide' => 'Left',
        'StyleAlignmentNarrow' => 'Left',
        'StyleAlignmentVertical' => 'Baseline'
    );
    
    /**
     * Updates the CMS fields of the extended object.
     *
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        // Update Field Objects:
        
        $fields->addFieldToTab(
            'Root.Style',
            ToggleCompositeField::create(
                'StyleAlignmentToggle',
                _t('StyleAlignmentExtension.ALIGNMENT', 'Alignment'),
                array(
                    DropdownField::create(
                        'StyleAlignmentWide',
                        _t('StyleAlignmentExtension.ALIGNMENTWIDE', 'Alignment (wide)'),
                        $this->owner->dbObject('StyleAlignmentWide')->enumValues()
                    ),
                    DropdownField::create(
                        'StyleAlignmentNarrow',
                        _t('StyleAlignmentExtension.ALIGNMENTNARROW', 'Alignment (narrow)'),
                        $this->owner->dbObject('StyleAlignmentNarrow')->enumValues()
                    ),
                    DropdownField::create(
                        'StyleAlignmentVertical',
                        _t('StyleAlignmentExtension.ALIGNMENTVERTICAL', 'Alignment (vertical)'),
                        $this->owner->dbObject('StyleAlignmentVertical')->enumValues()
                    )
                )
            )
        );
    }
    
    /**
     * Updates the class names of the extended object.
     *
     * @param array $classes
     * @return array
     */
    public function updateClassNames(&$classes)
    {
        foreach ($this->owner->getStyleAlignmentClassNames() as $class) {
            $classes[] = $class;
        }
    }
    
    /**
     * Answers an array of alignment class names.
     *
     * @return array
     */
    public function getStyleAlignmentClassNames()
    {
        return array(
            $this->owner->getStyleAlignmentWideClass(),
            $this->owner->getStyleAlignmentNarrowClass(),
            $this->owner->getStyleAlignmentVerticalClass()
        );
    }
    
    /**
     * Answers the alignment class name for wide devices.
     *
     * @return string
     */
    public function getStyleAlignmentWideClass()
    {
        return strtolower('wide-' . $this->owner->StyleAlignmentWide);
    }
    
    /**
     * Answers the alignment class name for narrow devices.
     *
     * @return string
     */
    public function getStyleAlignmentNarrowClass()
    {
        return strtolower('narrow-' . $this->owner->StyleAlignmentNarrow);
    }
    
    /**
     * Answers the vertical alignment class name.
     *
     * @return string
     */
    public function getStyleAlignmentVerticalClass()
    {
        return strtolower('vertical-' . $this->owner->StyleAlignmentVertical);
    }
}
