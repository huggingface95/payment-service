<?php

namespace App\GraphQL\Mutations;

use App\DTO\Email\Request\EmailTrustedDeviceRequestDTO;
use App\DTO\GraphQLMutator\ActiveSessionCloneDTO;
use App\DTO\GraphQLResponse\ActiveSessionMutatorResponse;
use App\DTO\TransformerDTO;
use App\Models\Clickhouse\ActiveSession;
use App\Services\EmailService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class ActiveSessionMutator
{
    public EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function update($root, array $args)
    {
        $record = DB::connection('clickhouse')
            ->query()
            ->from((new ActiveSession())->getTable())
            ->where('id', '=', $args['id'])
            ->orderBy('created_at')
            ->first();

        if ($record) {
            $recordDTO = (array) TransformerDTO::transform(ActiveSessionCloneDTO::class, $record, true);

            if (DB::connection('clickhouse')
                 ->query()
                 ->from((new ActiveSession())->getTable())
                 ->insert($recordDTO)) {
                $redis = Redis::connection('go-auth');

                $redis->rpush(
                    config('mail.redis.device'),
                    json_encode(TransformerDTO::transform(EmailTrustedDeviceRequestDTO::class, $recordDTO, auth()->user()))
                );

                return TransformerDTO::transform(ActiveSessionMutatorResponse::class, true);
            }
        }

        return TransformerDTO::transform(ActiveSessionMutatorResponse::class, false);
    }
}
