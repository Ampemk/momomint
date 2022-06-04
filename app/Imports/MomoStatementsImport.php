<?php

namespace App\Imports;

use App\Models\MomoStatement;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class MomoStatementsImport implements OnEachRow, WithUpserts, WithHeadingRow
{

    public function __construct($account_id, $statement_file_id, $user_id)
    {
        $this->account_id = $account_id;
        $this->user_id = $user_id;
        $this->statement_file_id = $statement_file_id;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function uniqueBy()
    {
        return 'f_id';
    }

    public function onRow(Row $row)
    {

        $transaction_date = date("Y-m-d H:i:s", strtotime($row['transaction_date']));
        return MomoStatement::updateOrCreate(
            ['f_id' => $row['f_id']],
            [
                //
                'transaction_date' => $transaction_date,
                'from_acct' => $row['from_acct'],
                'from_name' => $row['from_name'],
                'from_no' => $row['from_no'],
                'transaction_type' => $row['trans_type'],
                'amount' => $row['amount'],
                'fees' => $row['fees'],
                'e_levy' => $row['e_levy'],
                'bal_before' => $row['bal_before'],
                'bal_after' => $row['bal_after'],
                'to_no' => $row['to_no'],
                'to_name' => $row['to_name'],
                'to_acct' => $row['to_acct'],
                'f_id' => $row['f_id'],
                'ref' => $row['ref'],
                'ova' => $row['ova'],
                'account_id' => $this->account_id,
                'user_id' => $this->user_id,
                'statement_file_id' => $this->statement_file_id
            ]
        );
    }


    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function hasFID($fID)
    {

        if (MomoStatement::where('f_id', $fID)->exists()) {
            return true;
        }

        return false;
    }


    public function hasFIDAndDate($fID, $date)
    {

        if (MomoStatement::where('f_id', $fID)->where('transaction_date', $date)->exists()) {
            return true;
        }

        return false;
    }
}
