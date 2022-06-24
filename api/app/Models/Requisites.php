<?php

namespace App\Models;

class Requisites extends BaseModel
{
    protected $table = 'requisites';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id', 'recipient', 'registration_number', 'address', 'country_id', 'bank_name', 'bank_country_id', 'iban', 'account_no', 'swift', 'bank_correspondent',
    ];

    /**
     * Get relation Country
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    /**
     * Get relation applicant Account
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Accounts()
    {
        return $this->belongsTo(Accounts::class, 'account_id', 'id');
    }

    public static function PDFTable($account_id)
    {
        $data = self::all()->where('account_id', $account_id);
        $html = '';
        foreach ($data as $field) {
            $html = '<div class="table-scrollable" style="text-align: center; font-family: Be Vietnam,sans-serif"><h3>Requisites for '.$field->bank_name.'</h3>
                    <table border="1" align="center" width="100%">
                        <tbody id="body"><tr>
                            <td>
                               <p style="font-weight: bold; margin-left: 3px">Recipient</p>
                            </td>
                            <td><p style="margin-left: 3px">'.
                $field->recipient.
                '</p></td>
                </tr>
                <tr>
                    <td>
                       <p style="font-weight: bold; margin-left: 3px">Registration Number</p>
                    </td>
                    <td><p style="margin-left: 3px">'.
                $field->registration_number.
                '</p></td>
                </tr>
                <tr>
                    <td>
                       <p style="font-weight: bold; margin-left: 3px">Address</p>
                    </td>
                    <td><p style="margin-left: 3px">'.
                $field->address.
                '</p></td>
                </tr>
                <tr>
                    <td>
                       <p style="font-weight: bold; margin-left: 3px">Country</p>
                    </td>
                    <td><p style="margin-left: 3px">'.
                Country::query()->where('id', $field->country_id)->value('name').
                '</p></td>
                </tr>
                <tr>
                    <td>
                       <p style="font-weight: bold; margin-left: 3px">Bank Name</p>
                    </td>
                    <td><p style="margin-left: 3px">'.
                $field->bank_name.
                '</p></td>
                </tr>
                <tr>
                    <td>
                       <p style="font-weight: bold; margin-left: 3px">Bank Address</p>
                    </td>
                    <td><p style="margin-left: 3px">'.
                $field->bank_address.
                '</p></td>
                </tr>
                <tr>
                    <td>
                       <p style="font-weight: bold; margin-left: 3px">IBAN/Account No.</p>
                    </td>
                    <td><p style="margin-left: 3px">'.
                $field->iban.
                '</p></td>
                </tr>
                <tr>
                    <td>
                       <p style="font-weight: bold; margin-left: 3px">Swift code</p>
                    </td>
                    <td><p style="margin-left: 3px">'.
                $field->swift.
                '</p></td>
                </tr>
                <tr>
                    <td>
                       <p style="font-weight: bold; margin-left: 3px">Bank address</p>
                    </td>
                    <td><p style="margin-left: 3px">'.
                $field->bank_address.
                '</p></td>
                </tr>
                <tr>
                    <td>
                       <p style="font-weight: bold; margin-left: 3px">Bank country</p>
                    </td>
                    <td><p style="margin-left: 3px">'.
                Country::query()->where('id', $field->bank_country_id)->value('name').
                '</p></td>
                </tr>
               </tbody>
            </table>
        </div>';
        }

        return $html;
    }
}
