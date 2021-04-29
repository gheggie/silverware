<?php

/**
 * Interface for classes which provide items for a list component.
 */
interface ListSource
{
    /**
     * Answers a list of items.
     *
     * @return SS_List
     */
    public function getListItems();
}
