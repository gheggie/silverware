<?php

/**
 * An extension of the base rule class for a content rule.
 */
class ContentRule extends BaseRule
{
    private static $singular_name = "Content Rule";
    private static $plural_name   = "Content Rules";
    
    private static $selector = "div.content";
}
