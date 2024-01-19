-- copy citations from publication_settings to citations_extended
INSERT INTO citations_extended (publication_id, parsed_citations)
SELECT ps.publication_id, ps.setting_value
FROM publication_settings AS ps
         LEFT JOIN citations_extended AS ce ON ps.publication_id = ce.publication_id
WHERE ps.setting_name = 'OptimetaCitations__CitationsParsed' AND ps.setting_value <> '[]'
