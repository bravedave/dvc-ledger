CREATE TEMPORARY TABLE _t AS
SELECT
	gl_code, gl_description, gl_trading, gl_type, glt_value
FROM ledger l
	LEFT JOIN (
		SELECT glt_code, SUM( glt_value) glt_value
		FROM transactions
		GROUP BY glt_code) t
	ON t.glt_code = l.gl_code
ORDER BY
	gl_trading ASC, gl_type ASC;
