<?php

/**
 * An extension of the base rule class for a title text rule.
 */
class TitleTextRule extends BaseRule
{
    private static $singular_name = "Title Text Rule";
    private static $plural_name   = "Title Text Rules";
    
    private static $selector = "header > h3 > span";
}
