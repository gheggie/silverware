<?php

/**
 * An extension of the extension class which adds status messages to field lists.
 */
class FieldListMessageExtension extends Extension
{
    /**
     * Adds a status message literal field to the extended object.
     *
     * @param string|array $text
     * @param string $type
     * @param string $icon
     * @param string $insertBefore
     */
    public function addStatusMessage($text = null, $type = 'warning', $icon = 'fa-warning', $insertBefore = 'Root')
    {
        if (!is_null($text)) {
            
            if (func_num_args() == 1 && is_array(func_get_arg(0))) {
                
                $message = func_get_arg(0);
                
                if (isset($message['text'])) {
                    $text = $message['text'];
                }
                
                if (isset($message['type'])) {
                    $type = $message['type'];
                }
                
                if (isset($message['icon'])) {
                    $icon = $message['icon'];
                }
                
            }
            
            $this->owner->insertBefore(
                $insertBefore,
                LiteralField::create(
                    'StatusMessageLiteral',
                    sprintf(
                        "<p class=\"message %s\"><i class=\"fa fa-fw %s\"></i> %s</p>",
                        $type,
                        $icon,
                        !is_array($text) ? $text : ''
                    )
                )
            );
            
        }
    }
}
