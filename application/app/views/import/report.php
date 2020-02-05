<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

    */

    // sys::dump( $this->data);

    $tot = 0;
    ?>
<div class="table-responsive">
    <table class="table table-sm">
        <thead>
            <tr>
            <td>Date</td>
            <td>Type</td>
            <td>No.</td>
            <td>Name</td>
            <td>Memo/Description</td>
            <td>Split</td>
            <td class="text-right">Amount</td>
            <td class="text-right">Balance</td>
            <td>Account</td>

            </tr>

        </thead>

        <tbody>
        <?php
        foreach ( $this->data->journal as $row) {
            $tot += (float)$row['Amount'];

            print '<tr>';
            printf( '<td>%s</td>', $row['Date']);
            printf( '<td>%s</td>', $row['Transaction Type']);
            printf( '<td>%s</td>', $row['No.']);
            printf( '<td>%s</td>', $row['Name']);
            printf( '<td>%s</td>', $row['Memo/Description']);
            printf( '<td>%s</td>', $row['Split']);
            printf( '<td class="text-right">%s</td>', $row['Amount']);
            printf( '<td class="text-right">%s</td>', $row['Balance']);
            printf( '<td>%s</td>', $row['Account']);

            print '</tr>';

        }
        ?>
        </tbody>

        <tfoot>
            <tr>
                <td colspan="6">&nbsp;</td>
                <td><?= $tot; ?></td>
                <td colspan="2">&nbsp;</td>

            </tr>

        </tfoot>

    </table>

</div>