<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

namespace dao;

class transactions extends _dao {
	protected $_db_name = 'transactions';

	const gst_remitted = 1;
	const gst_paid = 2;

	function flagRemitedGST( $transaction) {
		$a = [
			'gst_transaction' => $transaction,
			'glt_gst_remit' => self::gst_paid

		];

		$this->Update( $a,
			sprintf('WHERE glt_gst_remit = %d', self::gst_remitted)

		);

	}

	function getBalance( dto\dto $gl) {
		$sql = sprintf( "SELECT
					sum(glt_value) balance
				FROM
				 	transactions
				WHERE
				 	glt_code = '%s'", $gl->gl_code);

		if ( $res = $this->Result( $sql)) {
			if ( $dto = $res->dto()) {
				if ( $gl->gl_code == \config::gl_gst) {
					if ( $res = $this->Result('SELECT sum( glt_gst) gst from transactions')) {
						if ( $_ = $res->dto()) {
							$dto->balance += $_->gst;

						}

					}

				}

				return ( $dto);

			}

		}

		return false;

	}

	function getGSTRange( $start, $end, $paid = false) {
		$where = [
			sprintf( "t.glt_date BETWEEN '%s' AND '%s'", $start, $end),
			"t.glt_gst <> 0"
		];

		if ( !$paid) {
			$where[] = sprintf( "t.glt_gst_remit < %s", self::gst_paid);

		}

		$sql = sprintf( "SELECT
				t.*,
				l.gl_description,
				l.gl_type
			FROM
				`transactions` t
				LEFT JOIN
					`ledger` l ON l.gl_code = t.glt_code
			WHERE %s
			ORDER BY
				gl_type ASC, glt_code ASC, glt_date DESC", implode( ' AND ', $where));

		if ( $res = $this->Result( $sql)) {
			return ( $res->dtoSet());

		}

		return ( false);

	}

	function getGSTRemited() {
		$sql = sprintf('SELECT
			 	l.gl_code,
				CASE l.gl_type
				 	WHEN "a" THEN "output"
					ELSE "input"
				END type,
				t.glt_value,
				t.glt_gst
			FROM transactions t
			 	LEFT JOIN
				 	ledger l ON l.gl_code = t.glt_code
			WHERE
			 	glt_gst_remit = 1
			GROUP BY
				CASE l.gl_type
				 	WHEN "a" THEN "output"
					ELSE "input"
				END
			ORDER BY
			 	l.gl_type');

		if ( $res = $this->Result( $sql)) {
			$ret = (object)[
				'output' => 0,
				'outputGST' => 0,
				'input' => 0,
				'inputGST' => 0,
				'total' => 0,
				'totalGST' => 0

			];

			$res->dtoSet( function( $dto) use ( $ret) {
				if ( 'output' == $dto->type) {
					$ret->output -= $dto->glt_value;
					$ret->total -= $dto->glt_value;
					$ret->outputGST -= $dto->glt_gst;
					$ret->totalGST -= $dto->glt_gst;

				}
				else {
					$ret->input += $dto->glt_value;
					$ret->total -= $dto->glt_value;
					$ret->inputGST += $dto->glt_gst;
					$ret->totalGST -= $dto->glt_gst;

				}

			});

			return $ret;

		}

		return ( false);

	}

	function getRange( $start, $end) {
		$sql = sprintf( "SELECT
				t.*,
				l.gl_description
			FROM
				`transactions` t
				LEFT JOIN
					`ledger` l ON l.gl_code = t.glt_code
			WHERE
				glt_date BETWEEN '%s' AND '%s'
			ORDER BY
				glt_code ASC, glt_date DESC", $start, $end);

		if ( $res = $this->Result( $sql)) {
			return ( $res->dtoSet());

		}

		return ( false);

	}

	function getTransactionsByLedger( dto\dto $gl) {
		$sql = sprintf( "SELECT
				t.*,
				l.gl_description
			FROM
				`transactions` t
				LEFT JOIN
					`ledger` l ON l.gl_code = t.glt_code
			WHERE
				glt_code = '%s'
			ORDER BY
				glt_date DESC", $gl->gl_code

		);

		if ( $res = $this->Result( $sql)) {
			return $res->dtoSet();

		}

		return false;

	}

}
