<?php

/**
 * An extension of the base rule class for an image rule.
 */
class ImageRule extends BaseRule
{
    private static $singular_name = "Image Rule";
    private static $plural_name   = "Image Rules";
    
    private static $selector = "div.content img";
}
