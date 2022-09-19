<?php

namespace Tests\Feature\GraphQL\Queries;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccountsQueryTest extends TestCase
{

    public function testQueryAccounts(): void
    {
        $this->login();

        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->graphQL('
            query Account($id:ID!){
                account(id: $id) {
                    id
                }
            }
        ', [
            'id' => strval($accounts->id),
        ])->seeJson([
            'data' => [
                'account' => [
                    'id' => strval($accounts->id),
                ],
            ],
        ]);
    }

    public function testQueryAccountsOrderBy(): void
    {
        $this->login();

        $account = DB::connection('pgsql_test')
            ->table('accounts')
            ->orderBy('id', 'DESC')
            ->take(1)
            ->get();

        $this->graphQL('
        query {
            accounts(orderBy: { column: ID, order: DESC }) {
                data {
                    id
                }
                }
        }')->seeJsonContains([
            [
                'id' => strval($account[0]->id),
            ],
        ]);
    }

    public function testQueryAccountsWhere(): void
    {
        $this->login();

        $account = DB::connection('pgsql_test')
            ->table('accounts')
            ->orderBy('id', 'DESC')
            ->take(1)
            ->get();

        $this->graphQL('
        query Accounts($owner: String) {
            accounts (query:{owner:$owner})
                {
                data{
                    id
                }
                }
        }', [
            'owner' => strval(1),
        ])->seeJsonContains([
            [
                'id' => strval($account[0]->id),
            ],
        ]);
    }

    public function testQueryAccountsFilterByCompanyId(): void
    {
        $this->login();

        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->graphQL('
            query TestAccountListFilters($company_id: Mixed) {
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
            }
        ', [
            'company_id' => strval($accounts->company_id),
        ])->seeJsonContains([
            'id' => strval($accounts->id),
            'account_number' => strval($accounts->account_number),
            'account_type' => strval($accounts->account_type),
            'account_name' => strval($accounts->account_name),
        ]);
    }

    public function testQueryAccountsFilterByPaymentProviderId(): void
    {
        $this->login();

        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->graphQL('
            query TestAccountListFilters($payment_provider_id: Mixed) {
                accountList(
                    filter: {
                        column: HAS_PAYMENT_PROVIDER_MIXED_ID_OR_NAME
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
            }
        ', [
            'payment_provider_id' => strval($accounts->payment_provider_id),
        ])->seeJsonContains([
            'id' => strval($accounts->id),
            'account_number' => strval($accounts->account_number),
            'account_type' => strval($accounts->account_type),
            'account_name' => strval($accounts->account_name),
        ]);
    }

    public function testQueryAccountsFilterByOwnerId(): void
    {
        $this->login();

        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->graphQL('
            query TestAccountListFilters($owner_id: Mixed) {
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
            }
        ', [
            'owner_id' => strval($accounts->owner_id),
        ])->seeJsonContains([
            'id' => strval($accounts->id),
            'account_number' => strval($accounts->account_number),
            'account_type' => strval($accounts->account_type),
            'account_name' => strval($accounts->account_name),
        ]);
    }

    public function testQueryAccountsFilterByAccountNumber(): void
    {
        $this->login();

        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->graphQL('
            query TestAccountListFilters($account_number: Mixed) {
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
            }
        ', [
            'account_number' => strval($accounts->account_number),
        ])->seeJsonContains([
            'id' => strval($accounts->id),
            'account_number' => strval($accounts->account_number),
            'account_type' => strval($accounts->account_type),
            'account_name' => strval($accounts->account_name),
        ]);
    }

    public function testQueryAccountsFilterByCurrencyId(): void
    {
        $this->login();

        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->graphQL('
            query TestAccountListFilters($currency_id: Mixed) {
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
            }
        ', [
            'currency_id' => strval($accounts->currency_id),
        ])->seeJsonContains([
            'id' => strval($accounts->id),
            'account_number' => strval($accounts->account_number),
            'account_type' => strval($accounts->account_type),
            'account_name' => strval($accounts->account_name),
        ]);
    }

    public function testQueryAccountsFilterByGroupRoleId(): void
    {
        $this->login();

        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->graphQL('
            query TestAccountListFilters($group_role: Mixed) {
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
            }
        ', [
            'group_role' => strval($accounts->group_role_id),
        ])->seeJsonContains([
            'id' => strval($accounts->id),
            'account_number' => strval($accounts->account_number),
            'account_type' => strval($accounts->account_type),
            'account_name' => strval($accounts->account_name),
        ]);
    }

    public function testQueryAccountsFilterByGroupTypeId(): void
    {
        $this->login();

        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->graphQL('
            query TestAccountListFilters($group_type: Mixed) {
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
            }
        ', [
            'group_type' => strval($accounts->group_type_id),
        ])->seeJsonContains([
            'id' => strval($accounts->id),
            'account_number' => strval($accounts->account_number),
            'account_type' => strval($accounts->account_type),
            'account_name' => strval($accounts->account_name),
        ]);
    }

    public function testQueryAccountsFilterByMemberId(): void
    {
        $this->login();

        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->graphQL('
            query TestAccountListFilters($member_id: Mixed) {
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
            }
        ', [
            'member_id' => strval($accounts->member_id),
        ])->seeJsonContains([
            'id' => strval($accounts->id),
            'account_number' => strval($accounts->account_number),
            'account_type' => strval($accounts->account_type),
            'account_name' => strval($accounts->account_name),
        ]);
    }

    public function testQueryAccountsFilterByCommissionTemplateId(): void
    {
        $this->login();

        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->graphQL('
            query TestAccountListFilters($commission_template: Mixed) {
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
            }
        ', [
            'commission_template' => strval($accounts->commission_template_id),
        ])->seeJsonContains([
            'id' => strval($accounts->id),
            'account_number' => strval($accounts->account_number),
            'account_type' => strval($accounts->account_type),
            'account_name' => strval($accounts->account_name),
        ]);
    }

    public function testQueryAccountsFilterByAccountStateId(): void
    {
        $this->login();

        $accounts = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $this->graphQL('
            query TestAccountListFilters($account_state: Mixed) {
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
            }
        ', [
            'account_state' => strval($accounts->account_state_id),
        ])->seeJsonContains([
            'id' => strval($accounts->id),
            'account_number' => strval($accounts->account_number),
            'account_type' => strval($accounts->account_type),
            'account_name' => strval($accounts->account_name),
        ]);
    }
}
