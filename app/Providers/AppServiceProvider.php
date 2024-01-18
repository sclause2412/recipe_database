<?php

namespace App\Providers;

use App\Http\Controllers\TranslationController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();


        Blueprint::macro(
            'extendedtimestamps',
            function () {
                /** @var Blueprint $this */
                $this->timestamp('created_at')->nullable();
                $this->ulid('created_by')->nullable();
                $this->timestamp('updated_at')->nullable();
                $this->ulid('updated_by')->nullable();
                $this->timestamp('deleted_at')->nullable();
                $this->ulid('deleted_by')->nullable();
            }
        );

        Builder::macro('search', function ($fields, $string) {
            /** @var Builder $this */
            $qb = $this;

            if ($string == '') {
                return $qb;
            }

            $string = strtolower($string);

            if (!is_array($fields)) {
                $fields = [$fields];
            }

            $columns = $this->getConnection()->getSchemaBuilder()->getcolumns($this->getModel()->getTable());
            $columns = array_reduce($columns, function ($result, $item) {
                $coll = strtolower($item['collation']);
                $result[$item['name']] = substr($coll, -3) == '_cs' || substr($coll, -4) == '_bin';
                return $result;
            }, []);

            $qb->where(function (Builder $query) use ($fields, $string, $columns) {
                foreach ($fields as $field) {
                    $fa = explode('.', $field);
                    if (count($fa) == 1) {
                        if ($columns[$fa[0]] ?? false) {
                            $query->orWhereRaw('LOWER(`' . $fa[0] . '`) like ?', ['%' . $string . '%']);
                        } else {
                            $query->orWhere($fa[0], 'like', '%' . $string . '%');
                        }
                    }
                    if (count($fa) == 2) {
                        $query->orWhereHas($fa[0], function (Builder $query) use ($fa, $string, $columns) {
                            if ($columns[$fa[0]] ?? false) {
                                $query->orWhereRaw('LOWER(`' . $fa[1] . '`) like ?', ['%' . $string . '%']);
                            } else {
                                $query->where($fa[1], 'like', '%' . $string . '%');
                            }
                        });
                    }
                }
            });

            return $qb;
        });

        Collection::macro('paginate', function ($perPage, $columns = null, $pageName = 'page', $page = null) {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            /** @var Illuminate\Database\Eloquent\Collection $this */
            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });

        Lang::handleMissingKeysUsing(function (string $key, array $replacements, string $locale) {
            return TranslationController::add_missing($key, $locale);
        });
    }
}
