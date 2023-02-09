<?php

namespace App\GraphQL\Mutations;

use App\DTO\Email\SmtpConfigDTO;
use App\DTO\Email\SmtpDataDTO;
use App\DTO\GraphQLResponse\EmailTemplateOnCompanyResponse;
use App\DTO\TransformerDTO;
use App\Exceptions\GraphqlException;
use App\Jobs\SendMailJob;
use App\Models\Company;
use App\Models\EmailSmtp;
use App\Models\EmailTemplate;
use App\Models\EmailTemplateLayout;
use App\Models\Members;
use Illuminate\Support\Facades\Auth;

class EmailTemplateMutator extends BaseMutator
{
    public function create($root, array $args): EmailTemplateOnCompanyResponse
    {
        /** @var Members $member */
        $member = Auth::user();
        $args['member_id'] = $member->id;

        /** @var EmailTemplate $emailTemplate */
        $emailTemplate = EmailTemplate::create($args);

        if ($emailTemplate->useLayout()) {
            $this->compareLayoutHeaderAndFooter($emailTemplate, $args['header'] ?? null, $args['footer'] ?? null);
        }

        if ($member->role->IsSuperAdmin()) {
            Company::query()->where('id', '<>', $args['company_id'])->get()
                ->map(function ($company) use ($args) {
                    $args['company_id'] = $company->id;

                    return new EmailTemplate($args);
                })
                ->each(function (EmailTemplate $template) use ($args) {
                    $template->save();
                    if ($template->useLayout()) {
                        $this->compareLayoutHeaderAndFooter($template, $args['header'] ?? null, $args['footer'] ?? null);
                    }
                });
        }

        return TransformerDTO::transform(EmailTemplateOnCompanyResponse::class, $emailTemplate);
    }

    public function update($root, array $args): EmailTemplateOnCompanyResponse
    {
        /** @var EmailTemplate $emailTemplate */
        $emailTemplate = EmailTemplate::find($args['id']);

        $emailTemplate->update($args);

        if ($emailTemplate->useLayout()) {
            $this->compareLayoutHeaderAndFooter($emailTemplate, $args['header'] ?? null, $args['footer'] ?? null);
        }

        return TransformerDTO::transform(EmailTemplateOnCompanyResponse::class, $emailTemplate);
    }

    public function sendEmailWithData($root, array $args): array
    {
        try {
            if (! $this->validEmail($args['email'])) {
                throw new GraphqlException('Email not correct', 'Bad Request', 400);
            }
            /** @var Members $member */
            $member = Auth::user();

            /** @var EmailSmtp $smtp */
            $smtp = EmailSmtp::where('member_id', $member->id)->where('company_id', $args['company_id'])->first();
            if (! $smtp) {
                throw new GraphqlException('SMTP configuration for this company not found', 'Not found', '404');
            }
            $smtp->replay_to = $args['email'];

            if (array_key_exists('content', $args)) {
                $args['content'] = isset($args['header']) ? $args['header'].$args['content'] : $args['content'];
                $args['content'] = isset($args['footer']) ? $args['content'].$args['footer'] : $args['content'];
            }

            $data = TransformerDTO::transform(SmtpDataDTO::class, $smtp, $args['content'] ?? ' ', $args['subject']);
            $config = TransformerDTO::transform(SmtpConfigDTO::class, $smtp);
            dispatch(new SendMailJob($config, $data));

            return ['status' => 'OK', 'message' => 'Email sent for processing'];
        } catch (\Throwable $e) {
            throw new GraphqlException($e->getMessage(), 'Internal', $e->getCode());
        }
    }

    private function compareLayoutHeaderAndFooter(EmailTemplate $emailTemplate, string $header = null, string $footer = null)
    {
        $layout = EmailTemplateLayout::firstOrCreate(['company_id' => $emailTemplate->company_id]);
        if ($header && $header != $layout->header) {
            $layout->update(['header' => $header]);
        }
        if ($footer && $footer != $layout->footer) {
            $layout->update(['footer' => $footer]);
        }
    }
}
