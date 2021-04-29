<?php

/**
 * An extension of the ZenValidator regex constraint class for a dd/mm/yyyy date constraint.
 */
class Constraint_date_dmy extends Constraint_regex
{
    /**
     * Constructs the object upon instantiation.
     */
    public function __construct()
    {
        // Construct Parent:
        
        parent::__construct("/^(3[01]|[12][0-9]|0[1-9])\/(1[0-2]|0[1-9])\/[0-9]{4}$/");
        
        // Construct Object:
        
        $this->customMessage = _t(
            'Constraint_date_dmy.DEFAULTMESSAGE',
            'This value does not appear to be a valid date.'
        );
    }
}
