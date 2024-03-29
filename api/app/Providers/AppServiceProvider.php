<?php

namespace App\Providers;

use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /**
         * Paginate a standard Laravel Collection.
         *
         * @param int $perPage
         * @param int $total
         * @param int $page
         * @param string $pageName
         * @return array
         */
        Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });

        Str::macro('decimal', function ($amount) {
            return number_format($amount, 5, '.', '');
        });

        \Illuminate\Database\Query\Builder::macro('prefixes', function () {
            /** @var Builder $this */
            if (ApplicantIndividual::query()->getModel()->getTable() == $this->from) {
                $prefix = ApplicantIndividual::ID_PREFIX;
            } else {
                $prefix = ApplicantCompany::ID_PREFIX;
            }
            $this->addSelect('*')->selectRaw("concat('{$prefix}', id::text) as prefix");

            return $this;
        });

        \Illuminate\Database\Query\Builder::macro('toRawSql', function () {
            $bindings = $this->getBindings();
            return [$this->toSql(),$bindings];
        });

        \Illuminate\Database\Eloquent\Builder::macro('toRawSql', function () {
            return ($this->getQuery()->toRawSql());
        });


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\Illuminate\Contracts\Routing\ResponseFactory::class, function () {
            return new \Laravel\Lumen\Http\ResponseFactory();
        });
        $this->app->bind(\Illuminate\Contracts\Routing\UrlGenerator::class, function ($app) {
            return new \Laravel\Lumen\Routing\UrlGenerator($app);
        });

        Relation::enforceMorphMap([
            'ApplicantIndividual' => 'App\Models\ApplicantIndividual',
            'ApplicantCompany' => 'App\Models\ApplicantCompany',
            'Members' => 'App\Models\Members',
            'PaymentProvider' => 'App\Models\PaymentProvider',
            'PaymentProviderIban' => 'App\Models\PaymentProviderIban',
            'QuoteProvider' => 'App\Models\QuoteProvider',
            'Outgoing' => 'App\Models\TransferOutgoing',
            'Incoming' => 'App\Models\TransferIncoming',
            'Account' => 'App\Models\Account',
        ]);
    }
}
