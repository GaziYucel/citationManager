<?php

namespace Optimeta\Shared\WikiData\Model;

class Item
{
    /**
     * ID of the item
     * @var string
     */
    public string $id;

    /**
     * Title of the item
     * @var string
     */
    public string $title;

    /**
     * Type of the item
     * @var string
     */
    public string $type = 'item';

    function __construct()
    {

    }
}
