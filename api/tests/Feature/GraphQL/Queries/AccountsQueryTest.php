<?php

namespace Tests\Feature\GraphQL\Queries;

use App\Models\AccountClient;
use App\Models\AccountState;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccountsQueryTest extends TestCase
{
    public function testAccountsNoAuth(): void
    {
        $this->graphQL('
            {
                accountList {
                    data {
                      id
                      account_number
                      account_type
                    }
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryAccounts(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query Account($id:ID!){
                    account(id: $id) {
                        id
                    }
                }',
                'variables' => [
                    'id' => (string) $accounts->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonContains([
            'data' => [
                'account' => [
                    'id' => (string) $accounts->id,
                ],
            ],
        ]);
    }

    public function testQueryAccountsOrderBy(): void
    {
        $account = DB::connection('pgsql_test')
            ->table('accounts')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                query {
                    accountList(orderBy: { column: ID, order: ASC }) {
                        data {
                            id
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $account[0]->id,
        ]);
    }

    public function testQueryAccountsFilterByCompanyId(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestAccountListFilters($company_id: Mixed) {
                    accountList(
                        filter: { column: HAS_COMPANY_MIXED_ID_OR_NAME, value: $company_id }
                    ) {
                        data {
                            id
                            account_number
                            account_type
                            account_name
                        }
                    }
                }',
                'variables' => [
                    'company_id' => (string) $accounts->company_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $accounts->id,
            'account_number' => (string) $accounts->account_number,
            'account_type' => (string) $accounts->account_type,
            'account_name' => (string) $accounts->account_name,
        ]);
    }

    public function testQueryAccountsFilterByPaymentProviderId(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query TestAccountListFilters($payment_provider_id: Mixed) {
                    accountList(
                        filter: {
                            column: PAYMENT_PROVIDER_ID
                            value: $payment_provider_id
                        }
                    ) {
                        data {
                            id
                            account_number
                            account_type
                            account_name
                        }
                    }
                }',
                'variables' => [
                    'payment_provider_id' => (string) $accounts->payment_provider_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $accounts->id,
            'account_number' => (string) $accounts->account_number,
            'account_type' => (string) $accounts->account_type,
            'account_name' => (string) $accounts->account_name,
        ]);
    }

    public function testQueryAccountsFilterByOwnerId(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestAccountListFilters($owner_id: Mixed) {
                    accountList(
                        filter: { column: HAS_OWNER_MIXED_ID_OR_FULLNAME, value: $owner_id }
                    ) {
                        data {
                            id
                            account_number
                            account_type
                            account_name
                        }
                    }
                }',
                'variables' => [
                    'owner_id' => (string) $accounts->owner_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $accounts->id,
            'account_number' => (string) $accounts->account_number,
            'account_type' => (string) $accounts->account_type,
            'account_name' => (string) $accounts->account_name,
        ]);
    }

    public function testQueryAccountsFilterByAccountNumber(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestAccountListFilters($account_number: Mixed) {
                    accountList(
                        filter: {
                            column: MIXED_ACCOUNT_NUMBER_OR_ACCOUNT_NAME
                            operator: ILIKE
                            value: $account_number
                        }
                    ) {
                    data {
                        id
                        account_number
                        account_type
                        account_name
                    }
                }
                }',
                'variables' => [
                    'account_number' => (string) $accounts->account_number,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $accounts->id,
            'account_number' => (string) $accounts->account_number,
            'account_type' => (string) $accounts->account_type,
            'account_name' => (string) $accounts->account_name,
        ]);
    }

    public function testQueryAccountsFilterByCurrencyId(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestAccountListFilters($currency_id: Mixed) {
                    accountList(
                        filter: {
                            column: CURRENCY_ID
                            value: $currency_id
                        }
                    ) {
                        data {
                            id
                            account_number
                            account_type
                            account_name
                        }
                    }
                }',
                'variables' => [
                    'currency_id' => (string) $accounts->currency_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $accounts->id,
            'account_number' => (string) $accounts->account_number,
            'account_type' => (string) $accounts->account_type,
            'account_name' => (string) $accounts->account_name,
        ]);
    }

    public function testQueryAccountsFilterByGroupRoleId(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestAccountListFilters($group_role: Mixed) {
                    accountList(
                        filter: {
                            column: HAS_GROUP_ROLE_MIXED_ID_OR_NAME
                            value: $group_role
                        }
                    ) {
                        data {
                            id
                            account_number
                            account_type
                            account_name
                        }
                    }
                }',
                'variables' => [
                    'group_role' => (string) $accounts->group_role_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $accounts->id,
            'account_number' => (string) $accounts->account_number,
            'account_type' => (string) $accounts->account_type,
            'account_name' => (string) $accounts->account_name,
        ]);
    }

    public function testQueryAccountsFilterByGroupTypeId(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestAccountListFilters($group_type: Mixed) {
                    accountList(
                        filter: {
                            column: GROUP_TYPE_ID
                            value: $group_type
                        }
                    ) {
                        data {
                            id
                            account_number
                            account_type
                            account_name
                        }
                    }
                }',
                'variables' => [
                    'group_type' => (string) $accounts->group_type_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $accounts->id,
            'account_number' => (string) $accounts->account_number,
            'account_type' => (string) $accounts->account_type,
            'account_name' => (string) $accounts->account_name,
        ]);
    }

    public function testQueryAccountsFilterByMemberId(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestAccountListFilters($member_id: Mixed) {
                    accountList(
                        filter: {
                            column: HAS_MEMBER_MIXED_ID_OR_FULLNAME
                            value: $member_id
                        }
                    ) {
                        data {
                            id
                            account_number
                            account_type
                            account_name
                        }
                    }
                }',
                'variables' => [
                    'member_id' => (string) $accounts->member_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $accounts->id,
            'account_number' => (string) $accounts->account_number,
            'account_type' => (string) $accounts->account_type,
            'account_name' => (string) $accounts->account_name,
        ]);
    }

    public function testQueryAccountsFilterByCommissionTemplateId(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestAccountListFilters($commission_template: Mixed) {
                    accountList(
                        filter: {
                            column: HAS_COMMISSION_TEMPLATE_MIXED_ID_OR_FULLNAME
                            value: $commission_template
                        }
                    ) {
                        data {
                            id
                            account_number
                            account_type
                            account_name
                        }
                    }
                }',
                'variables' => [
                    'commission_template' => (string) $accounts->commission_template_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $accounts->id,
            'account_number' => (string) $accounts->account_number,
            'account_type' => (string) $accounts->account_type,
            'account_name' => (string) $accounts->account_name,
        ]);
    }

    public function testQueryAccountsFilterByAccountStateId(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestAccountListFilters($account_state: Mixed) {
                    accountList(
                        filter: {
                            column: ACCOUNT_STATE_ID
                            value: $account_state
                        }
                    ) {
                        data {
                            id
                            account_number
                            account_type
                            account_name
                        }
                    }
                }',
                'variables' => [
                    'account_state' => (string) $accounts->account_state_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $accounts->id,
            'account_number' => (string) $accounts->account_number,
            'account_type' => (string) $accounts->account_type,
            'account_name' => (string) $accounts->account_name,
        ]);
    }

    public function testQueryAccountsFilterByPaymentSystemId(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query TestAccountListFilters($payment_system_id: Mixed) {
                    accountList(
                        filter: {
                            column: HAS_PAYMENT_SYSTEM_MIXED_ID_OR_NAME
                            value: $payment_system_id
                        }
                    ) {
                        data {
                            id
                            account_number
                            account_type
                            account_name
                        }
                    }
                }',
                'variables' => [
                    'payment_system_id' => (string) $accounts->payment_system_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $accounts->id,
            'account_number' => (string) $accounts->account_number,
            'account_type' => (string) $accounts->account_type,
            'account_name' => (string) $accounts->account_name,
        ]);
    }

    public function testQueryAccountsFilterByIsPrimary(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '{
                    accountList(
                        filter: {
                            column: IS_PRIMARY
                            value: true
                        }
                    ) {
                        data {
                            id
                            account_number
                            account_type
                            account_name
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $accounts[0]->id,
            'account_number' => (string) $accounts[0]->account_number,
            'account_type' => (string) $accounts[0]->account_type,
            'account_name' => (string) $accounts[0]->account_name,
        ]);
    }

    public function testQueryAccountsFilterByCurrentBalance(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                {
                    accountList(
                        filter: {
                            column: CURRENT_BALANCE
                            value: 10000
                        }
                    ) {
                        data {
                            id
                            account_number
                            account_type
                            account_name
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $accounts->id,
            'account_number' => (string) $accounts->account_number,
            'account_type' => (string) $accounts->account_type,
            'account_name' => (string) $accounts->account_name,
        ]);
    }

    public function testQueryAccountsFilterByReservedBalance(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                {
                    accountList(
                        filter: {
                            column: RESERVED_BALANCE
                            value: 5000
                        }
                    ) {
                        data {
                            id
                            account_number
                            account_type
                            account_name
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $accounts->id,
            'account_number' => (string) $accounts->account_number,
            'account_type' => (string) $accounts->account_type,
            'account_name' => (string) $accounts->account_name,
        ]);
    }

    public function testQueryAccountsFilterByAvailableBalance(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                {
                    accountList(
                        filter: {
                            column: AVAILABLE_BALANCE
                            value: 10000
                        }
                    ) {
                        data {
                            id
                            account_number
                            account_type
                            account_name
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $accounts->id,
            'account_number' => (string) $accounts->account_number,
            'account_type' => (string) $accounts->account_type,
            'account_name' => (string) $accounts->account_name,
        ]);
    }

    public function testQueryAccountsFilterById(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query TestAccountListFilters($id: Mixed) {
                    accountList(
                        filter: {
                            column: ID
                            value: $id
                        }
                    ) {
                        data {
                            id
                            account_number
                            account_type
                            account_name
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $accounts->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $accounts->id,
            'account_number' => (string) $accounts->account_number,
            'account_type' => (string) $accounts->account_type,
            'account_name' => (string) $accounts->account_name,
        ]);
    }

    public function testQueryAccountsFilterByClientable(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->where('client_id', 1)
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query TestAccountListFilters($id: Mixed) {
                    accountList(
                        filter: {
                            column: HAS_CLIENTABLE_MIXED_ID_OR_FULLNAME_OR_NAME
                            value: $id
                        }
                    ) {
                        data {
                            id
                            account_number
                            account_type
                            account_name
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $accounts->client_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $accounts->id,
            'account_number' => (string) $accounts->account_number,
            'account_type' => (string) $accounts->account_type,
            'account_name' => (string) $accounts->account_name,
        ]);
    }

    public function testQueryAccountsFilterByIbanProviderId(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query TestAccountListFilters($iban_provider_id: Mixed) {
                    accountList(
                        filter: {
                            column: IBAN_PROVIDER_ID
                            value: $iban_provider_id
                        }
                    ) {
                        data {
                            id
                            account_number
                            account_type
                            account_name
                        }
                    }
                }',
                'variables' => [
                    'iban_provider_id' => (string) $accounts->iban_provider_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $accounts->id,
            'account_number' => (string) $accounts->account_number,
            'account_type' => (string) $accounts->account_type,
            'account_name' => (string) $accounts->account_name,
        ]);
    }

    public function testDownloadAccountListFilterById(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                    query TestDownloadAccountListFilters($id: Mixed) {
                        downloadAccountList(
                            type: Pdf
                            filter: {
                                column: ID
                                value: $id
                            }
                        ) {
                            ... on RawFile {
                                base64
                            }
                        }
                    }',
                'variables' => [
                    'id' => (string) $accounts->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonStructure([
            'data' => [
                'downloadAccountList' => [
                    'base64',
                ],
            ],
        ]);
    }

    public function testDownloadAccountListFilterByCompanyMixedIdOrName(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                    query TestDownloadAccountListFilters($id: Mixed) {
                        downloadAccountList(
                            type: Pdf
                            filter: {
                                column: HAS_COMPANY_MIXED_ID_OR_NAME
                                value: $id
                            }
                        ) {
                            ... on RawFile {
                                base64
                            }
                        }
                    }',
                'variables' => [
                    'id' => (string) $accounts->company_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonStructure([
            'data' => [
                'downloadAccountList' => [
                    'base64',
                ],
            ],
        ]);
    }

    public function testDownloadAccountListFilterByPaymentSystemMixedIdOrName(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                    query TestDownloadAccountListFilters($id: Mixed) {
                        downloadAccountList(
                            type: Pdf
                            filter: {
                                column: HAS_PAYMENT_SYSTEM_MIXED_ID_OR_NAME
                                value: $id
                            }
                        ) {
                            ... on RawFile {
                                base64
                            }
                        }
                    }',
                'variables' => [
                    'id' => (string) $accounts->payment_system_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonStructure([
            'data' => [
                'downloadAccountList' => [
                    'base64',
                ],
            ],
        ]);
    }

    public function testDownloadAccountListFilterByOwnerMixedIdOrFullName(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                    query TestDownloadAccountListFilters($id: Mixed) {
                        downloadAccountList(
                            type: Pdf
                            filter: {
                                column: HAS_OWNER_MIXED_ID_OR_FULLNAME
                                value: $id
                            }
                        ) {
                            ... on RawFile {
                                base64
                            }
                        }
                    }',
                'variables' => [
                    'id' => (string) $accounts->owner_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonStructure([
            'data' => [
                'downloadAccountList' => [
                    'base64',
                ],
            ],
        ]);
    }

    public function testDownloadAccountListFilterByAccountMixedNumberOrName(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                    query TestDownloadAccountListFilters($id: Mixed) {
                        downloadAccountList(
                            type: Pdf
                            filter: {
                                column: MIXED_ACCOUNT_NUMBER_OR_ACCOUNT_NAME
                                operator: ILIKE
                                value: $id
                            }
                        ) {
                            ... on RawFile {
                                base64
                            }
                        }
                    }',
                'variables' => [
                    'id' => (string) $accounts->account_number,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonStructure([
            'data' => [
                'downloadAccountList' => [
                    'base64',
                ],
            ],
        ]);
    }

    public function testDownloadAccountListFilterByCurrencyId(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                    query TestDownloadAccountListFilters($id: Mixed) {
                        downloadAccountList(
                            type: Pdf
                            filter: {
                                column: CURRENCY_ID
                                value: $id
                            }
                        ) {
                            ... on RawFile {
                                base64
                            }
                        }
                    }',
                'variables' => [
                    'id' => (string) $accounts->currency_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonStructure([
            'data' => [
                'downloadAccountList' => [
                    'base64',
                ],
            ],
        ]);
    }

    public function testDownloadAccountListFilterByGroupRoleMixedIdOrName(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                    query TestDownloadAccountListFilters($id: Mixed) {
                        downloadAccountList(
                            type: Pdf
                            filter: {
                                column: HAS_GROUP_ROLE_MIXED_ID_OR_NAME
                                value: $id
                            }
                        ) {
                            ... on RawFile {
                                base64
                            }
                        }
                    }',
                'variables' => [
                    'id' => (string) $accounts->group_role_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonStructure([
            'data' => [
                'downloadAccountList' => [
                    'base64',
                ],
            ],
        ]);
    }

    public function testDownloadAccountListFilterByGroupTypeId(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                    query TestDownloadAccountListFilters($id: Mixed) {
                        downloadAccountList(
                            type: Pdf
                            filter: {
                                column: GROUP_TYPE_ID
                                value: $id
                            }
                        ) {
                            ... on RawFile {
                                base64
                            }
                        }
                    }',
                'variables' => [
                    'id' => (string) $accounts->group_type_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonStructure([
            'data' => [
                'downloadAccountList' => [
                    'base64',
                ],
            ],
        ]);
    }

    public function testDownloadAccountListFilterByMemberMixedIdOrFullname(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                    query TestDownloadAccountListFilters($id: Mixed) {
                        downloadAccountList(
                            type: Pdf
                            filter: {
                                column: HAS_MEMBER_MIXED_ID_OR_FULLNAME
                                value: $id
                            }
                        ) {
                            ... on RawFile {
                                base64
                            }
                        }
                    }',
                'variables' => [
                    'id' => (string) $accounts->member_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonStructure([
            'data' => [
                'downloadAccountList' => [
                    'base64',
                ],
            ],
        ]);
    }

    public function testDownloadAccountListFilterByCommissionTemplateMixedIdOrFullname(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                    query TestDownloadAccountListFilters($id: Mixed) {
                        downloadAccountList(
                            type: Pdf
                            filter: {
                                column: HAS_COMMISSION_TEMPLATE_MIXED_ID_OR_FULLNAME
                                value: $id
                            }
                        ) {
                            ... on RawFile {
                                base64
                            }
                        }
                    }',
                'variables' => [
                    'id' => (string) $accounts->commission_template_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonStructure([
            'data' => [
                'downloadAccountList' => [
                    'base64',
                ],
            ],
        ]);
    }

    public function testDownloadAccountListFilterByAccountStateId(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                    query TestDownloadAccountListFilters($id: Mixed) {
                        downloadAccountList(
                            type: Pdf
                            filter: {
                                column: ACCOUNT_STATE_ID
                                value: $id
                            }
                        ) {
                            ... on RawFile {
                                base64
                            }
                        }
                    }',
                'variables' => [
                    'id' => (string) $accounts->account_state_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonStructure([
            'data' => [
                'downloadAccountList' => [
                    'base64',
                ],
            ],
        ]);
    }

    public function testDownloadAccountListFilterByClientableMixedIdOrFullname(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                    query TestDownloadAccountListFilters($id: Mixed) {
                        downloadAccountList(
                            type: Pdf
                            filter: {
                                column: HAS_CLIENTABLE_MIXED_ID_OR_FULLNAME
                                value: $id
                            }
                        ) {
                            ... on RawFile {
                                base64
                            }
                        }
                    }',
                'variables' => [
                    'id' => (string) $accounts->client_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonStructure([
            'data' => [
                'downloadAccountList' => [
                    'base64',
                ],
            ],
        ]);
    }

    public function testDownloadAccountListFilterByPaymentProviderId(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                    query TestDownloadAccountListFilters($id: Mixed) {
                        downloadAccountList(
                            type: Pdf
                            filter: {
                                column: PAYMENT_PROVIDER_ID
                                value: $id
                            }
                        ) {
                            ... on RawFile {
                                base64
                            }
                        }
                    }',
                'variables' => [
                    'id' => (string) $accounts->payment_provider_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonStructure([
            'data' => [
                'downloadAccountList' => [
                    'base64',
                ],
            ],
        ]);
    }

    public function testDownloadAccountListFilterByIbanProviderId(): void
    {
        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                    query TestDownloadAccountListFilters($id: Mixed) {
                        downloadAccountList(
                            type: Pdf
                            filter: {
                                column: IBAN_PROVIDER_ID
                                value: $id
                            }
                        ) {
                            ... on RawFile {
                                base64
                            }
                        }
                    }',
                'variables' => [
                    'id' => (string) $accounts->iban_provider_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonStructure([
            'data' => [
                'downloadAccountList' => [
                    'base64',
                ],
            ],
        ]);
    }

    public function testDownloadAccountListFilterByCurrentBalance(): void
    {
        $this->postGraphQL(
            [
                'query' => '
                    {
                        downloadAccountList(
                            type: Pdf
                            filter: {
                                column: CURRENT_BALANCE
                                value: 10000
                            }
                        ) {
                            ... on RawFile {
                                base64
                            }
                        }
                    }'
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonStructure([
            'data' => [
                'downloadAccountList' => [
                    'base64',
                ],
            ],
        ]);
    }

    public function testDownloadAccountListFilterByAvailableBalance(): void
    {
        $this->postGraphQL(
            [
                'query' => '
                    {
                        downloadAccountList(
                            type: Pdf
                            filter: {
                                column: AVAILABLE_BALANCE
                                value: 5000
                            }
                        ) {
                            ... on RawFile {
                                base64
                            }
                        }
                    }'
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonStructure([
            'data' => [
                'downloadAccountList' => [
                    'base64',
                ],
            ],
        ]);
    }

    public function testDownloadAccountListFilterByReservedBalance(): void
    {
        $this->postGraphQL(
            [
                'query' => '
                    {
                        downloadAccountList(
                            type: Pdf
                            filter: {
                                column: RESERVED_BALANCE
                                value: 10000
                            }
                        ) {
                            ... on RawFile {
                                base64
                            }
                        }
                    }'
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonStructure([
            'data' => [
                'downloadAccountList' => [
                    'base64',
                ],
            ],
        ]);
    }

    public function testDownloadAccountListFilterByIsPrimary(): void
    {
        $this->postGraphQL(
            [
                'query' => '
                    {
                        downloadAccountList(
                            type: Pdf
                            filter: {
                                column: IS_PRIMARY
                                value: true
                            }
                        ) {
                            ... on RawFile {
                                base64
                            }
                        }
                    }'
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        )->seeJsonStructure([
            'data' => [
                'downloadAccountList' => [
                    'base64',
                ],
            ],
        ]);
    }

    public function testAcountStatesQuery(): void
    {
        $expected = AccountState::all()->map(function ($state) {
            return [
                'id' => (string) $state->id,
                'name' => $state->name,
                'active' => (bool) $state->active,
            ];
        })->toArray();

        $response = $this->postGraphQL(
            [
                'query' => '
                    {
                        accountStates {
                            id
                            name
                            active
                        }
                }'
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        );

        $response->seeJson([
            'data' => [
                'accountStates' => $expected,
            ],
        ]);
    }

    public function testClientListQuery(): void
    {
        $expected = AccountClient::all()
            ->map(function ($accountClient) {
                return [
                    'id' => (string) $accountClient->id,
                    'client' => $accountClient->client_type
                        ? ['__typename' => $accountClient->client_type]
                        : null,
                ];
            })
            ->toArray();

        $response = $this->postGraphQL(
            [
                'query' => '
                    {
                        clientList {
                            id
                            client {
                              __typename
                            }
                        }
                }'
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        );

        $response->seeJson([
            'data' => [
                'clientList' => $expected,
            ],
        ]);
    }

    public function testClientListQueryByGroupTypeId(): void
    {
        $expected = AccountClient::where('client_type', 'ApplicantCompany')
            ->get()
            ->map(function ($accountClient) {
                return [
                    'id' => (string) $accountClient->id,
                    'client' => $accountClient->client_type
                        ? ['__typename' => $accountClient->client_type]
                        : null,
                ];
            })
            ->toArray();

        $response = $this->postGraphQL(
            [
                'query' => '
                    {
                        clientList (group_type: 2) {
                            id
                            client {
                              __typename
                            }
                        }
                }'
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        );

        $response->seeJson([
            'data' => [
                'clientList' => $expected,
            ],
        ]);
    }

    public function testClientListQueryByCompanyId(): void
    {
        $args['company_id'] = 1;
        $expected = AccountClient::where(function (Builder $q) use ($args) {
                 $q->whereHas('individual', function (Builder $q) use ($args) {
                     $q->where('company_id', $args['company_id']);
                 })->orWhereHas('company', function (Builder $q) use ($args) {
                     $q->where('company_id', $args['company_id']);
                 });
             })
            ->get()
            ->map(function ($accountClient) {
                return [
                    'id' => (string) $accountClient->id,
                    'client' => $accountClient->client_type
                        ? ['__typename' => $accountClient->client_type]
                        : null,
                ];
            })
            ->toArray();

        $response = $this->postGraphQL(
            [
                'query' => '
                    {
                        clientList (company_id: 1) {
                            id
                            client {
                              __typename
                            }
                        }
                }'
            ],
            [
                'Authorization' => 'Bearer ' .  $this->login(),
            ]
        );

        $response->seeJson([
            'data' => [
                'clientList' => $expected,
            ],
        ]);
    }
}
