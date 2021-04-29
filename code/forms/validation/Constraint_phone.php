<?php

/**
 * An extension of the ZenValidator regex constraint class for a phone number constraint.
 */
class Constraint_phone extends Constraint_regex
{
    /**
     * Constructs the object upon instantiation.
     */
    public function __construct()
    {
        // Construct Parent:
        
        parent::__construct("/^(?=.*[0-9])[- +()0-9]+$/");
        
        // Construct Object:
        
        $this->customMessage = _t(
            'Constraint_phone.DEFAULTMESSAGE',
            'This value does not appear to be a valid phone number.'
        );
    }
}
