SELECT Month
	, SalesPersonId AS "Top Sales Person Id"
    , Sales AS "Total Sales"
FROM (
	SELECT s.DateOfSale
		, DATE_FORMAT(s.DateOfSale, '%M %Y') AS Month
		, s.SalesPersonId
        , s.UnitsSold * p.SalePrice AS Sales
        , (ROW_NUMBER() OVER (PARTITION BY DATE_FORMAT(s.DateOfSale, '%M %Y') ORDER BY (s.UnitsSold * p.SalePrice) DESC)) AS 'Row'
    FROM Sale s
    INNER JOIN Product p ON p.ProductId = s.ProductId
    WHERE s.UnitsSold > 0
) AS x
WHERE x.Row = 1
GROUP BY Month
ORDER BY DateOfSale ASC