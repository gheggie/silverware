<?php

/**
 * An extension of the base rule class for a link hover rule.
 */
class LinkHoverRule extends BaseRule
{
    private static $singular_name = "Link Hover Rule";
    private static $plural_name   = "Link Hover Rules";
    
    private static $selector = "div.content a:hover";
}
