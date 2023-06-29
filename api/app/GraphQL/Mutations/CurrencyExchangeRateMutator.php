<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\GraphQL\Mutations\Traits\UpdateOrCreateCurrencyExchangeRateTrait;
use App\Models\Currencies;
use App\Models\CurrencyExchangeRate;
use App\Models\QuoteProvider;
use App\Services\FileReaderService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Http\Request;

class CurrencyExchangeRateMutator
{

    use UpdateOrCreateCurrencyExchangeRateTrait;

    public function __construct(protected FileReaderService $fileReaderService)
    {
    }

    /**
     * @throws GraphqlException
     */
    public function create(mixed $root, array $args): CurrencyExchangeRate
    {
        try {
            DB::beginTransaction();

            /** @var QuoteProvider $quoteProvider */
            $quoteProvider = QuoteProvider::query()->find($args['quote_provider_id']);
            if (!$quoteProvider) {
                throw new GraphqlException('Quote Provider not found', 'not found', 404);
            }

            $srcDst = collect([
                [
                    'currency_src_id' => $args['currency_src_id'],
                    'currency_dst_id' => $args['currency_dst_id'],
                    'created_at' => Carbon::now(),
                    'rate' => $args['rate']
                ]
            ]);
            $result = [];
            $this->updateOrCreateRate($srcDst, $quoteProvider, $result);

            DB::commit();

            return $result[0];
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new GraphqlException($e->getMessage(), $e->getCode());
        }
    }


    /**
     * @throws GraphqlException
     */
    public function update(mixed $root, array $args): CurrencyExchangeRate
    {
        try {
            DB::beginTransaction();

            /** @var CurrencyExchangeRate $currencyExchangeRate */
            $currencyExchangeRate = CurrencyExchangeRate::query()->with('quoteProvider')->find($args['id']);
            if (!$currencyExchangeRate) {
                throw new GraphqlException('Not found exchange rate', 'not found', 404);
            }

            $srcDst = collect([
                [
                    'currency_src_id' => $currencyExchangeRate->currency_src_id,
                    'currency_dst_id' => $currencyExchangeRate->currency_dst_id,
                    'created_at' => Carbon::now(),
                    'rate' => $args['rate']
                ]
            ]);
            $result = [];
            $this->updateOrCreateRate($srcDst, $currencyExchangeRate->quoteProvider, $result);

            DB::commit();

            return $result[0];

        } catch (\Throwable $e) {
            DB::rollBack();
            throw new GraphqlException($e->getMessage(), $e->getCode());
        }
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

        $this->updateOrCreateRate($parsedContent, $quoteProvider);

        return $quoteProvider;
    }

}
