<?php

namespace App\GraphQL\Mutations;

use App\DTO\Email\SmtpConfigDTO;
use App\DTO\Email\SmtpDataDTO;
use App\DTO\TransformerDTO;
use App\Exceptions\GraphqlException;
use App\Jobs\Redis\IbanIndividualActivationJob;
use App\Jobs\SendMailJob;
use App\Models\Account;
use App\Models\AccountState;
use App\Models\EmailSmtp;
use App\Models\EmailTemplate;
use App\Models\GroupRole;
use App\Models\Region;
use App\Traits\ReplaceRegularExpressions;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class RegionMutator
{

    /**
     * @throws GraphqlException
     */
    public function create($root, array $args): LengthAwarePaginator
    {
        Region::create($args);

        if (isset($args['query'])) {
            return Region::getAccountFilter($args['query'])->paginate(env('PAGINATE_DEFAULT_COUNT'));
        } else {
            return Region::paginate(env('PAGINATE_DEFAULT_COUNT'));
        }
    }
}
