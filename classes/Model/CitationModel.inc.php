<?php
/**
 * @desc Works are scholarly documents like journal articles, books, datasets, and theses.
 */
namespace Optimeta\Citations\Model;

class CitationModel extends WorkModel
{
    /**
     * @var string
     * @desc The unchanged raw citation
     * @see
     * @example
     */
    public $raw;
}
