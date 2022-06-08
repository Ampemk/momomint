<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;

class MomoStatement extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\MomoStatement::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'from_name',
        'to_name',
        'from_no',
        'to_no'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            DateTime::make('transaction_date')->sortable(),
            Text::make('f_id')->sortable(),
            Text::make('amount')->sortable(),
            Text::make('fees'),
            Text::make('e_levy'),
            Text::make('from_name'),
            Text::make('to_name'),
            Text::make('ref'),
            Text::make('from_acct')->hideFromIndex(),
            Text::make('from_no')->hideFromIndex(),
            Text::make('transaction_type')->sortable(),
            Text::make('bal_before')->hideFromIndex(),
            Text::make('bal_after')->hideFromIndex(),
            Text::make('to_no')->hideFromIndex(),
            Text::make('to_acct')->hideFromIndex(),
            Text::make('ova')->hideFromIndex(),
            HasOne::make('User')->hideFromIndex(),
            HasOne::make('Account')->hideFromIndex(),
            BelongsTo::make('StatementFile')->hideFromIndex(),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
