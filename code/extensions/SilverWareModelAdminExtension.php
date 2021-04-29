<?php

/**
 * An extension of the extension class which adds custom features to ModelAdmin classes.
 */
class SilverWareModelAdminExtension extends Extension
{
    /**
     * @var string
     */
    private $modelClass;
    
    /**
     * Answers the model admin instance for the specified model class.
     *
     * @param DataObject|string $class
     * @return ModelAdmin
     */
    public function forModel($class)
    {
        // Define Model Class:
        
        $this->modelClass = ($class instanceof DataObject) ? $class->class : $class;
        
        // Answer Extended Object:
        
        return $this->owner;
    }
    
    /**
     * Answers an edit link for the item with the specified ID.
     *
     * @param integer $ID
     * @return string
     */
    public function getItemEditLink($ID)
    {
        return $this->getItemLink($ID, 'edit');
    }
    
    /**
     * Answers an view link for the item with the specified ID.
     *
     * @param integer $ID
     * @return string
     */
    public function getItemViewLink($ID)
    {
        return $this->getItemLink($ID, 'view');
    }
    
    /**
     * Answers the item link with the given ID and action.
     *
     * @param integer $ID
     * @param string $Action
     * @return string
     */
    private function getItemLink($ID, $Action)
    {
        return Controller::join_links(
            $this->owner->config()->url_base,
            $this->owner->config()->url_segment,
            sprintf('%1$s/EditForm/field/%1$s/item/%2$d/%3$s', $this->modelClass, $ID, $Action)
        );
    }
}
