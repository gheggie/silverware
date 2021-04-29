<?php

/**
 * An extension of the base rule class for a title rule.
 */
class TitleRule extends BaseRule
{
    private static $singular_name = "Title Rule";
    private static $plural_name   = "Title Rules";
    
    private static $selector = "header > h3";
}
