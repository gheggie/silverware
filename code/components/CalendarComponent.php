<?php

/**
 * An extension of the base component class for a calendar component.
 */
class CalendarComponent extends BaseComponent
{
    private static $singular_name = "Calendar Component";
    private static $plural_name   = "Calendar Components";
    
    private static $description = "Displays items on an interactive calendar";
    
    private static $icon = "silverware/images/icons/components/CalendarComponent.png";
    
    private static $hide_ancestor = "BaseComponent";
    
    private static $allowed_children = "none";
    
    private static $db = array(
        'DefaultDate' => 'Date'
    );
    
    private static $defaults = array(
        
    );
    
    private static $extensions = array(
        'CalendarSourceExtension'
    );
    
    private static $required_css = array(
        'silverware/thirdparty/fullcalendar/fullcalendar.min.css' => 'screen',
        'silverware/thirdparty/fullcalendar/fullcalendar.print.css' => 'print'
    );
    
    private static $required_themed_css = array(
        'calendar-component'
    );
    
    private static $required_js = array(
        'silverware/thirdparty/moment/moment.min.js',
        'silverware/thirdparty/fullcalendar/fullcalendar.min.js'
    );
    
    private static $required_js_templates = array(
        'silverware/javascript/fullcalendar/fullcalendar.init.js'
    );
    
    /**
     * Answers a collection of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Create Main Fields:
        
        $fields->addFieldsToTab(
            'Root.Main',
            array(
                
            )
        );
        
        // Create Options Fields:
        
        $fields->addFieldToTab(
            'Root.Options',
            ToggleCompositeField::create(
                'CalendarComponentOptions',
                $this->i18n_singular_name(),
                array(
                    $default_date = DateField::create(
                        'DefaultDate',
                        _t('CalendarComponent.DEFAULTDATE', 'Default date')
                    )
                )
            )
        );
        
        // Define Options Fields:
        
        $default_date->setConfig('showcalendar', true);
        $default_date->setConfig('jQueryUI.changeMonth', true);
        $default_date->setConfig('jQueryUI.changeYear', true);
        $default_date->setConfig('jQueryUI.yearRange', '-100:+1');
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers a validator for the CMS interface.
     *
     * @return RequiredFields
     */
    public function getCMSValidator()
    {
        return RequiredFields::create(
            array(
                
            )
        );
    }
    
    /**
     * Answers a unique ID for the calendar element.
     *
     * @return string
     */
    public function getCalendarID()
    {
        return $this->getHTMLID() . "_Calendar";
    }
    
    /**
     * Answers the from date for calendar event data.
     *
     * @return Date
     */
    public function getFromDate()
    {
        $time = $this->DefaultDate ? strtotime($this->DefaultDate) : time();
        
        $from = date('Y-m-d', strtotime('first day of previous month', $time));
        
        return DBField::create_field('Date', $from);
    }
    
    /**
     * Answers the to date for calendar event data.
     *
     * @return Date
     */
    public function getToDate()
    {
        $time = $this->DefaultDate ? strtotime($this->DefaultDate) : time();
        
        $to = date('Y-m-d', strtotime('last day of next month', $time));
        
        return DBField::create_field('Date', $to);
    }
    
    /**
     * Answers an array of variables required by the initialisation script.
     *
     * @return array
     */
    public function getJSVars()
    {
        $vars = parent::getJSVars();
        
        $vars['CalendarID'] = $this->getCalendarID();
        
        $vars['json:Config'] = $this->getCalendarConfig();
        
        return $vars;
    }
    
    /**
     * Answers the configuration array for the calendar.
     *
     * @todo Allow customisation of additional features such as header.
     * @return array
     */
    public function getCalendarConfig()
    {
        // Create Config Array:
        
        $config = array();
        
        // Define Config Array:
        
        $config['editable']   = false;
        $config['eventLimit'] = true;
        
        if ($this->DefaultDate) {
            $config['defaultDate'] = $this->DefaultDate;
        }
        
        // Define Event Sources:
        
        $config['eventSources'] = $this->getEventSources()->toNestedArray();
        
        // Answer Config Array:
        
        return $config;
    }
}

/**
 * An extension of the base component controller class for a calendar component.
 */
class CalendarComponent_Controller extends BaseComponent_Controller
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
