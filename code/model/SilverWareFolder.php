<?php

/**
 * An extension of the site tree class for a SilverWare folder.
 */
class SilverWareFolder extends SiteTree implements PermissionProvider
{
    private static $singular_name = "Folder";
    private static $plural_name   = "Folders";
    
    private static $description = "Abstract parent class of objects which behave as folders";
    
    private static $defaults = array(
        'ShowInMenus' => 0,
        'ShowInSearch' => 0
    );
    
    /**
     * Creates the default SilverWare folder objects (if required).
     *
     * @return boolean
     */
    public static function create_folders()
    {
        // Create Status:
        
        $status = false;
        
        // Iterate Default Folders:
        
        foreach (self::config()->default_folders as $class) {
            
            if (!self::find($class)) {
                
                // Create Folder:
                
                $folder = Injector::inst()->create($class);
                
                // Define Folder:
                
                $folder->Sort = 0;
                
                // Write and Publish Folder:
                
                $folder->write();
                $folder->publish('Stage', 'Live');
                $folder->flushCache();
                
                // Show Alteration Message:
                
                DB::alteration_message(sprintf('Creating %s (%s)', $folder->Title, $class), 'created');
                
                // Update Status:
                
                $status = true;
                
            }
            
        }
        
        // Answer Status:
        
        return $status;
    }
    
    /**
     * Answers the instance of the folder (only one instance should exist).
     *
     * @return SilverWareFolder
     */
    public static function find($class = null)
    {
        return self::get($class ? $class : get_called_class())->first();
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
        
        // Remove Field Objects:
        
        $fields->removeFieldsFromTab('Root.Main', array('Content', 'Metadata'));
        
        // Update Field Objects:
        
        $fields->dataFieldByName('Title')->setTitle(_t('SilverWareFolder.FOLDERNAME', 'Folder name'));
        $fields->dataFieldByName('MenuTitle')->addExtraClass('hidden');
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers true if the member can create a new instance of the receiver.
     *
     * @param Member $member
     * @return boolean
     */
    public function canCreate($member = null)
    {
        return (!self::get()->exists());
    }
    
    /**
     * Answers true if the member can view the receiver.
     *
     * @param Member $member
     * @return boolean
     */
    public function canView($member = null)
    {
        return Permission::checkMember($member, array('ADMIN', 'SILVERWARE_FOLDER_VIEW'));
    }
    
    /**
     * Answers true if the member can edit the receiver.
     *
     * @param Member $member
     * @return boolean
     */
    public function canEdit($member = null)
    {
        return Permission::checkMember($member, array('ADMIN', 'SILVERWARE_FOLDER_EDIT'));
    }
    
    /**
     * Answers true if the member can delete the receiver.
     *
     * @param Member $member
     * @return boolean
     */
    public function canDelete($member = null)
    {
        return Permission::checkMember($member, array('ADMIN', 'SILVERWARE_FOLDER_DELETE'));
    }
    
    /**
     * Provides the permissions for the security interface.
     *
     * @return array
     */
    public function providePermissions()
    {
        return array(
            
            'SILVERWARE_FOLDER_VIEW' => array(
                'category' => _t('SilverWareFolder.PERMISSION_CATEGORY', 'SilverWare folders'),
                'name' => _t('SilverWareFolder.PERMISSION_VIEW_NAME', 'View any folder'),
                'help' => _t('SilverWareFolder.PERMISSION_VIEW_HELP', 'Ability to view any folder.'),
                'sort' => 100
            ),
            
            'SILVERWARE_FOLDER_EDIT' => array(
                'category' => _t('SilverWareFolder.PERMISSION_CATEGORY', 'SilverWare folders'),
                'name' => _t('SilverWareFolder.PERMISSION_EDIT_NAME', 'Edit any folder'),
                'help' => _t('SilverWareFolder.PERMISSION_EDIT_HELP', 'Ability to edit any folder.'),
                'sort' => 200
            ),
            
            'SILVERWARE_FOLDER_DELETE' => array(
                'category' => _t('SilverWareFolder.PERMISSION_CATEGORY', 'SilverWare folders'),
                'name' => _t('SilverWareFolder.PERMISSION_DELETE_NAME', 'Delete any folder'),
                'help' => _t('SilverWareFolder.PERMISSION_DELETE_HELP', 'Ability to delete any folder.'),
                'sort' => 300
            )
            
        );
    }
    
    /**
     * Answers a string of CSS classes to apply to the receiver in the CMS tree.
     *
     * @param string $numChildrenMethod
     * @return string
     */
    public function CMSTreeClasses($numChildrenMethod = 'numChildren')
    {
        $classes = parent::CMSTreeClasses($numChildrenMethod);
        
        $classes .= " class-" . __CLASS__;
        
        return $classes;
    }
}

/**
 * An extension of the content controller class for a SilverWare folder.
 */
class SilverWareFolder_Controller extends ContentController
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
