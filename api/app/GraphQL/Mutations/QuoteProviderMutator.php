<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\Currencies;
use App\Models\CurrencyRateHistory;
use App\Models\QuoteProvider;
use App\Services\FileReaderService;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Http\Request;

class QuoteProviderMutator
{

    public function __construct(protected FileReaderService $fileReaderService)
    {
    }

    /**
     * @throws GraphqlException
     */
    public function attachFIleData(mixed $root, array $args): QuoteProvider
    {
        /** @var QuoteProvider $quoteProvider */
        $quoteProvider = QuoteProvider::query()->find($args['id']);
        if (!$quoteProvider) {
            throw new GraphqlException('Quote Provider not found', 'not found', 404);
        }

        $request = new Request(array_merge($args));

        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,xls,txt,xml|max:102400',
        ], [
            'mimes' => 'Please insert only csv, xls, txt, xml files',
            'max' => 'File should be less than 100 MB',
        ]);

        if ($validator->fails()) {
            throw new GraphqlException(implode('&', $validator->errors()->get('file')), 'Unprocessable entity', 422);
        }

        $parsedContent = collect($this->fileReaderService->getFileContent($request->file, [
            'src' => 'currency_src_id:' . Currencies::class . ':code',
            'dst' => 'currency_dst_id:' . Currencies::class . ':code',
            'rate' => 'rate',
            'time' => 'created_at'
        ]))->sortBy('created_at');

        $quoteProvider->currencyRateHistories()->saveMany($parsedContent->map(function ($item) {
            return new CurrencyRateHistory($item);
        }));

        $parsedContent->groupBy(['currency_src_id', 'currency_dst_id'])->map(function ($groups) use ($quoteProvider) {
            $groups->each(function ($v) use ($quoteProvider) {
                $v = $v->last();
                $quoteProvider->currencyExchangeRates()->updateOrCreate([
                    'currency_from_id' => $v['currency_src_id'],
                    'currency_to_id' => $v['currency_dst_id'],
                ], ['rate' => $v['rate']]);
            });
        });

        return $quoteProvider;
    }

}
