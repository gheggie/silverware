<?php

/**
 * An extension of the base rule class for a link rule.
 */
class LinkRule extends BaseRule
{
    private static $singular_name = "Link Rule";
    private static $plural_name   = "Link Rules";
    
    private static $selector = "div.content a";
}
