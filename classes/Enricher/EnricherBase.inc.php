<?php
namespace Optimeta\Citations\Enricher;

class EnricherBase
{
    /**
     * Array which holds the parsed citations
     *
     * @var array
     */
    protected $citationsParsed = [];

    /**
     * Array which holds the enriched citations
     *
     * @var array
     */
    protected $citationsEnriched = [];

    /**
     * Constructor
     *
     * @param array $citationsParsed parsed citations
     */
    function __construct(array $citationsParsed = [])
    {
        $this->citationsParsed = $citationsParsed;
    }
}
